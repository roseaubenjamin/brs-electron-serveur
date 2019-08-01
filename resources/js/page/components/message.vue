<template>
	<div class="message_body" :style="{ padding: '24px', background: '#fff' }" style=" height : calc( 100vh - 60px ) ; overflow: auto; ">
        <a-list
            v-if="conversation.users && conversation.users[this.to]"
            class="demo-loadmore-list"
            :loading="loading"
            :dataSource="conversation.users[this.to].messages">
            <div v-if="showMore" class="message_body_loading-container">
                <a-spin />
            </div>
            <a-list-item slot="renderItem" slot-scope="item, index">
                <a-comment>
                    <a slot="author">Han Solo</a>
                    <a-avatar
                        src="https://zos.alipayobjects.com/rmsportal/ODTLcjxAfvqbxHnVXCYX.png"
                        alt="Han Solo"
                        slot="avatar"/>
                    <p slot="content">{{item.content}} {{item.id}}</p>
                    <a-tooltip slot="datetime" :title="moment().format('YYYY-MM-DD HH:mm:ss')">
                      <span>{{moment().fromNow()}}</span>
                    </a-tooltip>
                </a-comment>
                <!--<a slot="actions">edit</a>  v-if="loading && !busy" -->
            </a-list-item>
            <hr style="display: block; height: 135px; border: none; background: none;">
        </a-list>
        <div v-show="style.showed" class="editor" :style="{ width: this.style.width , left : this.style.left , right : this.style.right }" style="position: fixed; bottom: 10px ; background-color: rgb(230, 230, 230) ; border-top: 2px solid #888888 ; padding: 0rem 1rem;">
            <a-comment>
                <a-avatar
                    slot="avatar"
                    src="https://zos.alipayobjects.com/rmsportal/ODTLcjxAfvqbxHnVXCYX.png"/>
                <div slot="content">
                    <div style="display: flex;">
                        <div style="flex: 1;" >
                            <a-textarea :rows="3" @keyup.enter.native="addMessage" v-model="message"></a-textarea>
                        </div>
                        <div>
                            <a-button
                                @click="addMessage"
                                htmlType="submit"
                                :loading="submitting"
                                type="primary">
                                Add Comment
                            </a-button>
                        </div>
                    </div>
                </div>
            </a-comment>
        </div>
    </div>
</template>

<script>

    import moment from 'moment'
    import user from '../store/user';
    import conversation from '../store/conversation';

	export default {
		props : [ 'from', 'to' ,'role' ], 
		data(){
            return {
                moment , 
                message: '',
                submitting : false, 
                user : user.stade , 
                conversation : conversation.stade , 
            	current : ['application'] ,
                paged : 1 ,
                loading : false ,
                showMore : false , 
                style : {
                    width : '600px' , 
                    left : '0px' , 
                    right : '0px' ,
                    showed : false ,
                },
                count : 0 ,
            }
        },

        watch:{
            conversation : function(){
                if ( this.conversation.users ) {
                    this.conversation.users.find( e => e.id == to );
                }
            }
		},  

        methods : {

            scrollMessage : async function(){
                await this.$nextTick(()=>{
                    this.$el.scrollTop = this.$el.scrollHeight ;  
                })
            },

            addMessage : async function(){
                this.submitting = true ; 
                let [ err , msg ] = await conversation.createMessages( this.message , this.role , this.to , this.from ) ; 
                console.log( err )
                this.message = '' ; 
                this.submitting = false ; 
                this.scrollMessage() ; 
            },

            //ici on scroll le message 
            scroll : async  function(){
                if ( this.$el.scrollTop == 0 && this.showMore == false && this.conversation.users[this.to].messages.length < this.count ) {
                    //conversation.users[this.to].messages
                    console.log( 'NEXT' )
                    let $scrollHeight = this.$el.scrollHeight ; 
                    this.showMore = true ; 
                    await conversation.findMessages( this.role , this.to , this.from , this.conversation.users[this.to].messages[0].created_at ) ; 
                    await this.$nextTick(()=>{
                        this.$el.scrollTop = this.$el.scrollHeight - $scrollHeight ; 
                    })
                    this.showMore = false ;
                }
            },

            sizeArea : async function(){
                let { width , left , right } = this.$el.getBoundingClientRect() ;
                this.style.width = ( width - 40 ) +'px'; 
                this.style.left = ( left + 20 ) +'px'; 
                this.style.right = ( right + 20 ) +'px'; 
                console.log( this.style.width )
                this.style.showed = true ; 
            },

            init : async function () {
                //récupération de l'ensemble des messages
                let resFindMessage = await conversation.findMessages( this.role , this.to , this.from ) ; 
                if ( resFindMessage && resFindMessage.count ) 
                    this.count = resFindMessage.count ; 
                this.paged ++ ; 
                await this.scrollMessage() ; 
                if ( this.conversation.users[this.to] &&  this.conversation.users[this.to].messages && this.conversation.users[this.to].messages.length < this.count  ) {
                    this.$el.addEventListener('scroll' , this.scroll )
                }
                window.addEventListener('resize', (event) => {
                    this.sizeArea() ; 
                });
                this.sizeArea() ; 
            }, 
        },
        
        destroyed(){
            this.$el.removeEventListener('scroll' , this.scroll )
            window.removeEventListener('resize' , this.sizeArea )
        },

        mounted(){
            this.init() ; 
        }

	}
</script>
<style>
	.message_body {
	    border: 1px solid #e8e8e8;
        border-radius: 4px;
        overflow: auto;
        padding: 8px 24px;
        height: 300px;
	}
    .message_body_loading-container{
        position: absolute;
        top: 40px;
        width: 100%;
        text-align: center;
    }
</style>