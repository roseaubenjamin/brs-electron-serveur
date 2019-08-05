<template>
    <div :style="{ marginLeft: 'auto', marginRight: 'auto', background: '#fff', padding: '24px', minHeight: '380px' , maxWidth : '992px' }">
        <div>
            <a-alert
                :message="$lang('operateur team success title')"
                :description="$lang('operateur team success title')"
                type="success"
              showIcon
            />
        </div>
        <a-divider dashed />
        <a-row :gutter="12">
            <a-col :span="8">
                <a-button @click="redirecthome" type="primary">{{$lang('operateur team success commencer')}}</a-button>    
            </a-col>
        </a-row>
    </div>
</template>
<script>
    import application from '../../store/application' ; 
    export default {
        props : [ ], 
        data(){
            return {
                application : application.state , 
            }
        },
        methods : {
            async init(){
                await application.itemApplicationByUnique( this.$route.params.id ) 
            },
            async redirecthome(){
                this.$router.push({ name: 'Home' }) ;
            }
        },
        created(){
            this.init() ; 
        }
    }
</script>

<style>

</style>