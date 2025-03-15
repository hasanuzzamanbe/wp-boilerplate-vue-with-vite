<?php

namespace PluginClassName\Classes;

class LoadAssets
{
    public function admin()
    {
        Vite::enqueueScript('pluginlowercase-script-boot', 'admin/start.js',[], PLUGIN_CONST_VERSION, true);
    }
  
}
