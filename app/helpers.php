<?php

if (!function_exists('config')) {
	/**
	 * Get the specified configuration value.
	 * 
	 * If an array is passed as the key, we will assume you want to set an array of values.
	 *
	 * @param  array<string, mixed>|string|null  $key
	 * @param  mixed  $default
	 * @return mixed
	 */
	function config($key = null, $default = null)
	{
		static $configs = [];

		$files = glob(PLUGIN_CONST_DIR . '/config/*.php') ?: [];
		if (empty($configs)) {
			foreach ($files as $file) {
				$name = basename($file, '.php');
				$configs[$name] = require $file;
			}
		}

		if (is_null($key)) {
			return $configs;
		}

		if (is_array($key)) {
			foreach ($key as $fullKey => $value) {
				$parts = explode('.', $fullKey);
				$ref =& $configs;
				foreach ($parts as $part) {
					$ref =& $ref[$part];
				}
				$ref = $value;
			}
			return true;
		}

		$segments = explode('.', $key);
		$value = $configs;

		foreach ($segments as $segment) {
			if (!is_array($value) || !array_key_exists($segment, $value)) {
				return $default;
			}
			$value = $value[$segment];
		}

		return $value;
	}
}
