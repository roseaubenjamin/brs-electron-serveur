import Vue from 'vue';
import vueRouter from 'vue-router';

Vue.use(vueRouter);

import Home from '../page/operateur/index';
import Team from '../page/operateur/team';

let $application = document.getElementById('application') ; 

console.log( $application.getAttribute('data-base') ) ; 

let router = new vueRouter({
	mode: 'history',
	base : $application.getAttribute('data-base'),
	routes : [
		{ name: 'Home', path : '/', component : Home },
		{ name: 'Team', path : '/team/:id', component : Team },
		{ path : '*', redirect : '/'},
	],
})

export default router ; 