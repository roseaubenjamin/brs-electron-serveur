<template>
    <a-spin :spinning="!ready" tip="Loading...">
        <a-alert
            :description="$lang('operateur delete application scription')"
            type="error"
            showIcon>
            <div slot="message" v-if="name">
                {{$lang('operateur delete application alert')}} <strong>{{name}}</strong>
            </div>
        </a-alert>
    </a-spin>
</template>
<script>

    import application from '../store/application' ; 
	
	export default {

		props : ['data'], 

		data(){
            return {
                application : application.state , 
                ready : false , 
                name : '' , 
            }
        },

        computed: {
        
        },

        methods : {

            async init(){
                await application.itemApplication( this.data.id ) ; 
                this.ready = true ; 
                this.name = this.application.item[this.data.id].name
            }

        },  
		created(){

            this.init()
			this.on('ApplicationDestroy',async () => {
				let [ err , destroy ] = await application.destroyApplication( this.data.id ) ; 
                this.emit('ApplicationCreateCloseModale') ; 
                this.emit('closemodale') ; 
			});

		}
	}
</script>
<style>

</style>