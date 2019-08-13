<template>
    <div :style="{ marginLeft: 'auto', marginRight: 'auto', background: '#fff', padding: '24px', minHeight: '380px' , maxWidth : '992px' }">
        <a-spin v-if="loading" tip="Loading..." style="position: absolute;top: 50%;left: 50%;"></a-spin>
        <a-row type="flex" justify="center" :gutter="16">
            <a-col :span="14" >
                <a-alert
                    v-if="newcreate"
                    :message="$lang('read success new note')"
                    type="success"
                    showIcon
                    style="margin-bottom: 1rem;"
                />
                <a-alert
                    v-if="existe===false"
                    :message="$lang('read note non existe')"
                    type="error"
                    showIcon
                    style="margin-bottom: 1rem;"
                />
                <div v-show="existe===true">
                    <h2>{{title}}</h2>
                    <p></p>
                    <div id="vocale-listen"></div>    
                    <a-divider dashed />
                    <div>
                        <a-button @click="newnote">Nouvelle note</a-button>
                        <a-button @click="edit" type="primary">Edit</a-button>
                    </div>
                </div>
            </a-col>
        </a-row>
    </div>
</template>
<script>

    import trello from '../../store/trello' ; 
    import listen from '../../libs/listen';
    import infusionsoft from '../../store/infusionsoft' ; 
    import note from '../../store/note' ; 

    export default {
        data(){
            return {
                newcreate : window.success_create , 
                existe : null ,
                title : '' , 
                id : '', 
                loading : true , 
            }
        },
        computed : {

        },
        methods : {

            async newnote(){
                let url = window.APP_URL+'/' ;
                window.location.href = url 
            },
            
            async edit(){
                let url = window.APP_URL+'/mobile/'+ this.$route.params.id +'/update'  ;
                window.location.href = url 
            },

            async ifsfindnote( n ){
                let err , note ; 
                console.log( n )
                if ( n.attache == 'note' ) 
                    [ err , note ] = await infusionsoft.findNote( n.application_id , n.native_id ) ;
                else if ( n.attache == 'task' ) 
                    [ err , note ] = await infusionsoft.findTask( n.application_id , n.native_id ) ;
                console.log( note )
                this.loading = false ; 
                if ( ! note.title ) 
                    return this.existe = false ;
                this.existe = true ; 
                this.title = note.title ; 
                new listen( 'vocale-listen' ,`${window.APP_URL}/audio/${n.unique}` , 'audio-liste-note-record' ) ; 
                console.log( window.urlapplication+'/audio/'+window.unique  )
            },

            async trellonote( n ){
                let [ err , note ] = await trello.card( n.application_id , n.native_id ) ;
                console.log( note )
                this.loading = false ; 
                if ( !note.name ) 
                    return this.existe = false ;
                this.title = note.name ; 
                this.existe = true ; 
                new listen( 'vocale-listen' , `${window.APP_URL}/audio/${n.unique}` , 'audio-liste-note-record' ) ; 
                console.log( window.urlapplication+'/audio/'+window.unique  )
            },

            async init(){
                await this.$nextTick() ; 
                //récupération des informations de ce note en native 
                let [ err , noteItem ] = await note.find( { unique : this.$route.params.id } ) ;
                if ( err || !noteItem || (noteItem && !noteItem.native_id ) ) {
                    return this.existe = false ;
                }
                console.log(noteItem.attache)
                if ( noteItem.attache == "note" || noteItem.attache == "task" ) {
                    this.ifsfindnote( noteItem )
                } 
                if ( noteItem.attache == "card" ) {
                    this.trellonote( noteItem )
                } 
            }
        },
        mounted(){
            this.init() ; 
        },
        created(){

        }
    }

</script>
<style>
    
</style>