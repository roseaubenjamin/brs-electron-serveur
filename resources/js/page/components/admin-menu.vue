<template>
	<a-layout-header class="header">
        <div class="logo" >
            <router-link :to="{name:'home'}">
                <a-avatar  src="/img/logo.png" /> 
            </router-link>
        </div>
        <a-menu :style="{ lineHeight: '64px' , float : 'right' }" v-model="current"  mode="horizontal">
            <a-menu-item key="Home">
                <router-link :to="{name:'Home'}">
                    {{$lang('admin menu home')}} 
                </router-link>
            </a-menu-item>
            <a-menu-item key="Team">
                <router-link :to="{name:'Team'}">
                    {{$lang('admin menu team')}} 
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
            	current : ['home'] , 
            }
        },

        watch:{
		    $route (to, from){
		        this.current = [ this.$route.name ]
		    }
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