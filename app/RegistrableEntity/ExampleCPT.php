<?php

namespace PluginClassName\RegistrableEntity;

use PluginClassName\Foundation\RegistrableEntity\CPT;

/**
 * Example Post Type
 * 
 * @since 1.0.0
 */
class ExampleCPT extends CPT
{
	public function __construct()
	{
		parent::__construct('example');

		$this->title('Example')
			->singular_name('Example')
			->plural_name('Examples')
			->supports(['title', 'editor', 'thumbnail'])
			->add_category_support('genre')
		;
	}

}