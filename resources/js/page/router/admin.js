import Vue from 'vue';
import vueRouter from 'vue-router';

Vue.use(vueRouter);

import Home from '../page/mobile/index';
//import Option from '../page/mobile/option';

let $application = document.getElementById('application') ; 

let router = new vueRouter({
	mode: 'history',
	base : $application.getAttribute('data-base'),
	routes : [
		{ name: 'home', path : '/', component : Home },
		//{ name: 'Option', path : '/option', component : Option },
		{ path : '*', redirect : '/'},
	],
})

export default router ; 