<template>
    <a-form-item >
        <a-select
            dropdownClassName="suggestion_dropdown"
            showSearch
            placeholder="Select a person"
            optionFilterProp="children"
            v-bind:class="{ active: opensuggestion }"
            class="opensuggestion"
            style="width: 100%"
            @change="handleChange"
            @search="handleSearch"
            @blur="closesuggestionEvent"
            @popupScroll="suggestionScroll"
            @dropdownVisibleChange="dropdownVisibleChange"
            :autoClearSearchValue="true" 
            :defaultValue="defaultContact"
            :filterOption="filterConctats">
            <a-spin v-if="fetching" slot="notFoundContent" size="small"/>
            <a-select-option :key="item.id" :value="item.id" v-for="item in suggestion">
                <span class="item-suggestion" v-html="highlight( item )"></span>
            </a-select-option>
            <a-select-option class="more-search" disabled="disabled" v-if=" ( contactPage < contactTotal ) && suggestion.length " :key="'more'" :value="'more'" >
                <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0;height: 36px;font-weight: bold;line-height: 36px;text-align: center; cursor: none;">
                    <a-spin style="display: flex;padding: 5px 12px;" :tip="contactPage+' / '+contactTotal" v-if="loading_more" >
                        <a-icon slot="indicator" type="loading" style="font-size: 22px; margin-right: 10px;" spin />
                    </a-spin>
                </div>
            </a-select-option>
        </a-select>
    </a-form-item> 
</template>
<script>
    import application from '../store/application' ; 
    import infusionsoft from '../store/infusionsoft' ; 
    import mobile from '../store/mobile' ; 
	
	export default {

		props : [], 

		data(){
            return {
                infusionsoft : infusionsoft.state , 
                mobile : mobile.state , 
                form : mobile.form , 
                //load les contacts 
                opensuggestion : true ,
                //dafault contact lors de l'update 
                defaultContact : null , 

                suggestion : [] , 
                opensuggestion : false , 
                suggestionDOM : null , 
                suggestionLongeur : 0 ,
                suggestionLoadSearch : false , 
                fetching : false , 

                loading_more : false , 
                contactSearch : '' , 
                //page d'affichage de contacts 
                contactPage : 1 , 
                contactTotal : 0 , 
                //
                inSearch : null , 
            }
        },

        computed : {
            contacts : {
                get : function () {
                    return this.suggestion ; 
                },
                set : function ( res ) {
                    this.suggestion = [ ...this.suggestion , ...res ] 
                    return this.suggestion
                }
            },
        },

        methods : {

            suggestionScroll : async function( e , i ) {
                let offsetHeight = this.suggestionDOM.offsetHeight
                let scrollTop = this.suggestionDOM.scrollTop
                if ( this.suggestionDOM && ( (this.suggestionDOM.scrollTop + offsetHeight) >= this.suggestionDOM.scrollHeight ) && ( this.suggestionLongeur < this.suggestionDOM.scrollHeight ) && this.suggestionLoadSearch ) {
                    this.suggestionLongeur = this.suggestionDOM.scrollHeight ;
                    let j =  await this.handleMore() ; 
                    setTimeout(() => {
                        this.suggestionDOM.scrollTop = scrollTop ;
                    }, 100); 
                }
            },

            watchDOM : function ( select , callback ) {
                let sel = null ; 
                sel = document.querySelector( select ) ;
                if ( sel ) {
                    callback( sel ) ; 
                } else {
                    setTimeout(()=> {
                        this.watchDOM( select , callback ) 
                    },500);
                }
            },

            dropdownVisibleChange : async function ( e , i ) {
                if ( !e ) {
                    this.suggestion = [] ;
                    return this.handleSearch('');
                }
                this.suggestion = [] ; 
                await this.getData() ; 
                this.opensuggestion = true ; 
                this.$nextTick(()=>{
                    this.watchDOM( ".suggestion_dropdown ul" , ( el )=>{
                        this.suggestionDOM = el ; 
                    })
                }); 
            },

            closesuggestionEvent : function () {
                this.opensuggestion = false ; 
            },

            highlight : function ( item ) {
                var search = this.contactSearch ;
                search = search.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                var re = new RegExp(search, 'gi');
                let text = item.family_name + item.given_name ;
                if (search.length > 0)
                    return `<span class="name_suggestion">${(text.replace(re, `<i class="mark">$&</i>`))}</span><span class="email_suggestion_title"> <strong>Email :</strong></span><span class="email_suggestion"><strong></strong>${item.emailText.replace(re, `<i class="mark">$&</i>`)}</span>`;
                else return `<span class="name_suggestion">${text}</span><span class="email_suggestion_title"> <strong>Email :</strong></span><span class="email_suggestion"><strong></strong>${item.emailText}</span>`;
            },

            filterConctats : function ( input, option ) {
                return true
            },

            handleChange : function ( value ) {
                this.form.contactId = value ; 
            },

            handleMore : async function () {
                this.contactPage++  ;
                this.loading_more = true ; 
                await this.getData() ;  
                setTimeout( () => {
                    this.loading_more = false ; 
                }, 600); 
                return !0 ; 
            },

            handleSearch : function ( search ) {
                this.suggestion = [] ; 
                this.suggestionLongeur = 0 ; 
                this.contactPage = 1 ;
                let recal = false ;  
                if ( this.inSearch === true ) {
                    recal = true ;
                }
                this.contactSearch = search ;
                this.suggestionLoadSearch = false ; 
                this.fetching = true ; 
                this.inSearch = true ; 
                clearTimeout( this.tempstemp )
                this.tempstemp = setTimeout( async () => {
                    clearTimeout( this.tempstemp )
                    console.log('---SERACHE : ' , search )
                    await this.getData() ; 
                    this.inSearch = false ; 
                    if ( recal ) {
                        setTimeout( async () => {
                            this.handleSearch( search )
                        }, 300);
                    }
                }, 500 );
            }, 

            async getData(){ 
                this.fetching = true ; 
                let [ err , data ] = await infusionsoft.allContacts( this.mobile.applications.infusionsoft , this.contactSearch , this.contactPage , this.defaultContact ) ;
                return this.setData( data ) ; 
            },

            setData( { data , maxpage , total } ){
                this.contacts = data ; 
                this.contactTotal = maxpage ;
                this.fetching = false ; 
                this.suggestionLoadSearch = true ; 
            },

            async init( ){
                //await infusionsoft.fetchContact( this.mobile.applications.infusionsoft ) ; 
                this.getData() ;  
            }

        },
		created(){
    		this.init() ; 
		}
	}
</script>