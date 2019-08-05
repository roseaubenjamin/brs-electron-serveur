<template>
    <div :style="{ marginLeft: 'auto', marginRight: 'auto', background: '#fff', padding: '24px', minHeight: '380px' , maxWidth : '992px' }">
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
                
                users_columns , 
                //loading 
                users_loading : true ,
            }
        },

        computed: {
        
        },
        methods : { 

            async finduser() {
                console.log( this.application.item )
                let application = this.application.item[ this.$route.params.id ] ; 
                if ( !application || !application.id ) 
                    return ; 
                this.urladdteams = window.APP_URL + '/team/'+ application.unique ; 
                console.log( this.urladdteams ) ; 
            },

            async init(){
                await application.itemApplication( this.$route.params.id ) 
                await team.all( this.$route.params.id ) ; 
                this.users_loading = false ;
                this.finduser() ; 
            },
        },
        created(){ 
            this.init() ; 
        }
    }
</script>