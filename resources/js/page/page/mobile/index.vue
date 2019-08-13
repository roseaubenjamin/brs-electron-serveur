<template>
    <div :style="{ marginLeft: 'auto', marginRight: 'auto', background: '#fff', minHeight: '380px' , maxWidth : '992px' }">
        <div v-if="error" style="position: fixed ; z-index: 1000; width: 50%; max-width: 500px ; left: 50% ; transform: translateX(-50%);">
            <a-alert
                message="Error"
                :description="ErrorText"
                type="error"
                showIcon
            />
        </div>
        <a-row type="flex" justify="center">
            <a-col :xs="24" style="max-width: 500px;" >
                <h1>{{$lang('mobile index title')}}</h1>
                <p>{{$lang('mobile index description')}}</p>
                <a-divider dashed />
                <a-form :layout="'vertical'">

                    <a-form-item v-show="!duplicate&&!update">
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
            
                    <a-form-item  v-if="(listemobile && listemobile.length > 1)&&!update" :label="$lang('mobile select list')">
                        <a-select @change="switchMobile" v-model="routeparamsid">
                            <a-select-option v-for="item in listemobile" :key="item.id" :value="item.id">
                                {{item.name}}
                            </a-select-option>
                        </a-select> 
                    </a-form-item>

                    <a-form-item>
                        <note-vocal :placedata="placeVocal"></note-vocal>
                    </a-form-item>

                    <a-form-item v-if="(( compte=='trello' && mobile.assigned.trello.length ) ||( compte=='infusionsoft' && mobile.assigned.infusionsoft.length ))&&!update" :label="compte=='trello' ? $lang('mobile index assigned trello') :$lang('mobile index assigned ifs')" style="width: 100%;">
                        <a-radio-group class="select-pour" buttonStyle="solid" v-model="form.assigned" style="width: 100%;">
                            <a-radio-button class="select-pour-option" v-for="item in assigned" :key="item.id" :value="item.id" :style="styleSelect(assigned)">{{item.value.name}}</a-radio-button>
                        </a-radio-group>
                    </a-form-item> 

                    <contactifs v-if="( compte=='infusionsoft' ) &&!update"  ></contactifs>

                    <a-form-item v-if="compte=='trello' && mobile.priority  &&!update " :label="$lang('mobile index priority trello')" style="width: 100%;">
                        <a-radio-group @change="prioriterChanger" class="select-pour" buttonStyle="solid" v-model="form.prioriter" style="width: 100%;">
                            <a-radio-button class="select-pour-option" v-for="item in mobile.priority" :key="item.id" :value="item.id" :style="styleSelect(mobile.priority)">{{item.value.name}}</a-radio-button>
                        </a-radio-group>
                    </a-form-item>   

                    <a-form-item v-if="( compte=='infusionsoft' ) &&!update " :label="$lang('mobile index priority ifs')" style="width: 100%;">
                        <a-radio-group @change="prioriterChanger" class="select-pour" buttonStyle="solid" v-model="form.prioriter" style="width: 100%;">
                            <a-radio-button :style="styleSelect(prioriters)" class="select-pour-option ellipsis" v-for="item in prioriters" :key="item.id" :value="item.id">{{item.name}}</a-radio-button>
                        </a-radio-group>
                    </a-form-item>   

                    <a-form-item :label="$lang('mobile index date')" v-if="!update" >
                        <a-date-picker style="width: 100%;" v-model="form.date" @change="changeDate"/>
                    </a-form-item>

                    <mobile-form  v-if="!update" ></mobile-form>
                    
                    <a-button :disabled="error" type="primary" icon="table" @click="create" block :loading="loading_btn" >Valider</a-button>

                </a-form>
            </a-col>
        </a-row>
    </div>
</template>
<script>

    import mobile from '../../store/mobile' ; 
    import application from '../../store/application' ; 
    import note from '../../store/note' ; 
    import form from '../../store/form' ; 
    import makeid from '../../libs/makeid' ; 
    import wait from '../../libs/wait' ; 
    import formateDescription from '../../libs/formateDescription' ; 
    import getForm from '../../libs/getForm' ; 
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
                defaultContact : null , 
                placeVocal : null , 
                tempstemp : null ,
                //valeur de paramètre de route par défault 
                routeparamsid  : this.$route.params.id , 
                //description des erreur 
                ErrorText : '',
                error : false , 

                //affichage en fonction action 
                duplicate : null , 
                update : null , 
                //liste possible des applications mobile 
                listemobile : [] , 
            }
        },

        computed : {
            assigned : function(){
                if( this.compte == 'trello' ){
                    return this.mobile.assigned.trello
                }else if( this.compte == 'infusionsoft' ){
                    return  this.mobile.assigned.infusionsoft  ; 
                }
            },
        }, 

        watch : {
            compte : async function () {
                if ( this.compte == 'trello' ) { 
                    this.switchTrello() ; 
                }else if ( this.compte == 'infusionsoft' ) {
                    this.switchInfusionsoft() ; 
                }
            },
        },
        methods : {
            
            styleSelect( data ) {
                if ( data.length == 2 || data.length == 1 ) {
                    return { width : '50%' }
                }else if ( data.length == 3 ) {
                    return { width : '33.33333333333333333333%' }
                }else if ( data.length > 3 && data.length< 6) {
                    return { width : '50%' }
                }else if ( data.length >= 6 ) {
                    return { width : '33.33333333333333333333%' }
                }
            },

            //switch de compte vers trello 
            switchTrello : async function(){
                console.log( this.mobile.applications.trello )
                this.mobile.priority && this.mobile.priority[0] ? this.form.prioriter = this.mobile.priority[0].id :'';
                this.prioriterChanger() ; 
                await mobile.findAssigned( this.routeparamsid , 'trello' )
                let note = this.mobile.assigned.trello.find( e => e.value.idMembers == 'default' )
                if( note ) this.form.assigned = note.id ; 
                else this.mobile.assigned.trello && this.mobile.assigned.trello[0] ? this.form.assigned = this.mobile.assigned.trello[0].id :'';
            },

            switchInfusionsoft : async function(){
                console.log( this.mobile.applications.infusionsoft )
                this.fetching = true ;
                this.form.prioriter = 2 ; 
                this.prioriterChanger() ;
                await mobile.findAssigned( this.routeparamsid , 'infusionsoft' )
                let note = this.mobile.assigned.infusionsoft.find( e => e.value.user_id == 'note' )
                if( note ) this.form.assigned = note.id ; 
                else this.mobile.assigned.infusionsoft && this.mobile.assigned.infusionsoft[0] ? this.form.assigned = this.mobile.assigned.infusionsoft[0].id :'';
                return !0;
            },

            prioriterChanger : function ( ) {
                let add = this.form.prioriter ;
                this.compte == 'trello'?
                this.mobile.priority.forEach( e => {
                    if ( e.id == this.form.prioriter ) {
                        add = parseInt( e.value.date ) ;  
                    }
                }): 
                add = this.form.prioriter
                this.form.date = moment().clone().add( add , 'day' )
            },

            errorCreate(err){
                this.error = true
                this.ErrorText = this.$lang( err )
                this.loading_btn = false ;
                setTimeout( () => {
                    this.error = false
                }, 5000);
            },

            //ici on change la valeur des date 
            changeDate(){ 
                this.form.prioriter = null ; 
            }, 

            async create(){
                this.loading_btn = true ;
                let [ err , success ] = await mobile.vocal( this.routeparamsid , this.compte , this.routeparamsid ) ; 
                if( err )
                    return this.errorCreate( err )
                //lancer une evenement pour crée les formulaire 
                let [ errForm , post ] = await form.create( success.id , getForm( this.form ) )
                //@todo : dans le cas ou l'enregistrement du formulaire a échouer, on peut répéter cette action 
                if( post ){
                    return window.location.href = `${window.APP_URL}/read/${this.form.NOTEID}?state=success` ;
                }
                return this.errorCreate( 'mobile error form' );
            }, 

            async initDupCop(){
                //récupération des possible mobile pour cette notes
                let [ err , natibem ] = await mobile.findMobileListeFromUnique( this.$route.params.unique ) ;
                if( err ){
                    this.error = true 
                    this.ErrorText = this.$lang('mobile error mobile liste') 
                    return 
                }
                this.listemobile = [ ...natibem ] ; 
                this.routeparamsid = this.listemobile[0].id;
                console.log( natibem ) 
                let [ e , n ] = await note.find( { unique : this.$route.params.unique } ) ; 
                if( !n ){
                    this.error = true 
                    this.ErrorText = this.$lang('mobile error duplicate note existe') 
                    return 
                }
                this.duplicate = n ; 
                this.compte = 'infusionsoft' ; 
                await this.switchMobile() ; 
                //récupération note et ajout de note dans 
                this.emit('vocallisten', `${window.APP_URL}/audio/${this.$route.params.unique}` )
                this.emit('placeforme', this.duplicate.id )
                //await mobile.duplicate( this.routeparamsid ) ; 
            },

            async switchMobile(){
                await mobile.find( this.routeparamsid ) ; 
                if( this.compte == "infusionsoft" )
                    return this.switchInfusionsoft(); 
                else if( this.compte == "trello" )
                    return this.switchTrello(); 
            },

            async initNew(){
                this.form.NOTEID = makeid(12) ;  
                //Récupération des options de mobile 
                await mobile.find( this.routeparamsid ) ; 
                if( this.mobile.applications.infusionsoft ){
                    this.compte='infusionsoft' 
                    this.switchTrello() 
                }else{
                    this.compte='trello' 
                    this.switchInfusionsoft(); 
                }
                if( this.mobile.applications.trello )
                    await mobile.findPriorty( this.routeparamsid )
            } ,

            async initUpdate( isSingle ){

                let unique = this.$route.params.unique ; 
                if( isSingle ){
                    unique = this.$route.params.id ; 
                    let [ err , natibem ] = await mobile.findMobileListeFromUnique( unique ) ;
                    if( err ){
                        this.error = true 
                        this.ErrorText = this.$lang('mobile error mobile liste') 
                        return 
                    }
                }

                let [ e , n ] = await note.find( { unique } ) ; 
                if( !n ){
                    this.error = true 
                    this.ErrorText = this.$lang('mobile error update note existe') 
                    return 
                }

                this.update = n ; 
                
                this.emit('vocallisten', `${window.APP_URL}/audio/${unique}` )

            },

            async init(){
                await this.$nextTick() ; 
                this.loader = false;
                if( this.$route.name == "Duplicate" || this.$route.name == "Copy" )
                    this.initDupCop() ; 
                else if( this.$route.name == "Home" )
                    this.initNew() ; 
                else if( this.$route.name=="Update" )
                    this.initUpdate() ; 
                else if( this.$route.name=="UpdateSingle" )
                    this.initUpdate(true) ; 

                console.log( this.routeparamsid )
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