<?php

namespace PluginClassName\Foundation;

class LoadAssets
{
	public function admin()
	{
		Vite::enqueueScript('pluginlowercase-script-boot', 'js/admin/main.js',[], PLUGIN_CONST_VERSION, true);
	}
  
}
