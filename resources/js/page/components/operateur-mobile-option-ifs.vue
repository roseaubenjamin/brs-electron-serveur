<template>
    <a-tabs defaultActiveKey="1">
        <a-tab-pane :disabled="disabled" key="1">
            <span slot="tab">
                {{this.$lang('mobile option infusionsoft assigned')}}
            </span>
            <a-form :layout="'vertical'">
                <a-form-item :label="$lang('mobile option infusionsoft assigned name')">
                    <a-input
                        v-decorator="[
                            'name',
                            {rules: [{ required: true, message: 'Please input name!' }]} ]"
                        v-model="assinged.name" :placeholder="$lang('mobile option infusionsoft assigned name')" /> 
                </a-form-item>
                <a-form-item :label="$lang('mobile option infusionsoft assigned membre')">
                    <a-select v-model="assinged.user_id">
                        <a-select-option v-for="item in assingedMembre" :key="item.id" :value="item.id">
                            {{item.preferred_name}}
                        </a-select-option>
                    </a-select> 
                </a-form-item>
            </a-form>
            <a-button :loading="updateAssingedBtnloading" :disabled="!updateBtn" @click="updateAssigned" type="primary" block>{{$lang('mobile option infusionsoft assigned valider')}}</a-button>
            
            <a-table 
                :columns="fields"
                :dataSource="mobile.assigned.infusionsoft"
                :loading="loading"
                rowKey="id">
                <template slot="name" slot-scope="name , record">
                    <span>{{record.value.name}}</span>
                </template>
                <template slot="action" slot-scope="action , record">
                    <a-button type="danger"  @click="deleteAssigned(record)" >{{$lang('mobile option trello assinged delete')}}</a-button>
                </template>
            </a-table>
        </a-tab-pane>
    </a-tabs>
</template>
<script>
    
    import application from '../store/application' ; 
    import infusionsoft from '../store/infusionsoft' ; 
    import mobile from '../store/mobile' ; 
    import team from '../store/team' ; 
    import option from '../store/option' ; 
     
    //tableaux de selection des boards 
    let fields =  [
        {
            title: 'ID',
            dataIndex: 'checkbox',
            width: 30,
            scopedSlots: { customRender: 'checkbox' },
        },
        {
            title: 'TITLE',
            dataIndex: 'name',
            width: 150,
            scopedSlots: { customRender: 'name' },
        },
        {
            title: 'Action',
            dataIndex: 'action',
            width: 150,
            scopedSlots: { customRender: 'action' },
        }
    ];

    export default {

        props : ['data'], 

        data(){
            return {
                application  : application.state ,
                infusionsoft : infusionsoft.state , 
                mobile : mobile.state , 
                disabled : false , 
                showed : false , 
                loading : true ,
                fields ,
                //option de la selection de board  
                board : null , 
                //option de la l'ajout de l'assigne 
                assinged : { name : '' , user_id : '' } , 
                updateBtn : false ,
                updateAssingedBtnloading : false , 
                native_id : null , 
            }
        },

        computed : {
            assingedMembre : function (argument) {
                let prefrmedTeam = [ ...this.infusionsoft.teams , { id : 'note' , preferred_name : this.$lang('mobile option trello assinged note') } ]
                let $teams = prefrmedTeam.filter( (a) => 
                    ! this.mobile.assigned.infusionsoft.find( e => e.value.user_id == a.id ) 
                    //&& parseInt( this.native_id ) != parseInt( a.id ) 
                )
                return $teams ; 
            }
        },

        methods : {

            async loadAssigned () {
                await mobile.findAssigned( this.data.id , 'infusionsoft' ) ;
                this.loading = false ;  
                this.updateBtn = true ; 
            },

            async updateAssigned(){
                if ( this.assinged.name , this.assinged.user_id ) {
                    this.updateAssingedBtnloading = true ; 
                    await mobile.assigned( this.data.id , { name : this.assinged.name , user_id : this.assinged.user_id } , 'infusionsoft') ;
                    this.assinged.name = ''; 
                    this.assinged.user_id = ''; 
                    this.loadAssigned() ; 
                    this.updateAssingedBtnloading = false ; 
                }
            },

            async deleteAssigned( item ){
                let [ err , res ] = await mobile.deassigned( item.id ) ; 
                this.loadAssigned() ; 
            },

            async init(){
                let [err , info ] = await team.info( this.data.infusionsoft ) ;
                console.log( info );
                this.native_id = info.native_id ; 
                console.log( this.native_id )
                await application.itemApplication( this.data.infusionsoft ) ;
                await infusionsoft.allMembres( this.data.infusionsoft ) ; 
                this.loadAssigned() ; 
                this.showed = true ;
            }

        },
        created(){
            this.init() ; 
            this.on('ApplicationCreate',async () => {
                await application.addApplication( { name :this.name , type : this.type } )
                this.emit('closemodale') ; 
            });
        }
    }
</script>