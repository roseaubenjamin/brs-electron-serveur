<template>
    <div :style="{ marginLeft: 'auto', marginRight: 'auto', background: '#fff', padding: '24px', minHeight: '380px' , maxWidth : '992px' }">
        <div v-if="notify" style="position: fixed ; z-index: 1000; width: 50%; max-width: 500px ; left: 50% ; transform: translateX(-50%);">
            <a-alert
                :message="notifyMessage"
                :type="notifyType">
                <div slot="description">
                    <a-list
                        itemLayout="horizontal"
                        :dataSource="appContactSearch">
                        <a-list-item slot="renderItem" slot-scope="item, index">
                            <a-list-item-meta>
                                <span slot="title">
                                    <span style="line-height: 36px; font-size: 1.1rem;">
                                        {{item.title}} 
                                        <a-spin v-if="item.start" >
                                            <a-icon slot="indicator" type="loading" style="font-size: 24px" spin />
                                        </a-spin>
                                    </span>
                                </span>
                                <div slot="avatar"><a-progress :width="40" type="circle" :percent="item.progress" /></div>
                            </a-list-item-meta>
                        </a-list-item>
                    </a-list>
                </div>
            </a-alert>
        </div>
        <div style="display: flex;">
            <h1>{{$lang('operateur index title')}}</h1>
            <h3 style="flex: 1;"><a @click.stop.prevent="createMobile" style="float: right; line-height: 2rem;"><a-icon type="mobile" />{{$lang('operateur index mobile')}}</a></h3>
        </div>
        <a-divider dashed />
        <a-row :gutter="16">
            <a-col style="margin-bottom: 1rem;" :span="8" v-for="item in mobile.lists" :key="item.id">
                <a-card>
                    <template class="ant-card-actions" slot="actions">

                        <a-popover v-if="item.trello&&item.trello.id" placement="bottom">
                            <template slot="content">
                                <p>{{item.trello.name}}</p>
                            </template>
                            <a-avatar class="gryforover" shape="square" :size="16" src="/img/trello/favicon-32x32.png" @click.stop.prevent="trello(item.id , item.trello.id)" type="dashboard" />
                        </a-popover>

                        <a-popover v-if="item.infusionsoft&&item.infusionsoft.id" placement="bottom">
                            <template slot="content">
                                <p>{{item.infusionsoft.name}}</p>
                            </template>
                            <a-avatar class="gryforover" shape="square" :size="16" src="/img/infusionsoft/favicon-32x32.png" @click.stop.prevent="infusionsoft(item.id , item.infusionsoft.id)" type="user" />
                        </a-popover>
                        <a-icon theme="twoTone" twoToneColor="#ff4c50" @click="destroyMobile(item.id)" type="delete" />
                    </template>
                    <a-card-meta
                        :description="item.user_role">
                        <span slot="title">
                            <a-avatar src="/img/microphone.active.svg" /> 
                            <a style="color: inherit;" :href="'/mobile/'+item.id">
                                <span >{{item.name}}</span>
                            </a>
                        </span>
                    </a-card-meta>
                </a-card>
            </a-col>
        </a-row>
        <a-divider />
        <a-row :gutter="16">
            <a-col style="margin-bottom: 1rem;" :span="8" v-for="item in application.applications" :key="item.id">
                <a-card>
                    <template class="ant-card-actions" slot="actions">
                        <a-icon @click="dashboard(item.id)" type="dashboard" />
                        <a-icon @click="user(item.id)" type="user" />
                        <a-icon @click="destroy(item.id)" type="delete" theme="twoTone" twoToneColor="#ff4c50" />
                    </template>
                    <a-card-meta
                        :title="item.name"
                        :description="item.user_role">
                        <a-avatar v-if="item.type=='trello'" slot="avatar" src="/img/trello.png" />
                        <a-avatar v-if="item.type=='infusionsoft'" slot="avatar" src="/img/infusionsoft.png" />
                    </a-card-meta>
                </a-card>
            </a-col>
        </a-row>
        <div style="margin-top: 1.5rem;">
            <a-button type="primary" icon="plus-circle" @click="newapplication" block >{{this.$lang('operateur create button')}}</a-button>
        </div>
    </div>
</template>
<script>
    
    import application from '../../store/application' ; 
    import mobile from '../../store/mobile' ; 
    import infusionsoft from '../../store/infusionsoft' ; 

    export default {
        props : [ ], 
        data(){
            return {
                application : application.state , 
                mobile : mobile.state , 
                //notification 
                notify : false ,
                notifyType : 'info' , 
                notifyMessage : '' , 
                //
                appContactSearch : [] , 
            }
        },
        computed: {
        
        },
        methods : {

            createMobile(){
                this.emit('modal',{
                    title : this.$lang('operateur create mobile title') , 
                    component : 'create-mobile' , 
                    handleOk : 'MobileCreate'
                })
            },

            newapplication() { 
                
                this.emit('modal',{
                    title : this.$lang('operateur create application title') , 
                    component : 'create-application' , 
                    handleOk : 'ApplicationCreate'
                })

            },

            //récupération de l'intégralité des contacts 
            async allContact( data ){

                this.appContactSearch = [] ; 

                for ( let item of data ){
                    await application.itemApplication( item ) ;  
                }

                for ( let item of data ){
                    this.appContactSearch.push({ title : this.$lang('operateur application charge contact') +' '+ this.application.item[ item ].name , progress : 0 , start : false })
                }
                
                this.notify = true ; 
                let index = 0 ;
                for ( let item of data ){
                    console.log( this.appContactSearch[index] , this.appContactSearch )
                    this.appContactSearch[index].start = true ;
                    await infusionsoft.fetchContact( item , ( res ) => {
                        console.log( res )
                        if( res && res.value && res.value.percenty ){
                            let progress = parseFloat( res.value.percenty ) ; 
                            console.log( progress )
                            this.appContactSearch[index].progress = progress ; 
                        }
                    })
                    this.appContactSearch[index].start = false ;
                    index++ ; 
                }
                this.notify = false ; 
                this.appContactSearch = [] ; 

            },

            async init(){
                //initialisation des stores de l'applications  
                await application.allApplication() ;  
                await mobile.allMobile() ; 
                //récupération de tout vos application IFS qui n'on pas de conctact, ou 
                //tout simplement qui on de nouvelle contact 
                let [ err , data ] = await infusionsoft.findContactSynhronisation() ;
                //initialisation de tout les applications Ifusionsoft qui n'on pas de contact 
                this.allContact( data ) ;
            },

            //redirection a la page dashbord 
            async dashboard( page ){
                this.$router.push({ name: 'Note', params: { id: page } }) 
            },

            async user( page ){
                this.$router.push({ name: 'Team', params: { id: page } }) 
            },

            async destroy( page ){
                this.emit('modal',{
                    title : this.$lang('operateur delete application title') , 
                    component : 'delete-application' , 
                    data : { id : page } , 
                    handleOk : 'ApplicationDestroy'
                })
            },

            async destroyMobile( page ){
                this.emit('modal',{
                    title : this.$lang('operateur delete mobile title') , 
                    component : 'delete-mobile' , 
                    data : { id : page } , 
                    handleOk : 'MobileDestroy'
                })
            },

            async trello( mobile , page ){
                this.emit('modal',{
                    title : this.$lang('operateur option mobile trello title') , 
                    component : 'mobile-option-trello' , 
                    data : { trello : page , id : mobile } , 
                    footer : true,
                })
            },

            async infusionsoft( mobile , page ){
                this.emit('modal',{
                    title : this.$lang('operateur option mobile infusionsoft title') , 
                    component : 'mobile-option-ifs' , 
                    data : { id : mobile , infusionsoft : page } , 
                    footer : true,
                })
            }

        },
        mounted(){
            
            this.on('MobileCreateCloseModale',async () => {
                await mobile.allMobile() ; 
            })

            this.on('ApplicationCreateCloseModale',async () => {
                await application.allApplication() ;  ; 
                await mobile.allMobile() ; 
            })

            this.init()
        
        }
    }
</script>

<style>
    .homepage{
        position: fixed;
        top: 0;left: 0;right: 0;bottom: 0;
        z-index: 1025;
        background-color: #FFF ;
        padding-top: 3rem;
    }

    .gryforover img{
        filter: grayscale(100%) ; 
    }

    .gryforover:hover img {
        filter: grayscale(0%) ; 
    }

    .ant-popover-inner-content {
        padding: 0px 16px;
        color: rgba(0, 0, 0, 0.65);
    }

</style>