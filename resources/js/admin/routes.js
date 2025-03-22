import Admin from './components/Admin.vue';
import Contact from './components/Contact.vue';

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