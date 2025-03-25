<?php

namespace PluginClassName\Database\Migrations;

use PluginClassName\Foundation\Migration;

class ExampleTable extends Migration
{
	protected static string $table = 'test';

	protected bool $timestamps = true;
	protected bool $softDeletes = true;
	protected array $indexes = ['email'];
	protected array $uniqueKeys = ['email'];
	protected array $foreignKeys = [];

	protected function defineSchema(): array
	{
		return [
			"role_id" => "INT UNSIGNED NOT NULL",
			"email" => "VARCHAR(255) NOT NULL",
			"password" => "VARCHAR(255) NOT NULL",
			"name" => "VARCHAR(255) NOT NULL"
		];
	}
}