<?php

namespace PluginClassName\Http\Controllers;

use PluginClassName\Models\Post;
use \WP_REST_Request as Request;

class TestController extends Controller
{
	public function test(Request $request)
	{
		$id = $request->get_param('id');

		$posts = Post::find(1)
		;
		
		return $this->response([
			'posts' => $posts,
		]);
	}
}
