import Vue from 'vue';
import vueRouter from 'vue-router';

Vue.use(vueRouter);

import Home from '../page/mobile/index';

let $application = document.getElementById('application') ; 

let router = new vueRouter({
	mode: 'history',
	base : $application.getAttribute('data-base'),
	routes : [
		{ name: 'home', path : '/:id', component : Home },
		{ path : '*', redirect : '/'},
	],
})

export default router ; 