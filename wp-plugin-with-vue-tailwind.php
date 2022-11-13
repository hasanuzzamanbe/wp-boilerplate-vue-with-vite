<?php

/**
 * Plugin Name: WP with vue-tailwind-vite
 * Plugin URI: http://wpminers.com/
 * Description: A sample Wordpress plugin to implement Vue with tailwind.
 * Author: Hasanuzzaman Shamim
 * Author URI: http://hasanuzzaman.com/
 * Version: 1.0.5
 */
define('WPM_URL', plugin_dir_url(__FILE__));
define('WPM_DIR', plugin_dir_path(__FILE__));

define('WPM_VERSION', '1.0.5');

// This will automatically update, when you run dev or production
define('WPM_DEVELOPMENT', 'yes');

class WPPluginWithVueTailwind {
    public function boot()
    {
        $this->loadClasses();
        $this->registerShortCodes();
        $this->ActivatePlugin();
        $this->renderMenu();
        $this->registerHooks();
    }
    public function registerHooks()
    {
        add_filter('script_loader_tag', array($this, 'addModuleToScript'), 10, 3);
    }

    public function loadClasses()
    {
        require WPM_DIR . 'includes/autoload.php';
    }

    public function renderMenu()
    {
        add_action('admin_menu', function () {
            if (!current_user_can('manage_options')) {
                return;
            }
            global $submenu;
            add_menu_page(
                'WPPluginVueTailwind',
                'WP Plugin Vue Tailwind',
                'manage_options',
                'wpp-plugin-with-vue-tailwind.php',
                array($this, 'renderAdminPage'),
                'dashicons-editor-code',
                25
            );
            $submenu['wpp-plugin-with-vue-tailwind.php']['dashboard'] = array(
                'Dashboard',
                'manage_options',
                'admin.php?page=wpp-plugin-with-vue-tailwind.php#/',
            );
            $submenu['wpp-plugin-with-vue-tailwind.php']['contact'] = array(
                'Contact',
                'manage_options',
                'admin.php?page=wpp-plugin-with-vue-tailwind.php#/contact',
            );
        });
    }

    public function addModuleToScript($tag, $handle, $src)
    {
        if ($handle === 'WPWVT-script-boot') {
            $tag = '<script type="module" id="WPWVT-script-boot" src="' . esc_url($src) . '"></script>';
        }
        return $tag;
    }

    public function renderAdminPage()
    {
        $loadAssets = new \WPPluginWithVueTailwind\Classes\LoadAssets();
        $loadAssets->enqueueAssets();

        $WPWVT = apply_filters('WPWVT/admin_app_vars', array(
            'assets_url' => WPM_URL . 'assets/',
            'ajaxurl' => admin_url('admin-ajax.php')
        ));

        wp_localize_script('WPWVT-script-boot', 'WPWVTAdmin', $WPWVT);

        echo '<div class="WPWVT-admin-page" id="WPWVT_app">
            <div class="main-menu text-white-200 bg-wheat-600 p-4">
                <router-link to="/">
                    Home
                </router-link> |
                <router-link to="/contact" >
                    Contacts
                </router-link>
            </div>
            <hr/>
            <router-view></router-view>
        </div>';
    }

    public function registerShortCodes()
    {
        // your shortcode here
    }

    public function ActivatePlugin()
    {
        //activation deactivation hook
        register_activation_hook(__FILE__, function ($newWorkWide) {
            require_once(WPM_DIR . 'includes/Classes/Activator.php');
            $activator = new \WPPluginWithVueTailwind\Classes\Activator();
            $activator->migrateDatabases($newWorkWide);
        });
    }
}

(new WPPluginWithVueTailwind())->boot();



