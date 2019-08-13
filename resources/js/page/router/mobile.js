import Vue from 'vue';
import vueRouter from 'vue-router';

Vue.use(vueRouter);

import Home from '../page/mobile/index';
import Read from '../page/mobile/read';

let $application = document.getElementById('application') ; 


let router = new vueRouter({
	mode: 'history',
	base : $application.getAttribute('data-base'),
	routes : [
		{ name: 'Copy', path : '/:unique/copy', component : Home },
		{ name: 'Duplicate', path : '/:unique/duplicate', component : Home },
		{ name: 'UpdateSingle', path : '/:id/update', component : Home },
		{ name: 'Update', path : '/:id/:unique/update', component : Home },
		//{ name: 'Read', path : '/:id/:unique/update', component : Read },
		{ name: 'Home', path : '/:id', component :  $application.getAttribute('data-base') == 'read' ? Read : Home },
		{ path : '*', redirect : '/'},
	],
})


export default router ; 