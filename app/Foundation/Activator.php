<?php

namespace PluginClassName\Foundation;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use RegexIterator;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Handles plugin activation and database migrations.
 * @since 1.0.0
 */
class Activator
{
	private $newWorkWide;

	public function __construct(bool $newWorkWide = false)
	{
		$this->newWorkWide = $newWorkWide;
	}

	/**
	 * Boot the activator.
	 */
	public function boot()
	{
		$this->migrateDatabases();

		flush_rewrite_rules();
	}

	/**
	 * Run all database migrations during activation.
	 *
	 * @param bool $network_wide Whether the plugin is activated network-wide.
	 */
	public function migrateDatabases(): void
	{
		global $wpdb;

		if ($this->newWorkWide && function_exists('get_sites') && function_exists('get_current_network_id')) {
			$site_ids = get_sites([
				'fields'      => 'ids',
				'network_id'  => get_current_network_id(),
			]);

			foreach ($site_ids as $site_id) {
				switch_to_blog($site_id);
				$this->migrate();
				restore_current_blog();
			}
		} else {
			$this->migrate();
		}
	}

	/**
	 * Run all registered migrations dynamically.
	 */
	private function migrate(): void
	{
		$migrations = $this->getMigrations();

		foreach ($migrations as $migrationClass) {
			if (class_exists($migrationClass) && is_subclass_of($migrationClass, Migration::class)) {
				(new $migrationClass())->up();
			}
		}
	}

	/**
	 * Get all migration classes dynamically from the Migrations directory.
	 *
	 * @return array List of migration class names
	 */
	private function getMigrations(): array
	{
		$migrations = [];
		$directory = PLUGIN_CONST_DIR . '../../database/Migrations/';

		if (!is_dir($directory)) {
			return $migrations;
		}

		$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
		$regexIterator = new RegexIterator($iterator, '/^.+\.php$/i', RegexIterator::GET_MATCH);

		foreach ($regexIterator as $file) {
			$filePath = $file[0];
			$className = basename($filePath, '.php');
			$fullClassName = "PluginClassName\\Database\\Migrations\\$className";

			if (class_exists($fullClassName) && is_subclass_of($fullClassName, Migration::class)) {
				$migrations[] = $fullClassName;
			}
		}

		return $migrations;
	}
}