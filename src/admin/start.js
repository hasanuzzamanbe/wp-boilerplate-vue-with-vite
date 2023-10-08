import routes from './routes';
import { createWebHashHistory, createRouter } from 'vue-router'
import PluginClassName from './Bits/AppMixins';

const router = createRouter({
    history: createWebHashHistory(),
    routes
});


const framework = new PluginClassName();

framework.app.config.globalProperties.appVars = window.PluginClassNameAdmin;

window.PluginClassNameApp = framework.app.use(router).mount('#pluginlowercase_app');

router.afterEach((to, from) => {
    jQuery('.pluginlowercase_menu_item').removeClass('active');
    let active = to.meta.active;
    if(active) {
        jQuery('.pluginlowercase_main-menu-items').find('li[data-key='+active+']').addClass('active');
    }
});

//update nag remove from admin, You can remove if you want to show notice on admin
jQuery('.update-nag,.notice, #wpbody-content > .updated, #wpbody-content > .error').remove();
