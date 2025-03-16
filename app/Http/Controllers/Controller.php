<?php

namespace PluginClassName\Http\Controllers;

/**
 * Base Controller class for handling REST API responses
 *
 * This abstract class provides common response handling methods for REST API endpoints
 * in WordPress plugins. It includes methods for successful and error responses.
 */
abstract class Controller
{
    /**
     * Send a successful response with data
     *
     * @param array|object $data The data to be included in the response
     * @param int $status HTTP status code for the response
     * @return \WP_REST_Response The WordPress REST response object
     */
    protected function response(array|object $data, int $status = 200): \WP_REST_Response
    {
        return rest_ensure_response(array_merge($data, ['status' => $status]));
    }

    /**
     * Send an error response with a message
     *
     * @param string $message The error message
     * @param int $status HTTP status code for the error
     * @return \WP_REST_Response The WordPress REST response object
     */
    protected function error(string $message, int $status = 400): \WP_REST_Response
    {
        return rest_ensure_response(['error' => $message, 'status' => $status]);
    }
}
