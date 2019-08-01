<template>
	<a-drawer
		height="300"
	    placement="bottom"
	    :closable="false"
	    style="overflow: auto;"
	    @close="onClose"
	    :visible="visible">
	    <p><h3>les bots qui me son attacher</h3></p>
	    <a-row>
	    	<a-col>
	    		<a-list
				    class="demo-loadmore-list"
				    itemLayout="horizontal"
				    :dataSource="bot.attacheds">
				    <a-list-item slot="renderItem" slot-scope="item, index">
					    <a @click="message( item )" slot="actions">Message</a>
					    <a @click="dettache( item )" slot="actions">Dettache</a>
					    <a-list-item-meta>
					        <a slot="title" href="https://vue.ant.design/"><span @click="showHomeProfile( item )" >{{item.psoeudo}}</span></a>
					        <a-avatar slot="avatar" :src="item.avatar?item.avatar:'img/default.jpg'" />
					    </a-list-item-meta>
				    </a-list-item>
				</a-list>
	    	</a-col>
	    </a-row>
	</a-drawer>
</template>
<script>

    import bot from '../store/bot' ; 
	export default {
		data() {
		    return {
		      	visible: false,
                bot : bot.stade ,
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
		    async dettache( item ){
		    	await bot.dettachedBot( item.id )
		    },
            async message( item ){
                this.$router.push({ name: 'message', params: { bot: item.id } })  
            }
		},
		created(){
        	this.$parent.$on('bot-attached-show' , () => {
        		this.visible = true ;
        	})
        }
	}
</script>