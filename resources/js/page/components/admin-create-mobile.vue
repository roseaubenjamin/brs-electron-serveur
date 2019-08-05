<template>
    <a-form :layout="'vertical'">   
        <a-form-item :label="$lang('admin create mobile')">
            <a-input
                v-decorator="[
                    'name',
                    {rules: [{ required: true, message: 'Please input name!' }]} ]"
                v-model="name" :placeholder="$lang('admin create mobile')" /> 
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
                application  : application.stade
            }
        },
        methods : {
            
        },
		created(){
			this.on('MobileCreate',async () => {
                alert('@ERROR:AZA ADINO FA RA CHARGENA MARO ITO MODALE ITO DIA TSY OVAFAFA LE ON')
                await mobile.create( { name :this.name , type : this.type } )
                this.emit('closemodale') ; 
			});
		}
	}
</script>