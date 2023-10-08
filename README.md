# WP Plugin Vue Boilerplate (Vite Build)
This is a Customizable sample WordPress Plugin which is developed as a single page app on backend with Vue js and Tailwind custom build css. and you don't have to reload page all the time.

### How faster is Vite than the Webpack in development?
It needs milliseconds to update the dom, [Check very short video](https://www.youtube.com/watch?v=VA3G8ahoHLE)

### Admin Dashboard

<img src="./src/github-images/dashboard.png" />

Caption: <i>Dummy dashboard with custom build vue + tailwind setup (Vite realtime environment)</i><br/>

# How to use?

- Just clone/fork this repository
- Check the package.json file
- command: `npm i`
- command: `npm run watch` for development and for production: `npm run production`

On production you only need
- assets
- includes
- wp-plugin-with-vue-tailwind.php (plugin Entry file)

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

`Vite::enqueueScript('my-plugin-script-boot', 'admin/start.js', array('jquery'), WPM_VERSION, true)`

`Vite::enqueueStyle('my-plugin-style', 'scss/my-style.js', array(), WPM_VERSION, true)`




<details>
  <summary>NOT RECOMMENDED wp_enqueue_script (see why)</summary>

If you want to use `wp_enqueue_script` then you have to call both dev and production manually:

(Production and dev enqueue script should be like this)

```
if (defined('WPM_DEVELOPMENT') && WPM_DEVELOPMENT !== 'yes') {
    wp_enqueue_script('WPWVT-script-boot', WPM_URL . 'assets/js/start.js', array('jquery'), WPM_VERSION, false);
} else {
    wp_enqueue_script('WPWVT-script-boot', 'http://localhost:8880/' . 'src/admin/start.js', array('jquery'), WPM_VERSION, true);
}
```
</details>


If you face any issues feel free to let me know. :)

<br/>

## Vue + Element UI auto command boilerplate
You can check another boilerplate plugin with vue js and element UI, You can create your own project using a simple command line on that project within 2 minutes.

Check it here: https://github.com/hasanuzzamanbe/wp-boilerplate-plugin-with-vuejs

### Other Setups You May Use
* WordPress Plugin with Vue 3, tailwind (Laravel Mix Build) [https://github.com/hasanuzzamanbe/wp-plugin-with-vue-tailwind]
* WordPress Plugin with Vue 2, Element UI (Laravel Mix Build) [https://github.com/hasanuzzamanbe/wp-boilerplate-plugin-with-vuejs]

