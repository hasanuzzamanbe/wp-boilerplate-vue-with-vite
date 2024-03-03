# WP Plugin Vue Boilerplate (Vite Build)
### Run only 4 commands and make your own plugin
- `git clone https://github.com/hasanuzzamanbe/wp-boilerplate-vue-with-vite.git`
- `cd wp-boilerplate-vue-with-vite`
- `npm i`
- `node aladin` and enter your Plugin Name in the command prompt.

Aladdin 🧞‍♂️ will make it within a blink. 

Congratulations Everything is done 🥳 
`npm run dev` to run development mode. Find and activate your plugin in WordPress.

------------------
This is a Customizable Boilerplate WordPress Plugin that is developed as a single-page app with Vue js and Vite. You don't have to reload the page all the time.
Read the <a href="https://github.com/hasanuzzamanbe/wp-boilerplate-vue-with-vite/#make-your-own-plugin-from-boilerplate-within-10-sec-quick-setup-%EF%B8%8F">Detailed quick setup</a> can help to make a new fresh plugin within 10 sec
### How faster is Vite than the Webpack in development?
It needs milliseconds to update the dom, [Check very short video](https://www.youtube.com/watch?v=VA3G8ahoHLE)

![photo_2023-10-09 00 03 48](https://github.com/hasanuzzamanbe/wp-boilerplate-vue-with-vite/assets/43160844/805520f1-9c72-4259-b863-2dc5818df5bf)

# How to use? (details)
- Just clone/fork this repository on your wp-content/plugins directory
- run: `npm i`
You may check the package.json file for more info.

### Make Your Own plugin from boilerplate within 10 sec (Quick Setup 🧞‍♂️)

No worries! It needs just one command to create your own plugin with your Namespaces, Text Domains and Slugs.

Open the directory in the terminal (`cd wp-boilerplate-vue-with-vite`)

Call aladin 🧞‍♂️ by one command.
- run: `node aladin` and enter your Plugin Name in the command prompt.

Aladdin 🧞‍♂️ will make it within a blink.
Congratulations Everything is done 🥳

Just find the plugin name and activate it in your WordPress. Run development mode by `npm run dev`

Yes, you can update all those things later also.


<details>
  <summary>Manual setup(Not recommended): </summary>
  
  you have to replace all the NameSpaces and slugs. You may search and replace in plugin directory. by these keywords bellow.
  
  `PluginClassName` to yourClassName
  
  `pluginlowercase` to yourpluginslug,
  
  `PLUGIN_CONST` to YOUR_PLUGIN_SLUG,
  
  `PluginName`  to Your Plugin Name,
  
  `pluginslug` to your-plugin-slug
</details>


### production mode
You only need to run `npm run production` delete all excepts these files/directory.
- assets
- includes
- plugin-entry.php (plugin Entry file)

# Development Helping Docs:

### Enqueue Assets:
Now easy enqueue from version 1.0.6
No need to worry about the dev environment enqueue or Production level enqueue.
everything here can be managed by Vite dedicated class (`includes/Classes/Vite.php`)

Just Call like this

`Vite::enqueueScript($enqueueTag, $yourAdminSourcePath, $dependency = [], $version = null, $inFooter = false)`

Note: same as `wp_enqueue_script`

### Example use case:
<p style="color: green;">
No need to enqueue production manually again, It will enqueue from manifest on production. Just call `Vite::enqueueScript()`</p>

`Vite::enqueueScript('my-plugin-script-boot', 'admin/start.js', array('jquery'), PLUGIN_CONST_VERSION, true)`

`Vite::enqueueStyle('my-plugin-style', 'scss/my-style.js', array(), PLUGIN_CONST_VERSION, true)`




<details>
  <summary>NOT RECOMMENDED wp_enqueue_script (see why)</summary>

If you want to use `wp_enqueue_script` then you have to call both dev and production manually:

(Production and dev enqueue script should be like this)

```
if (defined('PLUGIN_CONST_DEVELOPMENT') && PLUGIN_CONST_DEVELOPMENT !== 'yes') {
    wp_enqueue_script('pluginlowercase-script-boot', PLUGIN_CONST_URL . 'assets/js/start.js', array('jquery'), PLUGIN_CONST_VERSION, false);
} else {
    wp_enqueue_script('pluginlowercase-script-boot', 'http://localhost:8880/' . 'src/admin/start.js', array('jquery'), PLUGIN_CONST_VERSION, true);
}
```
</details>


Read web documentation here <a href="https://wpminers.com/make-wordpress-plugin-using-vue-with-vite-build/"> Details Docs</a>

If you face any issues feel free to let me know. :)

<br/>

## Vue + Element UI auto command boilerplate
You can check another boilerplate plugin with vue js and element UI, You can create your own project using a simple command line on that project within 2 minutes.

Check it here: https://github.com/hasanuzzamanbe/wp-boilerplate-plugin-with-vuejs

### Other Setups You May Use
* WordPress Plugin with Vue 3, tailwind (Laravel Mix Build) [https://github.com/hasanuzzamanbe/wp-plugin-with-vue-tailwind]
* WordPress Plugin with Vue 2, Element UI (Laravel Mix Build) [https://github.com/hasanuzzamanbe/wp-boilerplate-plugin-with-vuejs]

### Active Example plugins:
Plugin using this boilerplate: https://wordpress.org/plugins/buy-me-coffee/
<br/>
Github Repo: https://github.com/hasanuzzamanbe/buy-me-coffee
