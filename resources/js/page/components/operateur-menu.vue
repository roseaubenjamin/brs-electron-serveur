<template>
	<a-layout-header class="header">
        <div class="logo" >
            <a href="/">
                <a-avatar  src="/img/logo.png" /> 
            </a>
        </div>
        <a-menu :style="{ lineHeight: '64px' , float : 'right' }" v-model="current"  mode="horizontal">
            <a-menu-item key="Dashboard" v-if="this.$route.params.id" >
                <router-link :to="{name:'Dashboard',params : { id : this.$route.params.id }}">
                    {{$lang('operateur menu home')}} 
                </router-link>
            </a-menu-item>
            <a-menu-item key="Team" v-if="this.$route.params.id" >
                <router-link :to="{name:'Team',params : { id : this.$route.params.id }}">
                    {{$lang('operateur menu team')}} 
                </router-link>
            </a-menu-item>
            <a-menu-item key="Note" v-if="this.$route.params.id" >
                <router-link :to="{name:'Note',params : { id : this.$route.params.id }}">
                    {{$lang('operateur menu note')}} 
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
            	current : [] , 
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
        mounted(){
        	this.current = [ this.$route.name ]
        }
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