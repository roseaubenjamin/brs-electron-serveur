import Vue from 'vue';
import vueRouter from 'vue-router';

Vue.use(vueRouter);

import Home from '../page/admin/index';
import Team from '../page/operateur/team';
import Application from '../page/operateur/index';
import Note from '../page/operateur/note';

let $application = document.getElementById('application') ; 

console.log( $application.getAttribute('data-base') ) ; 

let router = new vueRouter({
	mode: 'history',
	base : $application.getAttribute('data-base'),
	routes : [
		{ name: 'Home', path : '/', component : Home },
		{ path : '*', redirect : '/'},
	],
})

export default router ; 