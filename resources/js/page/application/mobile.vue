<template>
	<a-layout style="width: 100% ; height: 100% ; " >    
        <mobile-menu></mobile-menu>
        <a-layout-content :style="{ background: '#fff', padding: '24px', margin: 0, minHeight: '280px' }" style="width: 100% ; height: 100% ; overflow: auto;">
            <transition name="fade">
                <router-view></router-view> 
            </transition>
        </a-layout-content>
        <modal></modal>
    </a-layout> 
</template>
<script>
    import router from '../router/mobile';

    export default {
        router , 
        data(){ 
            return {
                page : window.location.pathname ,
            }
        },
        computed : {},
        methods : {},
        mounted(){

        },
    }
</script>
<style>
    
</style>