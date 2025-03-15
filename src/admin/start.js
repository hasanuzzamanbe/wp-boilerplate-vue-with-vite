import routes from './routes';
import { createWebHashHistory, createRouter } from 'vue-router';
import PluginClassName from './Bits/AppMixins';

const router = createRouter({
    history: createWebHashHistory(),
    routes
});

const framework = new PluginClassName();

framework.app.config.globalProperties.appVars = window.PluginClassNameAdmin;

window.PluginClassNameApp = framework.app.use(router).mount('#pluginlowercase_app');

router.afterEach((to, from) => {
    document.querySelectorAll('.pluginlowercase_menu_item').forEach(el => el.classList.remove('active'));
    
    let active = to.meta.active;
    if (active) {
        let activeElement = document.querySelector(`.pluginlowercase_main-menu-items li[data-key="${active}"]`);
        if (activeElement) {
            activeElement.classList.add('active');
        }
    }
});