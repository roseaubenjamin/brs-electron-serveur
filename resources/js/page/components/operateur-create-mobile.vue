<template>
    <a-form :layout="'vertical'">   
        <a-form-item :label="$lang('admin create mobile name')">
            <a-input
                v-decorator="[
                    'name',
                    {rules: [{ required: true, message: 'Please input name!' }]} ]"
                v-model="name" :placeholder="$lang('admin create mobile')" /> 
        </a-form-item>
        <a-form-item :label="$lang('admin create mobile trello')">
            <a-select v-model="trello">
                <a-select-option v-for="item in application.trellos" :key="item.id" :value="item.id">
                    {{item.name}}
                </a-select-option>
            </a-select> 
        </a-form-item>
        <a-form-item :label="$lang('admin create mobile infusionsoft')">
            <a-select v-model="infusionsoft">
                <a-select-option v-for="item in application.infusionsofts" :key="item.id" :value="item.id">
                    {{item.name}}
                </a-select-option>
            </a-select> 
        </a-form-item>
    </a-form>
</template>
<script>
	
    import application from '../store/application' ; 
    import mobile from '../store/mobile' ; 
	
	export default {

		props : [], 

		data(){
            return {
                name : '' , 
                infusionsoft : null , 
                trello : null , 
                application  : application.state
            }
        },
        methods : {
            async init(){
                //initialisation des stores de l'applications  
                await application.allApplication() ;  
            },
        },
		created(){
            this.init()
			this.on('MobileCreate',async () => {
                if( this.name && (this.infusionsoft || this.trello) ){
                    await mobile.create( { trello: this.trello , infusionsoft:this.infusionsoft , name :this.name } )
                    this.trello = '' ; 
                    this.infusionsoft = '' ; 
                    this.name = '' ; 
                    this.emit('closemodale') ; 
                    this.emit('MobileCreateCloseModale') ; 
                }else this.emit('resetbtnmodale') ; 
			} , true );
		}
	}
</script>