import routes from './routes';
import { createWebHashHistory, createRouter } from 'vue-router'
import WPPluginVueTailwind from './Bits/WPPluginVueTailwind';

const router = createRouter({
    history: createWebHashHistory(),
    routes
});


const framework = new WPPluginVueTailwind();

framework.app.config.globalProperties.appVars = window.WPPluginVueTailwindAdmin;

window.WPPluginVueTailwindApp = framework.app.use(router).mount('#wpmvt_app');

router.afterEach((to, from) => {
    jQuery('.wpmvt_menu_item').removeClass('active');
    let active = to.meta.active;
    if(active) {
        jQuery('.wpmvt_main-menu-items').find('li[data-key='+active+']').addClass('active');
    }
});

//update nag remove from admin, You can remove if you want to show notice on admin
jQuery('.update-nag,.notice, #wpbody-content > .updated, #wpbody-content > .error').remove();
