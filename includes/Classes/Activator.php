<?php

namespace PluginClassName\Classes;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Ajax Handler Class
 * @since 1.0.0
 */
class Activator
{
    public function migrateDatabases(bool $network_wide = false): void
    {
        global $wpdb;

        if ($network_wide && function_exists('get_sites') && function_exists('get_current_network_id')) {
            $site_ids = get_sites([
                'fields'      => 'ids',
                'network_id'  => get_current_network_id(),
            ]);

            foreach ($site_ids as $site_id) {
                switch_to_blog($site_id);
                $this->migrate();
                restore_current_blog();
            }
        } else {
            $this->migrate();
        }
    }

    private function migrate()
    {
        /*
        * database creation commented out,
        * If you need any database just active this function bellow
        * and write your own query at createUserFavorite function
        */

        $this->sampleTable();
    }

    public function sampleTable()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'pluginlowercase_user_favorites';

        $cached = wp_cache_get($table_name, 'database_tables');

        if ($cached === false) {
            $exists = (bool) $wpdb->get_var($wpdb->prepare(
                "SHOW TABLES LIKE %s",
                $table_name
            ));
            wp_cache_set($table_name, $exists, 'database_tables', 3600); // Cache per 1 ora
        } else {
            $exists = $cached;
        }

        if (!$exists) {
            $charset_collate = $wpdb->get_charset_collate();
            $sql = "CREATE TABLE {$table_name} (
                id INT(10) NOT NULL AUTO_INCREMENT,
                user_id INT(10) NOT NULL,
                post_id INT(10) NOT NULL,
                created_at TIMESTAMP NULL DEFAULT NULL,
                updated_at TIMESTAMP NULL DEFAULT NULL,
                PRIMARY KEY (id)
            ) $charset_collate;";

            $this->runSQL($sql, $table_name);
        }
    }

    private function runSQL($sql, $tableName)
    {
        global $wpdb;

        $exists = wp_cache_get($tableName, 'database_tables');

        if ($exists === false) {
            $exists = (bool) $wpdb->get_var($wpdb->prepare(
                "SHOW TABLES LIKE %s",
                $tableName
            ));
            wp_cache_set($tableName, $exists, 'database_tables', 3600);
        }

        if (!$exists) {
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
            dbDelta($sql);
        }
    }
}
