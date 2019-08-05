<template>
    <a-tabs @change="changeTabms" defaultActiveKey="1">
        <a-tab-pane v-show="showed" key="1">
            <span slot="tab">
                {{this.$lang('mobile option trello board')}}
            </span>
            <a-table 
                :columns="fields"
                :dataSource="trello.boards"
                :loading="loadingBoard"
                rowKey="id">
                <template slot="checkbox" slot-scope="checkbox , record">
                    <a-radio-group v-model="board">
                        <a-radio :value="record.url"></a-radio>
                    </a-radio-group>
                </template>
                <template slot="name" slot-scope="name , record">
                    <a target="_blank" :href="record.url">{{record.name}}</a>
                </template>
            </a-table>
            <a-button :disabled="!updateBoardBtn" @click="updateBoard" type="primary" block>{{$lang('mobile option trello board valider')}}</a-button>

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
                <a-form-item :label="$lang('mobile option trello assinged name')">
                    <a-select v-model="assinged.idList">
                        <a-select-option v-for="item in trello.lists" :key="item.id" :value="item.id">
                            {{item.name}}
                        </a-select-option>
                    </a-select> 
                </a-form-item>
                <a-form-item :label="$lang('mobile option trello assinged name')">
                    <a-select v-model="assinged.idMembers">
                        <a-select-option v-for="item in trello.teams" :key="item.id" :value="item.id">
                            {{item.name}}
                        </a-select-option>
                    </a-select> 
                </a-form-item>
            </a-form>
            <a-button icon="plus" :disabled="!updateAssinedBtn" @click="updateAssigned" type="primary" block>{{$lang('mobile option trello assinged valider')}}</a-button>
            <a-table 
            :columns="fieldsAssigned"
            :dataSource="mobile.assigned.trello"
            :loading="loadingAssigned"
            rowKey="id">
                <template slot="name" slot-scope="name , record">
                    <span>{{record.value.name}}</span>
                </template>
                <template slot="list" slot-scope="list , record">
                    <span>{{record.value.idList}}</span>
                </template>
                <template slot="user" slot-scope="user , record">
                    <span>{{record.value.idMembers}}</span>
                </template>
            </a-table>
        </a-tab-pane>
        <a-tab-pane v-show="showed" :disabled="disabled" key="3">
            <span slot="tab">
                {{this.$lang('mobile option trello priorty')}}
            </span>
            <a-button :disabled="!updatePriortyBtn" @click="updatePriorty" type="primary" block>{{$lang('mobile option trello priorty valider')}}</a-button>
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
                        <a-select-option v-for="item in trello.labels" :key="item.id" :value="item.id">
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
            <a-table 
            :columns="fieldsPriorty"
            :dataSource="mobile.priority"
            :loading="loadingPriorty"
            rowKey="id">
                <template slot="name" slot-scope="name , record">
                    <span>{{record.value.name}}</span>
                </template>
                <template slot="date" slot-scope="date , record">
                    <span>{{$lang('mobile option trello priorty date add')}} : {{record.value.date}}</span>
                </template>
            </a-table>
        </a-tab-pane>
    </a-tabs>
</template>
<script>
	
    import application from '../store/application' ; 
    import trello from '../store/trello' ; 
    import mobile from '../store/mobile' ; 
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
            title: 'Liste',
            dataIndex: 'list',
            width: 150,
            scopedSlots: { customRender: 'list' },
        },
        {
            title: 'User',
            dataIndex: 'user',
            width: 150,
            scopedSlots: { customRender: 'user' },
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
            }
        },

        watch:  {
            
        },

        methods : {

            async loadAssigned () {
                return await mobile.findAssigned( this.data.id , 'trello' ) ; 
            },

            async changeTabms( e ){
                if ( e == 2 ) {
                    await this.loadAssigned() ;
                    await trello.allList( this.data.trello ) ; 
                    await trello.allMembres( this.data.trello ) ;
                    this.updateAssinedBtn = true ;
                    this.loadingAssigned = false ;
                }else if ( e == 3 ) {
                    await trello.allLabel( this.data.trello ) ;
                    await mobile.findPriorty( this.data.id ) ; 
                    this.updatePriortyBtn = true ;
                    this.loadingPriorty = false ;
                }
            },

            async updatePriorty(){
                if ( this.priorty.name , this.priorty.date , this.priorty.idLabels ) {
                    await mobile.priorty( this.data.id , { name : this.priorty.name , date : this.priorty.date , idLabels : this.priorty.idLabels }) ; 
                }
            },

            async updateBoard(){
                console.log( this.trello.boards )
                let { id , url } = this.trello.boards.find( ( { url }) => {
                    return url == this.board ? true : false ;
                })
                await application.update( this.data.trello , { url }) ; 
                await option.create({ name : `application_${this.data.trello}_native` , value : id }) ; 
            },

            async updateAssigned(){
                if ( this.assinged.name , this.assinged.idList , this.assinged.idMembers ) {
                    await mobile.assigned( this.data.id , { name : this.assinged.name , idList : this.assinged.idList , idMembers : this.assinged.idMembers }) ; 
                }
            },

            async init(){
                await application.itemApplication( this.data.trello ) ;
                if ( !this.application.item[this.data.trello].url ) 
                    this.disabled = true ;  
                else{
                    this.board = this.application.item[this.data.trello].url ;
                } 
                this.showed = true ;
                await trello.allBoard( this.data.trello ) ; 
                this.loadingBoard = false;
                this.updateBoardBtn = true; 
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