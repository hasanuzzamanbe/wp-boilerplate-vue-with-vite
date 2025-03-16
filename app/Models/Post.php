<?php

namespace PluginClassName\Models;

if (!defined('ABSPATH')) {
    exit;
}

class Post extends Model
{
    protected static string $table = 'wp_posts';
    protected static string $primaryKey = 'ID';
    protected static bool $timestamps = false;
    protected static bool $trashable = true;
	protected static array $globalScope = ['post_type' => 'post'];

    /**
     * Recupera tutti i post pubblicati
     */
    public static function published(): array
    {
        return self::query()->where('post_status', '=', 'publish')->get();
    }

    /**
     * Recupera un post specifico per slug
     */
    public static function findBySlug(string $slug): ?array
    {
        return self::query()->where('post_name', '=', $slug)->first();
    }

    /**
     * Aggiorna il titolo di un post
     */
    public function updateTitle(int $id, string $newTitle): bool
    {
        return self::query()->where('ID', '=', $id)->update(['post_title' => $newTitle]);
    }
}