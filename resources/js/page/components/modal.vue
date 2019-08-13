<template>
    <div>
    	<a-modal
		    :title="options.title"
		    :visible="visible"
		    @ok="handleOk"
		    :confirmLoading="confirmLoading"
		    :closable="closable"
		    :maskClosable="closable"
		    :destroyOnClose="true"
		    @cancel="handleCancel">
		    <div v-if="options.footer" slot="footer"></div>
	      	<component :data="data" v-if="component" v-bind:is="component"></component>
	    </a-modal>
    </div>
</template>
<script>
	
	export default {
		props : [], 
		data(){
            return {
            	options : {
            		footer : true , 
            	} , 
            	component : '' , 
			    visible: false,
			    confirmLoading: false,
			    data : {} , 
			    closable : true ,
            }
        },
        computed: {

        },
        methods : {

        	handleCancel(e) {
		      	console.log('Clicked cancel button');
		      	this.visible = false
		    },

        	handleOk(e) {
		      	this.confirmLoading = true;
		      	console.log( this.options.handleOk )
        		this.emit( this.options.handleOk ) ; 
		    },

        },

        beforeDestroy(){
			this.confirmLoading = false;
        },

        destroyed(){
			this.confirmLoading = false;
        },

		mounted(){

			this.on('modal',(options) => {
				this.visible = true ; 
				this.closable = options.closable!==undefined?options.closable:true;
				console.log( this.closable ) ; 
				this.options = Object.assign({} ,  { ...options } )
				console.log( this.options )
				if ( this.options.data ) {
					this.data = Object.assign({} , this.options.data )
				}
				this.component = this.options.component
			})

			this.on('closemodale',(options) => {
				this.confirmLoading = false;
				setTimeout(() => {
		        	this.visible = false;
		      	}, 300);
			})

			this.on('resetbtnmodale',(options) => {
				this.confirmLoading = false;
			})
			
		}
	}
</script>
<style>

</style>