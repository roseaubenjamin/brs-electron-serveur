<template>
	<a-drawer
		width=640
	    placement="right"
	    :closable="false"
	    @close="onClose"
	    :visible="visible">
	    <p>Personal</p>
	    <a-row>
	        
	    </a-row>
	</a-drawer>
</template>
<script>
	export default {
		data() {
		    return {
		      	visible: false,
		    }
		},
		components: {

		},
		methods: {
		    showDrawer() {
		      this.visible = true
		    },
		    onClose() {
		      this.visible = false
		    },
		},
		created(){
        	this.$parent.$on(' home-profile-view' , () => {
        		this.visible = true ;
        	})
        }
	}
</script>