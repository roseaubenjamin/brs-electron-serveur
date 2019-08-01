<template>
    <a-layout  style="width: 100% ; height: 100% ; " >    
        <app-client-menu></app-client-menu>
        <a-layout-content :style="{ background: '#fff', padding: '0px', margin: 0, minHeight: '280px' }" style="width: 100% ; height: 100% ; overflow: auto;" >
            <transition name="fade">
                <router-view></router-view> 
            </transition>
        </a-layout-content>
        <modal></modal>
    </a-layout> 
</template>
<script>

    import router from '../router/brs';
    import user from '../store/user';
    
    export default {
        props : ['user'], 
        router , 
        data(){ 
            return {}
        },
        computed : {},
        methods : {
            async init (){
                await user.addUser( this.user )
                await user.addStatusUser()
            }
        },
        mounted(){
            console.log( this.user )
            this.init() ; 
        },
    }

</script>
<style>

</style>