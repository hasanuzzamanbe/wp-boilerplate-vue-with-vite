<?php

namespace PluginClassName\Foundation;

// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.SchemaChange

use wpdb;
use Exception;
use RuntimeException;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Abstract base class for database migrations.
 *
 * This class provides a foundation for creating and managing database tables
 * with features including:
 * - Automatic timestamp management
 * - Soft delete functionality
 * - Index and foreign key management
 * - Transaction support
 * - Schema validation
 */
abstract class Migration
{
	/** @var string The database table name without prefix */
	protected static string $table;

	/** @var bool Whether to add timestamp columns (created_at, updated_at) */
	protected bool $timestamps = true;

	/** @var bool Whether to add soft delete support (deleted_at) */
	protected bool $softDeletes = false;

	/** @var array List of column names to be indexed */
	protected array $indexes = [];

	/** @var array List of column names that should be unique */
	protected array $uniqueKeys = [];

	/** @var array List of foreign key configurations */
	protected array $foreignKeys = [];

	/** @var array Valid SQL column types */
	private array $validColumnTypes = [
		'INT', 'TINYINT', 'SMALLINT', 'MEDIUMINT', 'BIGINT',
		'DECIMAL', 'FLOAT', 'DOUBLE',
		'CHAR', 'VARCHAR', 'TEXT', 'TINYTEXT', 'MEDIUMTEXT', 'LONGTEXT',
		'DATE', 'DATETIME', 'TIMESTAMP', 'TIME', 'YEAR',
		'ENUM', 'SET',
		'BINARY', 'VARBINARY', 'BLOB', 'TINYBLOB', 'MEDIUMBLOB', 'LONGBLOB',
		'BIT', 'BOOLEAN'
	];

	/**
	 * Define the table schema.
	 *
	 * @return array Array of column definitions
	 */
	abstract protected function defineSchema(): array;

	/**
	 * Create the database table with the defined schema.
	 *
	 * @throws RuntimeException If table creation fails
	 */
	public function up(): void
	{
		global $wpdb;

		$table_name = esc_sql($wpdb->prefix . static::$table);
		if ($this->tableExists($table_name)) {
			return;
		}

		$wpdb->query('START TRANSACTION');

		try {
			if (empty(static::$table)) {
				throw new RuntimeException('Table name must be defined');
			}

			$charset_collate = $wpdb->get_charset_collate();

			$columns = $this->defineSchema();
			$this->validateSchema($columns);

			$columns_sql = implode(",\n", array_map(fn($col, $type) => "$col $type", array_keys($columns), $columns));

			if ($this->timestamps) {
				$columns[] = "created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP";
				$columns[] = "updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
			}

			if ($this->softDeletes) {
				$columns[] = "deleted_at TIMESTAMP NULL DEFAULT NULL";
				$this->indexes[] = 'deleted_at';
			}

			foreach ($this->indexes as $index) {
				$index = esc_sql($index);
				$columns[] = "INDEX idx_{$index} ({$index})";
			}

			foreach ($this->uniqueKeys as $unique) {
				$unique = esc_sql($unique);
				$columns[] = "UNIQUE KEY unq_{$unique} ({$unique})";
			}

			$sql = "CREATE TABLE {$table_name} (
				id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
				" . $columns_sql . ",
				PRIMARY KEY (id)
			) {$charset_collate};";

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			$result = dbDelta($sql);

			if (empty($result)) {
				throw new RuntimeException(esc_html(sprintf(
					'Failed to create table %s: %s', esc_html(static::$table), esc_html($wpdb->last_error)
				)));
			}

			$this->addForeignKeys();

			$wpdb->query('COMMIT');
		} catch (Exception $e) {
			$wpdb->query('ROLLBACK');
			throw new RuntimeException(esc_html(sprintf(
				'Migration failed for table %s: %s', esc_html(static::$table), esc_html($e->getMessage())
			)));			
		}
	}

	/**
	 * Validates the schema definition.
	 *
	 * @param array $columns The column definitions to validate
	 * @throws RuntimeException If the schema is invalid
	 */
	private function validateSchema(array $columns): void
	{
		foreach ($columns as $column) {
			$columnDef = strtoupper($column);
			$hasValidType = false;
			foreach ($this->validColumnTypes as $type) {
				if (strpos($columnDef, $type) === 0) {
					$hasValidType = true;
					break;
				}
			}
			if (!$hasValidType) {
				throw new RuntimeException(
					sprintf('Invalid column type: %s', esc_html($column))
				);
			}
		}
	}

	/**
	 * Adds foreign key constraints to the table.
	 *
	 * @throws RuntimeException If adding foreign keys fails
	 */
	private function addForeignKeys(): void
	{
		global $wpdb;
		$table_name = esc_sql($wpdb->prefix . static::$table);

		$valid_actions = ['CASCADE', 'SET NULL', 'RESTRICT', 'NO ACTION', 'SET DEFAULT'];

		foreach ($this->foreignKeys as $foreignKey) {
			$constraint_name = esc_sql($foreignKey['constraint_name']);
			$column = esc_sql($foreignKey['column']);
			$references_table = esc_sql($wpdb->prefix . $foreignKey['references_table']);
			$references_column = esc_sql($foreignKey['references_column']);
			$on_delete = strtoupper(esc_sql($foreignKey['on_delete']));
			$on_update = strtoupper(esc_sql($foreignKey['on_update']));

			if (!in_array($on_delete, $valid_actions, true)) {
				$on_delete = 'CASCADE';
			}
			if (!in_array($on_update, $valid_actions, true)) {
				$on_update = 'CASCADE';
			}

			$sql = "ALTER TABLE {$table_name} ADD CONSTRAINT {$constraint_name}
				FOREIGN KEY ({$column})
				REFERENCES {$references_table}({$references_column})
				ON DELETE {$on_delete}
				ON UPDATE {$on_update};";

			$result = $wpdb->query($wpdb->prepare($sql));

			if ($result === false) {
				throw new RuntimeException(
					sprintf(
						'Failed to add foreign key %s: %s',
						esc_html($constraint_name),
						esc_html($wpdb->last_error)
					)
				);
			}
		}
	}

	/**
	 * Drops the database table.
	 *
	 * @throws RuntimeException If dropping the table fails
	 */
	public function down(): void
	{
		global $wpdb;
		$table_name = esc_sql($wpdb->prefix . static::$table);
		$result = $wpdb->query($wpdb->prepare("DROP TABLE IF EXISTS %s", esc_sql($table_name)));

		if ($result === false) {
			throw new RuntimeException(sprintf('Failed to drop table: %s', esc_html($table_name)));
		}
	}

	/**
	 * Checks if a table exists in the database.
	 *
	 * @param string $table_name The name of the table to check
	 * @return bool Whether the table exists
	 */
	private function tableExists(string $table_name): bool
	{
		global $wpdb;
		$safe_table_name = esc_sql($table_name);
		return (bool) $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $safe_table_name));
	}
}