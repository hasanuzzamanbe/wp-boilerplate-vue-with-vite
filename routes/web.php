<?php

use PluginClassName\Foundation\Route;
use PluginClassName\Http\Controllers;

Route::prefix('/api', function (Route $route) {
	$route->get('/test', function () {
		return rest_ensure_response(['message' => 'Test API works in prefix!']);
	}, null);
});

Route::get('/test/{id}', [Controllers\TestController::class, 'test'], null);

Route::get('/test-query', [Controllers\TestController::class, 'test'], null, [
	'id' => [
		'required' => true,
		'type' => 'integer',
		'validate_callback' => function ($param, $request, $key) {
			return is_numeric($param) && $param > 0;
		}
	]
]);

Route::get('/test-function', function () {
	return rest_ensure_response(['message' => 'Test API works!']);
}, null);

