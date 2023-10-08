<?php

namespace WPPluginVueTailwind\Classes;

class LoadAssets
{
    public function admin()
    {
        Vite::enqueueScript('wpmvt-script-boot', 'admin/start.js', array('jquery'), WPM_VERSION, true);
    }
  
}
