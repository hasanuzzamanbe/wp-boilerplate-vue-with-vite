<?php

namespace PluginClassName\Foundation\RegistrableEntity;

use PluginClassName\Foundation\RegistrableEntity;

class CPT extends RegistrableEntity
{
    protected string $slug;
    protected array $args = [];
    protected array $taxonomies = [];

    public function __construct(string $slug)
    {
        $this->slug = $slug;
        $this->args = [
            'public' => true,
            'has_archive' => true,
            'show_in_menu' => true,
            'supports' => ['title', 'editor'],
            'labels' => [],
        ];
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function toArray(): array
    {
        return $this->args;
    }

    public function title(string $title): static
    {
        $this->args['labels']['name'] = $title;
        $this->args['labels']['menu_name'] = $title;
        return $this;
    }

    public function singular_name(string $singularName): static
    {
        $this->args['labels']['singular_name'] = $singularName;
        $this->args['labels']['add_new'] = "Add New $singularName";
        $this->args['labels']['add_new_item'] = "Add New $singularName";
        $this->args['labels']['edit_item'] = "Edit $singularName";
        $this->args['labels']['new_item'] = "New $singularName";
        $this->args['labels']['view_item'] = "View $singularName";
        $this->args['labels']['search_items'] = "Search $singularName";
        return $this;
    }

    public function plural_name(string $pluralName): static
    {
        $this->args['labels']['all_items'] = "All $pluralName";
        $this->args['labels']['name'] = $pluralName;
        return $this;
    }

    public function supports(array $supports): static
    {
        $this->args['supports'] = $supports;
        return $this;
    }

    public function menu_icon(string $icon): static
    {
        $this->args['menu_icon'] = $icon;
        return $this;
    }

    public function show_in_menu(): static
    {
        $this->args['show_in_menu'] = true;
        return $this;
    }

    public function hide_in_menu(): static
    {
        $this->args['show_in_menu'] = false;
        return $this;
    }

    public function add_category_support(array|string $taxonomies = []): static
    {
        $this->taxonomies = array_merge(
            $this->taxonomies,
            is_array($taxonomies) ? $taxonomies : [$taxonomies]
        );
    
        return $this;
    }    

    public function add_tag_support(): static
    {
        $this->taxonomies[] = 'post_tag';
        return $this;
    }

    public function register(): void
    {
        register_post_type($this->slug, $this->args);

        foreach ($this->taxonomies as $taxonomy) {
            register_taxonomy_for_object_type($taxonomy, $this->slug);
        }
    }
}