<template>
    <a-layout>    
        <operateur-menu></operateur-menu>
        <a-layout-content :style="{ background: '#fff', padding: '0px', margin: 0, minHeight: '280px' }">
            <transition name="fade">
                <router-view></router-view> 
            </transition>
        </a-layout-content>
        <modal></modal>
    </a-layout> 
</template>
<script>
    
    import router from '../router/operateur';
    import user from '../store/user';

    export default {
        props : ['user'], 
        router , 
        data(){ 
            return {
                
            }
        },
        methods : {
            async init (){
                //user.addUser( this.user );
            }
        },
        mounted(){
            this.init() ; 
        },
    }
</script>