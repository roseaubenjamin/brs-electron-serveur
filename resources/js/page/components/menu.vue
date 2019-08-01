<template>
	<a-layout-header class="header">
        <div class="logo" >
            <router-link :to="{name:'external'}">
                <a-avatar  src="/img/logo.png" /> 
            </router-link>
        </div>
        <a-menu :style="{ lineHeight: '64px' , float : 'right' }" v-model="current"  mode="horizontal">
            <a-menu-item key="home">
                <router-link :to="{name:'home'}">
                    {{$lang('app menu home')}} 
                </router-link>
            </a-menu-item>
            <a-menu-item key="credi">
                <router-link :to="{name:'credi'}">
                    {{$lang('app menu credi')}} 
                </router-link>
            </a-menu-item>
            <a-menu-item key="message">
                <router-link :to="{name:'message'}">
                    {{$lang('app menu message')}} 
                </router-link>
            </a-menu-item>
        </a-menu>
    </a-layout-header>
</template>

<script>

	export default {
		props : [], 
		data(){
            return {
            	current : ['application'] , 
            }
        },

        watch:{
		    $route (to, from){
		        this.current = [ this.$route.name ]
		    }
		},  

        computed: {

        },
        methods : {

        },
	}
</script>
<style>
	.logo {
	    width: 120px;
	    height: 31px;
	    float: left;
	    margin: 0px;
	}
	
	/*@Ecrase :*/
	.ant-layout-header {
	    background: #f0f2f5;
	}
	.ant-menu-horizontal{
		background-color: #f0f2f5
	}

</style>