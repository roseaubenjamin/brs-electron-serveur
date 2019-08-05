<template>
    <div :style="{ marginLeft: 'auto', marginRight: 'auto', background: '#fff', minHeight: '380px' , maxWidth : '992px' }">
        <a-row type="flex" justify="center">
            <a-col :xs="24" style="max-width: 500px;" >
                <h1>{{$lang('mobile index title')}}</h1>
                <p>{{$lang('mobile index description')}}</p>
                <a-divider dashed />
                <a-form :layout="'vertical'">
                    <a-form-item v-show=" defaultNote.id == undefined">
                        <a-radio-group
                            buttonStyle="solid"
                            style="width: 100%;"
                            v-model="compte">
                            <a-radio-button :disabled="!mobile.applications.trello" style="width: 50%;" value="trello">
                                Trello
                            </a-radio-button>
                            <a-radio-button :disabled="!mobile.applications.infusionsoft" style="width: 50%;" value="infusionsoft">
                                Infusionsoft
                            </a-radio-button>
                        </a-radio-group>
                    </a-form-item> 
                    <a-form-item>
                        <note-vocal :placedata="placeVocal"></note-vocal>
                    </a-form-item>
                    <a-form-item v-if="(mobile.assigned.trello.length||mobile.assigned.infusionsoft.length)&&defaultNote.id == undefined" :label="$lang('Pour ')" style="width: 100%;">
                        <a-radio-group class="select-pour" buttonStyle="solid" v-model="form.pour" style="width: 100%;">
                            <a-radio-button class="select-pour-option" v-for="item in (mobile.assigned.trello.length?mobile.assigned.trello:mobile.assigned.infusionsoft)" :key="item.id" :value="item.id" :style="mobile.assigned.trello.length?styleSelect(mobile.assigned.trello):styleSelect(mobile.assigned.infusionsoft)">{{item.name}}</a-radio-button>
                        </a-radio-group>
                    </a-form-item> 
                    <contactifs v-if="( compte=='infusionsoft' && defaultNote.id == undefined ) || action " :label="$lang('Contact IFS ')" ></contactifs>
                    <a-form-item v-if="compte=='trello' && defaultNote.id == undefined " :label="$lang('Priorité ')" style="width: 100%;">
                        <a-radio-group @change="prioriterChanger" class="select-pour" buttonStyle="solid" v-model="form.prioriter" style="width: 100%;">
                            <a-radio-button class="select-pour-option" v-for="item in mobile.priority.labels" :key="item.id" :value="item.id" :style="styleSelect(mobile.priority.labels)">{{item.name}}</a-radio-button>
                        </a-radio-group>
                    </a-form-item>   
                    <a-form-item v-if="(compte=='infusionsoft' && defaultNote.id == undefined) || action " :label="$lang('Priorité ')" style="width: 100%;">
                        <a-radio-group @change="prioriterChanger" class="select-pour" buttonStyle="solid" v-model="form.prioriter" style="width: 100%;">
                            <a-radio-button class="select-pour-option ellipsis" v-for="item in prioriters" :key="item.id" :value="item.id" :style="styleSelect(prioriters)">{{item.name}}</a-radio-button>
                        </a-radio-group>
                    </a-form-item>   
                    <a-form-item :label="$lang('Date ')" v-if="(defaultNote.id == undefined) || action " >
                        <a-date-picker style="width: 100%;" v-model="form.date" @change="changeDate"/>
                    </a-form-item>
                    <mobile-form></mobile-form>
                    <a-button type="primary" icon="table" @click="create" block :loading="loading_btn" >Valider</a-button>
                </a-form>
            </a-col>
        </a-row>
    </div>
</template>
<script>

    import mobile from '../../store/mobile' ; 
    import application from '../../store/application' ; 
    //import note from '../store/note' ; 
    import makeid from '../../libs/makeid' ; 
    import wait from '../../libs/wait' ; 
    import styleSelect from '../../libs/styleSelect' ; 
    import formateDescription from '../../libs/formateDescription' ; 
    import moment from 'moment' ; 

    export default {
        data(){
            return {
                //option de la page mobile ( page trello et ou page infusionsoft a utiliser )
                application : application.state , 
                mobile : mobile.state ,  
                form : mobile.form ,
                compte : '',
                loader : true ,
                prioriters : [
                    { name: 'Critical', id: 1 },
                    { name: 'Essential', id: 2 },
                    { name: 'Non-essential', id: 3 },
                ],
                //le note vocal est enregistrer ici 
                loading_btn : false , 
                loading_more : false , 
                defaultContact : null , 
                placeVocal : null , 
                tempstemp : null ,
                //information sur la default note 
                defaultNote  : {} , 
                //update or duplicate note 
                action : (window.location.search.match(new RegExp('[?&]' + 'action' + '=([^&]+)')) || [, null])[1], 
            }
        },
        watch : {
            compte : async function () {
                if ( this.compte == 'trello' ) { 
                    this.mobile.assigned.trello && this.mobile.assigned.trello[0] ? this.form.pour = this.mobile.assigned.trello[0].id :'';
                    this.mobile.priority && this.mobile.priority[0] ? this.form.prioriter = this.mobile.priority[0].id :'';
                    this.prioriterChanger() ; 
                }else if ( this.compte == 'infusionsoft' ) {
                    this.fetching = true ;
                    this.mobile.assigned.infusionsoft && this.mobile.assigned.infusionsoft[0] ? this.form.pour = this.mobile.assigned.infusionsoft[0].id :'';
                    this.form.prioriter = 2 ; 
                    this.prioriterChanger() ;
                    return ; 
                    let [ e , d ] = await infusionsoft.allContact( this.option.external.infusionsoft , '' , this.defaultContact ) ;
                    console.log( d ) ; 
                    this.form.contactTotal = d.maxpage ; 
                    console.log( 'compte : async function' , this.infusionsoft.contacts ) ; 
                    this.fetching = false ;
                    this.suggestionLoadSearch = true ; 
                    this.infusionsoft.contacts.length?this.handleSearch(''):'' ; 
                }
            },
        },
        methods : {
            
            styleSelect ,

            prioriterChanger : function ( ) {
                let add = this.form.prioriter ;
                this.compte == 'trello'?
                this.mobile.priority.forEach( e => {
                    if ( e.id == this.form.prioriter ) {
                        add = parseInt( e.cardId ) ;  
                    }
                }): 
                add = this.form.prioriter
                this.form.date = moment().clone().add( add , 'day' )
            },

            formateTitle : function () {
                return placedata('categorieArray' , this.form.categorie)+" "+this.form.produit+" -"+( this.form.categorie !== 'autre' ? (this.form[this.form.categorie]=='_____'? this.form[this.form.categorie+"_autre"] : this.form[this.form.categorie]) : this.form.autre )
            }, 

            errorCreate(){
                alert( 'ERREUR DE CR2ATION ' )
                this.loading_btn = false ;
            },

            //ici on change la valeur des date 
            changeDate(){ 
                this.form.prioriter = null ; 
            }, 

            //création de card dans trello
            trelloCard : async function ( update ) {
                console.log( this.defaultNote , update ) ;
                this.loading_btn = true ; 
                let body = {
                    title : this.formateTitle() , 
                    compteId : this.option.external.trello , 
                    description : this.formateDescription(this.formateForm()) , 
                    pour : this.form.pour , 
                    prioriter : this.form.prioriter , 
                    date : this.form.date.format('YYYY-MM-DDTHH:mm:ssZ'), 
                }
                console.log( body ) ; 
                let err , note ; 
                if ( this.defaultNote && this.defaultNote.id ) {
                    body['nativeId'] = this.defaultNote.id ; 
                    [ err , note ] = await trello.cardUpdate( body ) ;
                }else{
                    [ err , note ] = await trello.card( body ) ;
                }
                if ( err ) {
                    return this.errorCreate() ; 
                } 
                return note

            },

            //création de note d'infusionsoft avec la mise a jour 
            ifsnoteCreate : async function ( update ) {
                console.log( this.defaultNote , update ) ;               
                if ( !this.form.contactId && !update ) {
                    return alert(' Contact Infusionsoft oblogatoire')
                }
               this.loading_btn = true ; 
                let body = {
                    title : this.formateTitle() , 
                    compteId : this.option.external.infusionsoft , 
                    description : 'https://therapiequantique.net/read/'+this.form.NOTEID , 
                    pour : this.form.pour , 
                    prioriter : this.form.prioriter , 
                    date : this.form.date.format('YYYY-MM-DDTHH:mm:ssZ'), 
                    contactId : this.form.contactId , 
                }
                let err , note ; 
                if ( this.defaultNote && this.defaultNote.id ) {
                    body['nativeId'] = this.defaultNote.id ; 
                    [ err , note ] = await infusionsoft.noteUpdate( body ) ;
                }else{
                    [ err , note ] = await infusionsoft.note( body ) ;
                }
                if ( err ) {
                    this.errorCreate() ; 
                } 
                return note
            },

            async create(){

                if ( ! this.form.note && !this.placeVocal ) 
                    return alert('Le vocal est obligatire')
                let noteSave = null ;

                console.log( this.form ) ; 
                return
                this.compte=='trello'?noteSave = await this.trelloCard((this.$route.params.id !== undefined?true:false)):noteSave = await this.ifsnoteCreate((this.$route.params.id !== undefined?true:false)) ; 
                if ( !noteSave || (!noteSave.url&&this.compte == 'trello') ) {
                    return this.errorCreate() ; 
                }
                //si le note que vous évez crée est un card de trello, alors on fait la recréation des ID 
                let id = null ; 
                this.compte == 'trello' ? id = decodeURIComponent( noteSave.url.replace('https://trello.com', '') ).split('/').join('_').replace('/', '_').normalize('NFD').replace(/[\u0300-\u036f]/g, "") : '' 
                console.log( noteSave );
                //création de note dans notre application 
                let url = '/upload?' ; 
                url += 'NOTEID='+this.form.NOTEID
                url += '&type='+(this.compte=='trello'?'trello':'infusionsoft') 
                url += '&appId='+(this.compte=='trello'?this.option.external.trello:this.option.external.infusionsoft)
                url += '&nativeId='+noteSave.id
                url += '&attache='+(this.compte=='trello'?'card':(this.form.pour=="default"?'note':'task'))  
                if ( id ) 
                    url += '&newId='+id
                url += '&text='+''
                url += '&title='+''
                console.log( url ) ; 
                if ( this.$route.params.id !== undefined ) {
                    url += '&update='+true
                }
                if ( this.form.note ) {
                    url += '&file='+true
                }else{
                    url += '&file='+false
                }
                let formData = new FormData();
                formData.append('file', this.form.note );
                let [ err , vocal ] = await note.noteCreate( url , formData )
                console.log( vocal ) ;
                if ( err ) {
                    alert('@TODO:il y a une erreur')
                } 
                let [ error , formSave ] = await form.formCreate( vocal.id , this.formateForm() )
                console.log( formSave );
                console.log( 'Upload a formulaire' )
                let [ errorFlash , flash ] = await index.setFlash( { create : true } ) ;
                if ( flash ) {
                    window.location.href= window.urlapplication + "/read/"+ ( id ? id : this.form.NOTEID)
                }
               this.loading_btn = false ; 
            },  

            async trellonote( n ){
                let [ err , note ] = await trello.itemCard( n.nativeId , n.ApplicationId ) ;
                console.log( note )
                if ( !note.name ) 
                    return this.existe = false ;
                this.title = note.name ; 
                this.defaultNote = Object.assign({} , note )
                return note  
            },

            async ifsfindnote( n ){
                let err , note ; 
                if ( n.attache == 'note' ) 
                    [ err , note ] = await infusionsoft.itemNote( n.nativeId , n.ApplicationId ) ;
                else if ( n.attache == 'task' ) 
                    [ err , note ] = await infusionsoft.itemTask( n.nativeId , n.ApplicationId ) ;  
                //defaultValueContact
                //récupération des listes de tout les contacts 
                //si dans la liste des contacts, on n'a pas le contact si contre 
                //on ajoute dans le première argument 
                //s'il existe, on transforme dans la premièer argument
                this.defaultContact = note.contact.id ; 
                this.compte = this.application.item.type ; 
                this.form.contactId = note.contact.id ; 
                if ( ! note.title ) 
                    return this.existe = false ;
                this.defaultNote = Object.assign({} , note )
                this.existe = true ; 
                return note  
            },

            defaultForm( form ){
                for( let item of form ){
                    if ( item.name == 'socas') {
                        this.form[ item.name ] = item.value.split(',').map( e => parseInt( e ) )
                    }else{
                        let place = parseInt( this.form[ item.name ] )
                        this.form[ item.name ] = isNaN( place ) ? item.value : place;
                    }
                }
            },

            /*
             * Chargement de tout les donners utilse dans l'update 
            */
            async loadUpdate(){
                let [ err , noteItem ] = await note.find( this.$route.params.id ) ;
                console.log( noteItem )
                this.placeVocal = noteItem.unique 
                if ( err || !noteItem || (noteItem && !noteItem.ApplicationId) ) {
                    return this.existe = false ;
                    //note n'existe pas on vous redirige a la page nouvelle note 
                }
                await application.itemApplication( noteItem.ApplicationId ) ; 
                if ( noteItem.type == "infusionsoft" ) {
                    this.option.external.trello = null ; 
                    this.option.external.infusionsoft = noteItem.ApplicationId ; 
                    this.ifsfindnote( noteItem )
                } 
                if ( noteItem.type == "trello" && ! this.action ) {
                    this.option.external.infusionsoft = null ;
                    this.option.external.trello = noteItem.ApplicationId ;
                    this.trellonote( noteItem )
                    this.compte = this.application.item.type ; 
                } 
                if ( noteItem.type == "trello" && this.action ) {
                    //this.option.external.infusionsoft = null ;
                    await exoption.findOption() ; 
                    this.option.external.trello = null ;
                    if ( !this.option.external.infusionsoft ) {
                        //si l'utilisateur n'a pas de compte infurionsosft 
                        //on le redirige pour en configuer un 
                        return this.$router.push({ name: 'option' }) 
                    }
                    this.trellonote( noteItem )
                    this.compte = 'infusionsoft' ; 
                } 
                //récupération des formulaires de l'application 
                let [ errorForm , formNote ] = await form.all( noteItem.id ) ; 
                if ( errorForm ) {
                    console.log( errorForm )
                    return
                }
                this.defaultForm( formNote ) ; 
            }, 

            async init(){
                await this.$nextTick() ; 
                this.form.NOTEID = this.$route.params.id?this.$route.params.id:makeid(12) ;  
                this.loader = false;
                console.log( this.$route.params.id )
                if ( this.$route.params.id !== undefined ) {
                    this.loadUpdate() ; 
                }else{
                    //Récupération des options de mobile 
                    await mobile.find() ; 
                    if ( !this.option.external.infusionsoft&&!this.option.external.trello ) {
                        //ouvrire la page option 
                        return this.$router.push({ name: 'option' }) 
                    }
                    this.option.external.infusionsoft?this.compte='infusionsoft':this.compte='trello' ;
                }               
            }

        },
        mounted(){
            this.init() ; 
        },
        created(){
            this.on('note-vocal-changed',( note ) => {
                this.form.note = note ;
                console.log( '--- NOTE' , this.form.note ) 
            })
        }
    }

</script>
<style>
    .select-pour-option, .select-pour-option *{
        border-radius: 0px !important;
    }
    .highlight {
        background-color: yellow;
    }
    li .name_suggestion{
        display: block !important;
    }
    li .email_suggestion{
        display: block !important;
    }
    li .email_suggestion_title{
        display: none !important; 
    }
    li .mark {
        font-style: normal;
        padding: 0em;
        background-color: #fdffca;
        font-weight: 600;
    }
    .more-search{
        position: relative;
        height: 30px !important;
        line-height: 30px !important;
    }

    .more-search a:hover{
        background-color: #40a9ff ; 
        color: #FFF !important ; 
    }
</style>