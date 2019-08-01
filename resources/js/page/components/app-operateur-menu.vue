<template>
	<a-layout-header class="header">
        <div class="logo" >
            <router-link :to="{name:'home'}">
                <a-avatar  src="/img/logo.png" /> 
            </router-link>
        </div>
        <a-menu :style="{ lineHeight: '64px' , float : 'right' }" v-model="current"  mode="horizontal">
            <a-menu-item key="dashboard">
                <router-link :to="{name:'dashboard'}">
                    {{$lang('app menu dashboard')}} 
                </router-link>
            </a-menu-item>
            <a-menu-item key="bot">
                <router-link :to="{name:'bot'}">
                    {{$lang('app menu bot')}} 
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
            	current : ['dashboard'] , 
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