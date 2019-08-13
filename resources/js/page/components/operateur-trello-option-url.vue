<template>
    <div>
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
        <a-button :loading="updateBoardBtnloading" :disabled="!updateBoardBtn" @click="updateBoard" type="primary" block>{{$lang('mobile option trello board valider')}}</a-button>
    </div>
</template>
<script>
	
    import application from '../store/application' ; 
    import trello from '../store/trello' ; 
    import option from '../store/option' ; 
    import mobile from '../store/mobile' ; 
	 
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

	export default {

		props : ['data'], 

		data(){
            return {
                name : '' , 
                application  : application.state ,
                trello : trello.state , 
                disabled : false , 
                showed : false , 
                loadingBoard : true ,
                fields ,
                //option de la selection de board  
                board : null , 
                updateBoardBtn : false ,
                //loader de chaque button 
                updateBoardBtnloading : false , 
            }
        },

        methods : {

            async updateBoard(){
                if( this.data.app )
                    await mobile.destroyOption( this.data.app )
                console.log( this.trello.boards )
                this.updateBoardBtnloading = true ;
                let { id , url } = this.trello.boards.find( ( { url }) => {
                    return url == this.board ? true : false ;
                })
                await application.update( this.data.trello , { url }) ; 
                await option.create({ name : `application_${this.data.trello}_native` , value : id }) ; 
                this.updateBoardBtnloading = false ;
                let [ e , r ] = await application.initCard( this.data.trello )
                if( e || !r ){
                    alert('une erreur est survenue')
                    return !1 ;
                }
                let [ err , res ] = await application.update( this.data.trello , { url }) ; 
                if( !err )
                    this.emit('onboardupdate', res )
                
            },

            async init(){
                this.board = this.application.item[ this.data.trello ].url ; 
                await trello.allBoard( this.data.trello ) ; 
                this.loadingBoard = false;
                this.updateBoardBtn = true; 
            }

        },
		created(){
            this.init() ; 
		}
	}
</script>