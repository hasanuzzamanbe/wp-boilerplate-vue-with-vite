<?php

namespace PluginClassName\Foundation;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Route class handles the registration and management of WordPress REST API routes.
 *
 * This class provides a fluent interface for registering REST API routes with WordPress.
 * It supports route grouping with prefixes, different HTTP methods (GET, POST, PUT, DELETE),
 * and handles permission checks for route access.
 *
 * @package PluginClassName\Foundation
 */
class Route {
	/** @var array<int, array{method: string, path: string, callback: callable|array, permission: ?string, args: array}> */
	private static array $routes = [];

	/** @var ?string The current route group prefix */
	private static ?string $prefix = null;

	/** @var string The REST API namespace for all routes */
	private static string $namespace = 'pluginlowercase/v1';

	/**
	 * Define a prefix for a group of routes.
	 *
	 * @param string   $prefix   The prefix to apply to all routes inside the callback.
	 * @param callable $callback The callback function that registers routes.
	 * 
	 * @return void
	 */
	public static function prefix(string $prefix, callable $callback): void {
		$previousPrefix = self::$prefix;
		self::$prefix = trim($prefix, '/');

		$callback(new self());

		self::$prefix = $previousPrefix;

		add_action('rest_api_init', [self::class, 'register_routes']);
	}

	/**
	 * Registers a GET route for the WordPress REST API.
	 * 
	 * @param string          $path       The route path.
	 * @param callable|array $callback   The callback function to execute when the route is matched.
	 * @param string|null     $permission The required permission to access the route.
	 * @param array           $args       Additional arguments for the route.
	 * 
	 * @return void
	 */
	public static function get(string $path, callable|array $callback, ?string $permission = 'read', array $args = []): void {
		self::addRoute('GET', $path, $callback, $permission, $args);
	}

	/**
	 * Registers a POST route for the WordPress REST API.
	 * 
	 * @param string          $path       The route path.
	 * @param callable|array $callback   The callback function to execute when the route is matched.
	 * @param string|null     $permission The required permission to access the route.
	 * @param array           $args       Additional arguments for the route.
	 * 
	 * @return void
	 */
	public static function post(string $path, callable|array $callback, ?string $permission = 'edit_posts', array $args = []): void {
		self::addRoute('POST', $path, $callback, $permission, $args);
	}

	/**
	 * Registers a PUT route for the WordPress REST API.
	 * 
	 * @param string          $path       The route path.
	 * @param callable|array $callback   The callback function to execute when the route is matched.
	 * @param string|null     $permission The required permission to access the route.
	 * @param array           $args       Additional arguments for the route.
	 * 
	 * @return void
	 */
	public static function put(string $path, callable|array $callback, ?string $permission = 'edit_posts', array $args = []): void {
		self::addRoute('PUT', $path, $callback, $permission, $args);
	}

	/**
	 * Registers a DELETE route for the WordPress REST API.
	 * 
	 * @param string          $path       The route path.
	 * @param callable|array $callback   The callback function to execute when the route is matched.
	 * @param string|null     $permission The required permission to access the route.
	 * @param array           $args       Additional arguments for the route.
	 * 
	 * @return void
	 */
	public static function delete(string $path, callable|array $callback, ?string $permission = 'delete_posts', array $args = []): void {
		self::addRoute('DELETE', $path, $callback, $permission, $args);
	}

	/**
	 * Adds a new route to the global route list.
	 * 
	 * This method processes the route parameters, handles controller callbacks,
	 * and adds the route to the WordPress REST API registration queue.
	 * 
	 * @param string          $method     The HTTP method for the route (GET, POST, PUT, DELETE).
	 * @param string          $path       The route path, can include dynamic parameters in {param} format.
	 * @param callable|array  $callback   The callback function or [ControllerClass, method] array.
	 * @param string|null     $permission The WordPress capability required to access this route.
	 * @param array<string, mixed> $args  Additional arguments for route registration.
	 * 
	 * @throws \Exception When the controller class or method doesn't exist.
	 * @return void
	 */
	private static function addRoute(string $method, string $path, callable|array $callback, ?string $permission, array $args): void {
		if (!isset(self::$routes)) {
			self::$routes = [];
		}
		
		$path = '/' . trim($path, '/');

		if (self::$prefix !== null) {
			$path = '/' . trim(self::$prefix, '/') . $path;
		}

		$path = preg_replace('/\{(\w+)\}/', '(?P<$1>[\w-]+)', $path);

		if (is_array($callback) && count($callback) === 2) {
			[$controller, $controllerMethod] = $callback;
	
			if (!class_exists($controller)) {
				throw new \Exception(sprintf('Controller %s does not exist.', esc_html($controller)));
			}
	
			if (!method_exists($controller, $controllerMethod)) {
				throw new \Exception(sprintf('Method %s does not exist in controller %s.', esc_html($controllerMethod), esc_html($controller)));
			}
	
			if (!(new \ReflectionMethod($controller, $controllerMethod))->isStatic()) {
				$callback = [new $controller(), $controllerMethod];
			}
			$controller = new $controller();
		}


		self::$routes[] = [
			'method'     => $method,
			'path'       => $path,
			'callback'   => $callback,
			'permission' => $permission,
			'args'       => $args,
		];

		add_action('rest_api_init', [self::class, 'register_routes']);
	}

	/**
	 * Registers all stored routes with WordPress REST API.
	 * 
	 * This method is called during the 'rest_api_init' action and handles the actual
	 * registration of routes with WordPress. It ensures each route is registered only once
	 * and properly formats the route path according to WordPress REST API requirements.
	 * 
	 * @return void
	 */
	public static function register_routes(): void {
		static $registered_routes = [];

		foreach (self::$routes as $route) {
			// Create a unique identifier for this route
			$route_id = $route['method'] . $route['path'];

			if (in_array($route_id, $registered_routes)) {
				continue;
			}

			// Remove leading slash for WordPress REST API compatibility
			$rest_path = ltrim($route['path'], '/');

			register_rest_route(self::$namespace, '/' . $rest_path, [
				'methods'  => $route['method'],
				'callback' => $route['callback'],
				'args'     => $route['args'],
				'permission_callback' => function ($request) use ($route) {
					return self::check_permissions($route['permission']);
				},
			]);

			$registered_routes[] = $route_id;
		}
	}

	/**
	 * Checks user permissions before executing the API request.
	 * 
	 * @param string|null $capability The required permission to access the route.
	 * 
	 * @return bool|\WP_Error
	 */
	private static function check_permissions(?string $capability): bool|\WP_Error
	{
		if ($capability === null) {
			return true;
		}

		if (!current_user_can($capability)) {
			return new \WP_Error('rest_forbidden', __('You do not have permissions to access this resource.', 'pluginslug'), ['status' => 403]);
		}
		return true;
	}
}