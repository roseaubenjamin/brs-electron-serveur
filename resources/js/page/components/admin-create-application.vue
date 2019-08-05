<template>
    <a-form :layout="'vertical'">
        <a-form-item>
            <a-radio-group
                style="width: 100%;"
                default-value="infusionsoft" 
                @change="handleFormApplicationChange">
                <a-radio-button style="width: 50%;" value="trello">
                    Trello
                </a-radio-button>
                <a-radio-button style="width: 50%;" value="infusionsoft">
                    Infusionsoft
                </a-radio-button>
            </a-radio-group>
        </a-form-item>    
        <a-form-item v-if="type=='infusionsoft'" :label="$lang('admin create application ifn')">
            <a-input
                v-decorator="[
                    'name',
                    {rules: [{ required: true, message: 'Please input name!' }]} ]"
                v-model="name" :placeholder="$lang('admin create application ifn')" /> 
        </a-form-item>
        <a-form-item v-if="type=='trello'" :label="$lang('admin create application trello')">
            <a-input
                v-decorator="[
                    'name',
                    {rules: [{ required: true, message: 'Please input name!' }]}]"
                v-model="name" :placeholder="$lang('admin create application trello')" />   
        </a-form-item>
    </a-form>
</template>
<script>
	
    import application from '../store/application' ; 
	
	export default {

		props : [], 

		data(){
            return {
                name : '' , 
                type : 'infusionsoft' , 
                application  : application.stade

            }
        },
        methods : {
            handleFormApplicationChange  (e) {
                this.type = e.target.value;
            },
        },
		created(){
			this.on('ApplicationCreate',async () => {
                await application.addApplication( { name :this.name , type : this.type } )
                this.emit('closemodale') ; 
			});
		}
	}
</script>