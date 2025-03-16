<?php

namespace PluginClassName\Models;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Abstract base model class for database operations.
 * 
 * This class provides a fluent interface for database operations including:
 * - CRUD operations (Create, Read, Update, Delete)
 * - Query building with where clauses, ordering, and pagination
 * - Relationship handling (hasOne, hasMany, belongsTo)
 * - Automatic timestamp management
 * - Soft delete functionality
 */
abstract class Model
{
    /** @var string The database table associated with the model */
    protected static string $table = '';
    /** @var string The primary key column name */
    protected static string $primaryKey = 'ID';
    /** @var bool Whether to automatically manage created_at and updated_at timestamps */
    protected static bool $timestamps = true;
    /** @var bool Whether the model supports soft deletes via deleted_at */
    protected static bool $trashable = true;
    /** @var ?\wpdb WordPress database connection instance */
    protected static ?\wpdb $db = null;
    /** @var array Global scope conditions applied to all queries */
	protected static array $globalScope = [];

    /** @var array Collection of where conditions for the query */
    private array $conditions = [];
    /** @var ?string ORDER BY clause for the query */
    private ?string $orderBy = null;
    /** @var ?string LIMIT clause for the query */
    private ?string $limit = null;
    /** @var array Parameter bindings for prepared statements */
    private array $bindings = [];
    /** @var array Columns to select in the query */
    private array $columns = ['*'];
    /** @var ?string OFFSET clause for the query */
    private ?string $offset = null;

    /**
     * Get the WordPress database connection instance.
     *
     * @return \wpdb The WordPress database connection
     */
    private static function db(): \wpdb
    {
        if (!self::$db) {
            global $wpdb;
            self::$db = $wpdb;
        }
        return self::$db;
    }

    /**
     * Initialize a new model instance.
     *
     * @throws \InvalidArgumentException If table name is not set
     */
    public function __construct()
    {
        if (empty(static::$table)) {
            throw new \InvalidArgumentException('Table name is required');
        }

        if (static::$timestamps) {
            $this->columns[] = 'created_at';
            $this->columns[] = 'updated_at';
        }

        if (static::$trashable) {
            $this->columns[] = 'deleted_at';
        }

        $this->applyGlobalScope();
    }

    /**
     * Start a new query builder instance.
     *
     * @return static A new instance of the model
     */
    public static function query(): self {
        return new static();
    }

	private function applyGlobalScope(): void
    {
        foreach (static::$globalScope as $column => $value) {
            $this->where($column, $value);
        }
    }

    /**
     * Set the columns to be selected.
     *
     * @param array $columns The columns to select
     * @return static The current query builder instance
     */
    public function select(array $columns): self
    {
        $this->columns = array_map(fn($col) => "`{$col}`", $columns);
        return $this;
    }

    /**
     * Find a record by its primary key.
     *
     * @param int $id The primary key value
     * @return array|null The found record or null if not found
     */
    public static function find(int $id): ?array
    {
        return self::query()->where(static::$primaryKey, $id, '=')->first();
    }

    /**
     * Get all records from the database.
     *
     * @return array Array of all records
     */
    public static function all(): array
    {
        return self::query()->get();
    }

    /**
     * Add a where clause to the query.
     *
     * @param string $column The column name
     * @param mixed $value The value to compare against
     * @param string $operator The comparison operator
     * @return static The current query builder instance
     * @throws \InvalidArgumentException If an invalid operator is provided
     */
    public function where(string $column, mixed $value, string $operator = '='): self
    {
        $validOperators = ['=', '!=', '<', '>', '<=', '>=', 'LIKE', 'NOT LIKE', 'IN', 'NOT IN'];
        if (!in_array(strtoupper($operator), $validOperators, true)) {
            throw new \InvalidArgumentException("Invalid SQL operator: {$operator}");
        }
        
        $this->conditions[] = "`" . esc_sql($column) . "` {$operator} %s";
        $this->bindings[] = $value;
        return $this;
    }

    /**
     * Add an order by clause to the query.
     *
     * @param string $column The column to order by
     * @param string $direction The sort direction (ASC or DESC)
     * @return static The current query builder instance
     */
    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $this->orderBy = sprintf("ORDER BY %s %s", esc_sql($column), esc_sql($direction));
        return $this;
    }

    /**
     * Set the maximum number of records to return.
     *
     * @param int $number The maximum number of records
     * @return static The current query builder instance
     */
    public function limit(int $number): self
    {
        $this->limit = sprintf("LIMIT %d", $number);
        return $this;
    }

    /**
     * Set the number of records to skip.
     *
     * @param int $number The number of records to skip
     * @return static The current query builder instance
     */
    public function offset(int $number): self
    {
        $this->offset = sprintf("OFFSET %d", $number);
        return $this;
    }

    /**
     * Build the complete SQL query string.
     *
     * @param string $baseQuery The base SQL query
     * @return string The complete SQL query
     */
    private function buildQuery(string $baseQuery): string
    {
        if (!empty($this->conditions)) {
            $baseQuery .= " WHERE " . implode(' AND ', $this->conditions);
        }
        if ($this->orderBy) {
            $baseQuery .= " " . $this->orderBy;
        }
        if ($this->limit) {
            $baseQuery .= " " . $this->limit;
        }
        if ($this->offset) {
            $baseQuery .= " " . $this->offset;
        }
        return $baseQuery;
    }

    /**
     * Execute the query and get the results.
     *
     * @return array The query results
     */
    public function get(): array
    {
        $columns = implode(', ', array_map(fn($col) => esc_sql($col), $this->columns));
        $query = $this->buildQuery("SELECT {$columns} FROM " . static::$table);
        return self::db()->get_results(self::db()->prepare($query, ...$this->bindings), ARRAY_A);
    }

    /**
     * Get the first record matching the query.
     *
     * @return array|null The first matching record or null if none found
     */
    public function first(): ?array
    {
        $this->limit(1);
        $results = $this->get();
        return $results[0] ?? null;
    }

    /**
     * Check if any record exists matching the query.
     *
     * @return bool True if records exist, false otherwise
     */
    public function exists(): bool
    {
        $query = $this->buildQuery("SELECT 1 FROM " . static::$table) . " LIMIT 1";
        return (bool) self::db()->get_var(self::db()->prepare($query, ...$this->bindings));
    }

    /**
     * Count the number of records matching the query.
     *
     * @return int The number of matching records
     */
    public function count(): int
    {
        $query = $this->buildQuery("SELECT COUNT(*) FROM " . static::$table);
        return (int) self::db()->get_var(self::db()->prepare($query, ...$this->bindings));
    }

    /**
     * Create a new record in the database.
     *
     * @param array $data The data to insert
     * @return int The ID of the newly created record
     */
    public static function create(array $data): int
    {
        if (static::$timestamps) {
            $data['created_at'] = current_time('mysql');
            $data['updated_at'] = current_time('mysql');
        }
        $inserted = self::db()->insert(static::$table, $data);
        return $inserted ? self::db()->insert_id : 0;
    }

    /**
     * Update records matching the query.
     *
     * @param array $data The data to update
     * @return bool True if the update was successful
     */
    public function update(array $data): bool
    {
        if (static::$timestamps) {
            $data['updated_at'] = current_time('mysql');
        }
        $query = "UPDATE " . static::$table . " SET " . implode(', ', array_map(fn($col) => "{$col} = %s", array_keys($data)));
        $query = $this->buildQuery($query);
        return (bool) self::db()->query(self::db()->prepare($query, ...array_merge(array_values($data), $this->bindings)));
    }

    /**
     * Delete records matching the query.
     *
     * @return bool True if the deletion was successful
     */
    public function delete(): bool
    {
        $query = "DELETE FROM " . static::$table;
        $query = $this->buildQuery($query);
        return (bool) self::db()->query(self::db()->prepare($query, ...$this->bindings));
    }

    /**
     * Define a one-to-one relationship.
     *
     * @param string $relatedModel The related model class name
     * @param string $foreignKey The foreign key column name
     * @return array|null The related record
     */
    public function hasOne(string $relatedModel, string $foreignKey): ?array
    {
        return $relatedModel::query()->where($foreignKey, '=', $this->{static::$primaryKey})->first();
    }

    /**
     * Define a one-to-many relationship.
     *
     * @param string $relatedModel The related model class name
     * @param string $foreignKey The foreign key column name
     * @return array The related records
     */
    public function hasMany(string $relatedModel, string $foreignKey): array
    {
        return $relatedModel::query()->where($foreignKey, '=', $this->{static::$primaryKey})->get();
    }

    /**
     * Define a belongs-to relationship.
     *
     * @param string $relatedModel The related model class name
     * @param string $foreignKey The foreign key column name
     * @return array|null The related record
     */
    public function belongsTo(string $relatedModel, string $foreignKey): ?array
    {
        return $relatedModel::query()->where(static::$primaryKey, '=', $this->{$foreignKey})->first();
    }
}