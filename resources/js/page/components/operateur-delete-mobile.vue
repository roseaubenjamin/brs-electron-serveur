<template>

    <a-spin :spinning="!ready" tip="Loading...">
        <a-alert
            :description="$lang('operateur delete mobile scription') "
            type="error"
            showIcon>
            <div slot="message" v-if="name">
                {{$lang('operateur delete mobile alert')}} <strong>{{name}}</strong>
            </div>
        </a-alert>
    </a-spin>
    
</template>
<script>

    import mobile from '../store/mobile' ; 
	
	export default {

		props : ['data'], 

		data(){
            return {
                mobile : mobile.state , 
                name : '',
                ready : false ,
            }
        },

        computed: {
        
        },

        methods : {

            async init(){
                //application.item[data.id].name
                let [ err , data ] = await mobile.find( this.data.id ) ; 
                if( data ){
                    this.name = data.name ; 
                    this.ready = true ; 
                }
            }

        },  
		created(){

            this.init()
			this.on('MobileDestroy',async () => {
				let [ err , destroy ] = await mobile.destroyMobile( this.data.id ) ; 
                this.emit('MobileCreateCloseModale')
                this.emit('closemodale') ; 
			});

		}
	}
</script>
<style>
    .spin-content{
        border: 1px solid #91d5ff;
        background-color: #e6f7ff;
        padding: 30px;
    }
</style>