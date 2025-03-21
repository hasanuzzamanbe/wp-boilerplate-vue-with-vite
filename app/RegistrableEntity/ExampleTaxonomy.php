<?php

namespace PluginClassName\RegistrableEntity;

use PluginClassName\Foundation\RegistrableEntity\Taxonomy;

class ExampleTaxonomy extends Taxonomy
{
	public function __construct()
	{
		parent::__construct('genre');

		$this->for(['example'])
			->label('name', 'Genres')
			->label('singular_name', 'Genre')
			->label('search_items', 'Search Genres')
			->label('all_items', 'All Genres')
			->label('edit_item', 'Edit Genre')
			->label('update_item', 'Update Genre')
			->label('add_new_item', 'Add New Genre')
			->label('new_item_name', 'New Genre Name')
			->hierarchical(true)
		;
	}
}
