import Vue from 'vue';
import plugin from './core/plugin';
import components from './core/components';
import directives from './core/directives';
import Vuex from 'vuex';
//import App from './App.vue';
import 'ant-design-vue/dist/antd.css'
window.io = require('socket.io-client') ; 

Vue.config.productionTip = false ; 

let $application = document.getElementById('application') ; 
if ( $application ) {
	new Vue({
	    el: '#application',
	    plugin ,
	    components , 
	})
}
