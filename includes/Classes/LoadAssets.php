<?php

namespace WPPluginWithVueTailwind\Classes;

use WPPluginWithVueTailwind\Classes\Vite;

class LoadAssets
{
    public function admin()
    {
        Vite::enqueueScript('WPWVT-script-boot', 'admin/start.js', array('jquery'), WPM_VERSION, true);
    }
  
}
