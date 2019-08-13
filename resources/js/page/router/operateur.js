import Vue from 'vue';
import vueRouter from 'vue-router';

Vue.use(vueRouter);

import Home from '../page/operateur/index';
import Dashboard from '../page/operateur/dashboard';
import Team from '../page/operateur/team';
import Note from '../page/operateur/note';

let $application = document.getElementById('application') ; 

console.log( $application.getAttribute('data-base') ) ; 

let router = new vueRouter({
	mode: 'history',
	base : $application.getAttribute('data-base'),
	routes : [
		{ name: 'Home', path : '/', component : Home },
		{ name: 'Dashboard', path : '/:id', component : Dashboard },
		{ name: 'Team', path : '/team/:id', component : Team },
		{ name: 'Note', path : '/note/:id', component : Note },
		{ path : '*', redirect : '/'},
	],
})

export default router ; 