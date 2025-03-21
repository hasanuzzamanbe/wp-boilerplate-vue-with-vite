<?php

namespace PluginClassName\Foundation\RegistrableEntity;

use PluginClassName\Foundation\RegistrableEntity;

class Taxonomy extends RegistrableEntity
{
    protected string $slug;
    protected array $postTypes = [];
    protected array $args = [];

    public function __construct(string $slug)
    {
        $this->slug = $slug;
        $this->args = [
            'public' => true,
            'hierarchical' => true,
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

    public function for(array|string $postTypes): static
    {
        $this->postTypes = (array) $postTypes;
        return $this;
    }

    public function label(string $key, string $value): static
    {
        $this->args['labels'][$key] = $value;
        return $this;
    }

    public function hierarchical(bool $isHierarchical = true): static
    {
        $this->args['hierarchical'] = $isHierarchical;
        return $this;
    }

    public function register(): void
    {
        register_taxonomy($this->slug, $this->postTypes, $this->args);
    }
}