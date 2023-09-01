<?php

namespace WPPluginWithVueTailwind\Classes;

class LoadAssets
{
    public function admin()
    {
        Vite::enqueueScript('WPWVT-script-boot', 'admin/start.js', array('jquery'), WPM_VERSION, true);
    }
  
}
