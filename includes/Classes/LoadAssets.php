<?php
namespace WPPluginWithVueTailwind\Classes;

class LoadAssets
{
    public function enqueueAssets()
    {
        if (defined('WPM_DEVELOPMENT') && WPM_DEVELOPMENT === 'yes') {
            //development
            wp_enqueue_script('WPWVT-script-boot', 'http://localhost:8880/' . 'src/admin/start.js', array('jquery'), WPM_VERSION, true);
        } else {
            //production
            wp_enqueue_script('WPWVT-script-boot', WPM_URL . 'assets/js/start.js', array('jquery'), WPM_VERSION, false);
            wp_enqueue_style('WPWVT-global-styling', WPM_URL . 'assets/css/start.css', array(), WPM_VERSION);
        }
    }

}
