<template>
    <div :style="{ marginLeft: 'auto', marginRight: 'auto', background: '#fff', padding: '24px', minHeight: '380px' , maxWidth : '992px' }">
        <a-row :gutter="12">
            <a-col :span="8">
                <h1>DASHBOARD</h1>    
            </a-col>
        </a-row>
        <a-divider dashed />
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
                await application.itemApplication( this.$route.params.id )
                //vérifier si on est dans un compte trello et que le compte
                //trello n'a pas de URL 
                let app = this.application.item[ this.$route.params.id ] ;
                if ( app.type=='trello' && !app.url ) {
                    //écouter si on update le board de trello 
                    this.on('onboardupdate',(data) => {
                        if( data && data.url )
                            this.emit('closemodale') ; 
                    } , true ) 
                    return this.emit('modal',{
                        title : this.$lang('operateur trello update board title') , 
                        data : { app : null , trello : this.$route.params.id } , 
                        footer : true,
                        closable : false , 
                        component : 'trello-option-url' , 
                    });  
                }
            },
        },
        created(){
            this.init() ; 
        }
    }
</script>

<style>

</style>