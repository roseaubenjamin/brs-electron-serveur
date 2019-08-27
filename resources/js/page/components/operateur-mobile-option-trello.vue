<template>
    <a-tabs @change="changeTabms" defaultActiveKey="1">
        <a-tab-pane v-show="showed" key="1">
            <span slot="tab">
                {{this.$lang('mobile option trello board')}}
            </span>
            <trello-option-url v-if="application.item[data.trello]" :data="{trello:data.trello,app:data.id}" ></trello-option-url>
        </a-tab-pane>
        <a-tab-pane v-show="showed" :disabled="disabled" key="2">
            <span slot="tab">
                {{this.$lang('mobile option trello assinged')}}
            </span>
            <a-form :layout="'vertical'">
                <a-form-item :label="$lang('mobile option trello assinged name')">
                    <a-input
                        v-decorator="[
                            'name',
                            {rules: [{ required: true, message: 'Please input name!' }]} ]"
                        v-model="assinged.name" :placeholder="$lang('mobile option trello assinged name')" /> 
                </a-form-item>
                <a-form-item :label="$lang('mobile option trello assinged list')">
                    <a-select v-model="assinged.idList">
                        <a-select-option v-for="item in trello.lists" :key="item.id" :value="item.id">
                            {{item.name}}
                        </a-select-option>
                    </a-select> 
                </a-form-item>
                <a-form-item :label="$lang('mobile option trello assinged membre')">
                    <a-select v-model="assinged.idMembers">
                        <a-select-option v-for="item in assingedMembre" :key="item.id" :value="item.id">
                            {{item.name}}
                        </a-select-option>
                    </a-select> 
                </a-form-item>
            </a-form>
            <a-button icon="plus" :loading="updateAssingedBtnloading" :disabled="!updateAssinedBtn" @click="updateAssigned" type="primary" block>{{$lang('mobile option trello assinged valider')}}</a-button>
            <a-table 
            :columns="fieldsAssigned"
            :dataSource="mobile.assigned.trello"
            :loading="loadingAssigned"
            rowKey="id">
                <template slot="name" slot-scope="name , record">
                    <span>{{record.value.name}}</span>
                </template>
                <template slot="action" slot-scope="action , record">
                    <a-button type="danger"  @click="deleteAssigned(record)" >{{$lang('mobile option trello assinged delete')}}</a-button>
                </template>
            </a-table>
        </a-tab-pane>
        <a-tab-pane v-show="showed" :disabled="disabled" key="3">
            <span slot="tab">
                {{this.$lang('mobile option trello priorty')}}
            </span>
            
            <a-form :layout="'vertical'">
                <a-form-item :label="$lang('mobile option trello priorty name')">
                    <a-input
                        v-decorator="[
                            'name',
                            {rules: [{ required: true, message: 'Please input name!' }]} ]"
                        v-model="priorty.name" :placeholder="$lang('mobile option trello priorty name')" /> 
                </a-form-item>
                <a-form-item :label="$lang('mobile option trello priorty label')">
                    <a-select v-model="priorty.idLabels">
                        <a-select-option v-for="item in priorityFilter" :key="item.id" :value="item.id">
                            {{item.name}}
                        </a-select-option>
                    </a-select> 
                </a-form-item>
                <a-form-item :label="$lang('mobile option trello priorty date')">
                    <a-input
                        v-decorator="[
                            'cardId',
                            {rules: [{ required: true, message: 'Please input Date !' }]} ]"
                        v-model="priorty.date" :placeholder="$lang('mobile option trello priorty date')" /> 
                </a-form-item>
            </a-form>
            <a-button :loading="updatePriorityBtnloading" :disabled="!updatePriortyBtn" @click="updatePriorty" type="primary" block>{{$lang('mobile option trello priorty valider')}}</a-button>
            <a-table 
            :columns="fieldsPriorty"
            :dataSource="mobile.priority"
            :loading="loadingPriorty"
            rowKey="id">
                <template slot="name" slot-scope="name , record">
                    <span>{{record.value.name}}</span>
                </template>
                <template slot="date" slot-scope="date , record">
                    <span>{{$lang('mobile option trello priorty date add')}} {{record.value.date}} j </span>
                </template>
                <template slot="action" slot-scope="action , record">
                    <a-button type="danger" @click="deletePriority(record)" >{{$lang('mobile option trello priorty delete')}}</a-button>
                </template>
            </a-table>
        </a-tab-pane>
    </a-tabs>
</template>
<script>
	
    import application from '../store/application' ; 
    import trello from '../store/trello' ; 
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
        }
    ]; 

    let fieldsAssigned =  [
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

    let fieldsPriorty =  [
        {
            title: 'TITLE',
            dataIndex: 'name',
            width: 150,
            scopedSlots: { customRender: 'name' },
        },
        {
            title: 'DATE',
            dataIndex: 'date',
            width: 150,
            scopedSlots: { customRender: 'date' },
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
                name : '' , 
                application  : application.state ,
                trello : trello.state , 
                mobile : mobile.state , 
                disabled : false , 
                showed : false , 
                loadingBoard : true ,
                loadingAssigned : true ,
                loadingPriorty : true ,
                fieldsPriorty ,
                fields ,
                fieldsAssigned ,
                //option de la selection de board  
                board : null , 
                //option de la l'ajout de l'assigne 
                assinged : { name : '' , idList : '' , idMembers : '' } , 
                priorty : { name : '' , date : '' , idLabels : '' } , 
                updateBoardBtn : false ,
                updateAssinedBtn : false ,
                updatePriortyBtn : false ,
                //native ID 
                native_id : null , 

                //loader de chaque button 
                updateBoardBtnloading : false , 
                updateAssingedBtnloading : false , 
                updatePriorityBtnloading : false , 
            }
        },

        computed : {
            assingedMembre : function (argument) {
                let preformate = [ ...this.trello.teams , { id : 'default' , name : this.$lang('mobile option trello assinged default')} ] ; 
                let $teams = preformate.filter( (a) => 
                    ! this.mobile.assigned.trello.find( e => e.value.idMembers == a.id ) 
                    //&& this.native_id !== a.id 
                )
                return $teams ; 
            },
            priorityFilter : function(){
                let $labels = this.trello.labels.filter( (a) =>
                    ! this.mobile.priority.find( e => e.value.idLabels == a.id ) 
                )
                return $labels
            }
        },

        methods : {

            async loadAssigned () {
                return await mobile.findAssigned( this.data.id , 'trello' ) ; 
            },

            async deleteAssigned( item ){
                let [ err , res ] = await mobile.deassigned( item.id ) ; 
                this.loadingAssigned = true ;
                if( !err ) this.initAssigned() ; 
                else this.loadingAssigned = false ;
            },

            async initTrelloFromAssinged(){
                await trello.allList( this.data.trello ) ; 
                await trello.allMembres( this.data.trello ) ;
            },

            async initAssigned(){
                this.updateAssingedBtnloading = true ; 
                await this.loadAssigned() ;
                let [err , info ] = await team.info( this.data.trello ) ;
                this.native_id = info.native_id ; 
                this.updateAssinedBtn = true ;
                this.loadingAssigned = false ;
                this.updateAssingedBtnloading = false ; 
            },

            async initTrelloFromPriority(){
                await trello.allLabel( this.data.trello ) ;
            },

            async initPriority(){
                await mobile.findPriorty( this.data.id ) ; 
                this.updatePriortyBtn = true ;
                this.loadingPriorty = false ;
            },

            async changeTabms( e ){
                if ( e == 2 ) {
                    this.initTrelloFromAssinged() ; 
                    this.initAssigned() ; 
                }else if ( e == 3 ) {
                    this.initTrelloFromPriority() ; 
                    this.initPriority() ; 
                }
            },

            async deletePriority( e ){
                let [ err , p ] = await mobile.depriorty( e.id ) ; 
                this.loadingPriorty = true ;
                if( !err ) this.initPriority() ; 
            },

            async updatePriorty(){
                if ( this.priorty.name , this.priorty.date , this.priorty.idLabels ) {
                    this.updatePriorityBtnloading = true ; 
                    let [ err , p ] = await mobile.priorty( this.data.id , { name : this.priorty.name , date : this.priorty.date , idLabels : this.priorty.idLabels }) ; 
                    this.loadingPriorty = true ;
                    if( !err ) this.initPriority() ; 
                    this.priorty.name = '' ;
                    this.priorty.date = '' ;
                    this.priorty.idLabels = '' ;
                    this.updatePriorityBtnloading = false ; 
                }
            },

            async initTrelloInfo(){
                await application.itemApplication( this.data.trello ) ;
                if ( !this.application.item[this.data.trello].url ) 
                    this.disabled = true ;  
                else{
                    this.board = this.application.item[this.data.trello].url ;
                } 
                this.showed = true ;
            },

            async updateBoard(){
                await mobile.destroyOption( this.data.id )
                console.log( this.trello.boards )
                this.updateBoardBtnloading = true ;
                let { id , url } = this.trello.boards.find( ( { url }) => {
                    return url == this.board ? true : false ;
                })
                await application.update( this.data.trello , { url }) ; 
                await option.create({ name : `application_${this.data.trello}_native` , value : id }) ; 
                await application.update( this.data.trello , { url }) ; 
                this.trelloCardUrlInit( this.data.trello ) ; 
                this.updateBoardBtnloading = false ;
            },

            async updateAssigned(){
                if ( this.assinged.name , this.assinged.idList , this.assinged.idMembers ) {
                    let [ err , res ] = await mobile.assigned( this.data.id , { name : this.assinged.name , idList : this.assinged.idList , idMembers : this.assinged.idMembers }) ; 
                    this.loadingAssigned = true ;
                    this.assinged.name = '' ;
                    this.assinged.idList = '' ;
                    this.assinged.idMembers = '' ;
                    if( !err ) this.initAssigned() ; 
                    else this.loadingAssigned = false ;
                }
            },

            async init(){
                this.initTrelloInfo() ; 
                await trello.allBoard( this.data.trello ) ; 
                this.loadingBoard = false;
                this.updateBoardBtn = true; 
            }

        },
		created(){
            this.init() ; 
			/*
            this.on('ApplicationCreate',async () => {
                await application.addApplication( { name :this.name , type : this.type } )
                this.emit('closemodale') ; 
            });
            */
            this.on('onboardupdate',async () => {
                this.disabled = false;
            });

		}
	}
</script>