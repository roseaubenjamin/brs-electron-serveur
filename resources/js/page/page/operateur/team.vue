<template>
    <div :style="{ marginLeft: 'auto', marginRight: 'auto', background: '#fff', padding: '24px', minHeight: '380px' , maxWidth : '992px' }">
        <div v-if="newteam && application.item[ app_id_item ] " >
            <a-alert
                :message="$lang('operateur team success title')"
                :description="$lang( 'operateur team success desk' , application.item[ app_id_item ] )"
                type="success"
              showIcon
            />
            <a-divider dashed />
        </div>
        <a-row :gutter="12">
            <a-col>
                <div style="display: flex;">
                    <h3>{{$lang('admin team title')}}</h3>
                    <div style="flex: 1;">
                        <a-popover placement="bottom">
                            <template slot="content">
                                <p>{{urladdteams}}</p>
                            </template>
                            <a-button style="float: right;" variant="primary" >{{$lang('admin team urladdteam')}}</a-button>
                        </a-popover>
                    </div>
                </div>
                <a-divider dashed />
                <a-table 
                    rowKey="email"
                    :columns="users_columns"
                    :dataSource="team.lists"
                    :loading="users_loading">
                </a-table>
            </a-col>
        </a-row>
    </div>

</template>
<script>

    import application from '../../store/application' ; 
    import team from '../../store/team' ; 

    let users_columns =  [
        {
            title: 'Email',
            dataIndex: 'email',
            width: 150,
        },
        {
            title: 'Name',
            dataIndex: 'name',
            width: 150,
        },
        {
            title: 'role',
            dataIndex: 'role',
            width: 150,
        }
    ]; 

    export default {
        props : [ ], 
        data(){
            return {
                application : application.state , 
                team : team.state , 
                urladdteams : '' ,  
                newteam : window.newteam , 
                users_columns , 
                //loading 
                users_loading : true ,
                app_id_item : this.$route.params.id
            }
        },

        computed: {
        
        },
        methods : { 

            async redirecthome(){
                this.$router.push({ name: 'Home' }) ;
            },
            
            async finduser() {
                console.log( this.application.item )
                let application = this.application.item[ this.app_id_item ] ; 
                if ( !application || !application.id ) 
                    return ; 
                this.urladdteams = window.APP_URL + '/team/'+ application.unique ; 
                console.log( this.urladdteams ) ; 
            },

            async initAfterTrelloCheck(){
                await team.all( this.app_id_item ) ; 
                this.users_loading = false ;
                this.finduser() ; 
            },

            async init(){
                if( this.newteam ){
                    let [ err , data ] = await application.itemApplicationByUnique( this.app_id_item ) 
                    if ( !err ) 
                        this.app_id_item = data.id
                    console.log( 'this.app_id_itemthis.app_id_itemthis.app_id_item' , this.app_id_item )
                }
                else
                    await application.itemApplication( this.app_id_item ) 
                let app = this.application.item[ this.app_id_item ] ;
                console.log( app ) ;
                if ( app.type=='trello' && !app.url ) {
                    //écouter si on update le board de trello 
                    this.on('onboardupdate',(data) => {
                        if( data && data.url )
                            this.emit('closemodale') ; 
                        this.initAfterTrelloCheck() ; 
                    } , true ) 
                    return this.emit('modal',{
                        title : this.$lang('operateur trello update board title') , 
                        data : { app : null , trello : this.app_id_item } , 
                        footer : true,
                        closable : false , 
                        component : 'trello-option-url' , 
                    });     
                }
                else this.initAfterTrelloCheck() ; 
            },
        },
        created(){ 
            this.init() ; 
        }
    }
</script>