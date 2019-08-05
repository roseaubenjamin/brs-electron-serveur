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
        </a-select>
    </a-form-item> 
</template>
<script>
	/*
        <a-select-option class="more-search" disabled="disabled" v-if=" ( form.contactPage < form.contactTotal ) && suggestion.length " :key="'more'" :value="'more'" >
            <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0;height: 36px;font-weight: bold;line-height: 36px;text-align: center; cursor: none;">
                <a-spin style="display: flex;padding: 5px 12px;" :tip="form.contactPage+' / '+form.contactTotal" v-if="loading_more" >
                    <a-icon slot="indicator" type="loading" style="font-size: 22px; margin-right: 10px;" spin />
                </a-spin>
            </div>
        </a-select-option>
    */
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

                contactSearch : '' , 

            }
        },

        computed : {
            contacts : {
                get : function () {
                    return this.suggestion ; 
                },
                set : function ( res ) {
                    this.suggestion = [...res]
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
                    console.log( 'NEXT ......' , this.suggestionDOM.scrollTop + offsetHeight , ' === ' , this.suggestionDOM.scrollHeight )
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

            dropdownVisibleChange : function ( e , i ) {
                if ( !e ) 
                    return this.handleSearch('')
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
                if (search.length > 0)
                    return `<span class="name_suggestion">${(item.text.replace(re, `<i class="mark">$&</i>`))}</span><span class="email_suggestion_title"> <strong>Email :</strong></span><span class="email_suggestion"><strong></strong>${item.email.map(e=>(e.email.replace(re, `<i class="mark">$&</i>`))).join(',')}</span>`;
                else return `<span class="name_suggestion">${item.text}</span><span class="email_suggestion_title"> <strong>Email :</strong></span><span class="email_suggestion"><strong></strong>${item.email.map(e=>e.email).join(',')}</span>`;
            },

            filterConctats : function ( input, option ) {
                return true
            },

            handleChange : function ( value ) {
                this.form.contactId = value ; 
            },

            handleMore : async function () {

                console.log('NEXT MORE') ;
                return ; 
                this.form.contactPage++  ;
                this.fetching = true ; 
                this.loading_more = true ; 
                let [ e , s ] = await infusionsoft.moreContact( this.option.external.infusionsoft , this.contactSearch , this.form.contactPage , this.defaultContact ) ;
                this.contacts = this.infusionsoft.contacts ; 
                this.loading_more = false ; 
                this.fetching = false ; 
                return s ; 
            },

            handleSearch : function ( search ) {
                this.suggestionLongeur = 0 ; 
                this.form.contactPage = 1 ; 
                this.contactSearch = search ;
                this.suggestionLoadSearch = false ; 
                this.fetching = true ; 
                this.contacts = [] ; 
                clearTimeout( this.tempstemp )
                this.tempstemp = setTimeout( async () => {
                    clearTimeout( this.tempstemp )
                    let [ e , d ] =  await infusionsoft.allContact( this.option.external.infusionsoft , this.contactSearch , this.defaultContact ) ;
                    this.form.contactTotal = d.maxpage ;
                    this.contacts = this.infusionsoft.contacts ; 
                    this.fetching = false ; 
                    this.suggestionLoadSearch = true ; 
                }, 500 );
            }, 

            async init( ){
                //await infusionsoft.fetchContact( this.mobile.applications.infusionsoft ) ; 
                let [ err , contact ] = await infusionsoft.allContacts( this.mobile.applications.infusionsoft , this.contactSearch , 1 , this.defaultContact ) ; 
                this.contacts = contact ; 
                //chargement du permièer contacts 
                console.log( contact ) ;
            }

        },
		created(){
		  console.log('CHARGEMENT SEUELEMENT APRES UN VSHOW')
            this.init() ; 
		}
	}
</script>