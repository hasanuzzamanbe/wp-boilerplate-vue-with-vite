import Admin from './Components/Admin.vue';
import Contact from './Components/Contact.vue';

export default [{
        path: '/',
        name: 'dashboard',
        component: Admin,
        meta: {
            active: 'dashboard'
        },
    },
    {
        path: '/contact',
        name: 'contact',
        component: Contact
    }
];