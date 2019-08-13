<template>
    <div :style="{ marginLeft: 'auto', marginRight: 'auto', background: '#fff', padding: '24px', minHeight: '380px' , maxWidth : '992px' }">
        <a-row :gutter="12">
            <a-col>
                <div style="display: flex;">
                    <h3>{{$lang('admin note title')}}</h3>
                </div>
                <a-divider dashed />
                <a-table 
                    rowKey="email"
                    :columns="users_columns"
                    :dataSource="[]"
                    :loading="users_loading">
                </a-table>
            </a-col>
        </a-row>
    </div>

</template>
<script>

    import application from '../../store/application' ; 

    let users_columns =  [
        {
            title: 'title',
            dataIndex: 'Title',
            width: 150,
        },
        {
            title: 'Note',
            dataIndex: 'name',
            width: 150,
        }
    ]; 

    export default {
        props : [ ], 
        data(){
            return {
                users_columns , 
                users_loading : true ,
                application : application.state , 
            }
        },
        methods : {
            async init(){
                
            },
        },
        created(){
            this.init() ; 
        }
    }
</script>

<style>

</style>