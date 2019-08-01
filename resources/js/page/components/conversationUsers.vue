<template>
	<div>
        <div style="padding: 4px; background-color: white; position: fixed; width: 100%; z-index: 2000 ; max-width: 280px; min-width: 280px;">
            <a-input placeholder="Basic usage"/>
        </div>
        <a-menu style="padding-top: 42px; display: block; min-height: 100%; " mode="inline">
            <a-menu-item 
                v-for="item in conversationUsers" 
                style="display: flex; flex-flow: row wrap;" 
                :key="item.id" 
                @click="to(item)">
                <a-badge dot status="success"> 
                    <a-avatar style="width: 40px; height: 40px;" src="https://zos.alipayobjects.com/rmsportal/ODTLcjxAfvqbxHnVXCYX.png" />
                </a-badge>
                <div style="max-width: calc( 100% - 40px ); padding-left: 6px; margin-top: 7px; ">
                    <h4 class="ant-list-item-meta-title">
                        <a>{{item.psoeudo}}</a>
                    </h4>
                </div>        
            </a-menu-item>
        </a-menu>
    </div>
</template>
<script>

    import conversation from '../store/conversation';

    export default {
		props : ['bot'], 
		data(){
            return {
                conversationUsers : [] , 
            }
        },
        watch : {
            
        },
        computed: {

        },
        methods : {
        	conversationAdd : function( fata ){
                console.log( fata )
                this.conversationUsers = [  ] ; 
                for( let item in fata ){
                    this.conversationUsers.push( fata[item] ) ; 
                }
                console.log( this.conversationUsers ) ;
            },
            init : async function () {
                await conversation.findUsers( (this.bot ? this.bot : '' ) ) ; 
            },
            to : async function ( item ) {
                this.$emit('to' , item.id )
            }
        },
		mounted(){
            this.init() ;
			conversation.$on('new-users', (data) => {
                this.conversationAdd( data )
                console.log( data )
            })
		}
	}
</script>
<style>

</style>