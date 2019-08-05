<template>
    <div>
        <a-form-item :label="$lang('Categorie ')">
            <a-select defaultValue="comptabilite" v-model="form.categorie">
                <a-select-option v-for="item in placedata.categorieArray" :key="item.value" :value="item.value">
                    {{item.key}}
                </a-select-option>
            </a-select> 
        </a-form-item>
        <a-form-item v-if="form.categorie=='comptabilite'" :label="$lang('Comptabilite')">
            <a-select v-model="form.comptabilite">
                <a-select-option v-for="item in placedata.comptabiliteArray" :key="item.value" :value="item.value">
                    {{item.key}}
                </a-select-option>
            </a-select> 
            <a-textarea v-if="form.comptabilite=='_____'" v-model="form.comptabilite_autre" :placeholder="$lang('Text ici')" :rows="4"/>
        </a-form-item>
        <a-form-item v-if="form.categorie=='sav'" :label="$lang('SAV')">
            <a-select v-model="form.sav">
                <a-select-option v-for="item in placedata.savArray" :key="item.value" :value="item.value">
                    {{item.key}}
                </a-select-option>
            </a-select> 
            <a-textarea v-if="form.sav=='_____'" v-model="form.sav_autre" :placeholder="$lang('Text ici')" :rows="4"/>
        </a-form-item>
        <a-form-item v-if="form.categorie=='commercial'" :label="$lang('Commercial')">
            <a-select v-model="form.commercial">
                <a-select-option v-for="item in placedata.commercialArray" :key="item.value" :value="item.value">
                    {{item.key}}
                </a-select-option>
            </a-select> 
            <a-textarea v-if="form.commercial=='_____'" v-model="form.commercial_autre" :placeholder="$lang('Text ici')" :rows="4"/>
        </a-form-item>
        <a-form-item v-if="form.categorie=='autre'" :label="$lang('Autre')">
            <a-textarea v-model="form.autre" :placeholder="$lang('Autre')" :rows="4"/>
        </a-form-item>
        
        <a-form-item :label="$lang('Produit')">
            <a-select v-model="form.produit">
                <a-select-option v-for="item in placedata.produitArray" :key="item.value" :value="item.value">
                    {{item.key}}
                </a-select-option>
            </a-select> 
        </a-form-item>
        <a-form-item v-if="form.categorie=='commercial'" :label="$lang('Vitesse Closing')">
            <a-select v-model="form.vitesseclosing">
                <a-select-option v-for="item in placedata.vitesseclosingArray" :key="item.value" :value="item.value">
                    {{item.key}}
                </a-select-option>
            </a-select> 
        </a-form-item>
        <a-form-item v-if="form.categorie=='commercial'" :label="$lang('Soncas')">
            <a-select mode="multiple" v-model="form.socas">
                <a-select-option v-for="item in placedata.soncasArray" :key="item.value" :value="item.value">
                    {{item.key}}
                </a-select-option>
            </a-select> 
        </a-form-item>
        <a-form-item :label="$lang('Commentaire')">
            <a-textarea :placeholder="$lang('commentaire')" v-model="form.comment" :rows="4"/> 
        </a-form-item>
        <a-form-item v-if="form.categorie=='commercial'" :label="$lang('Douleur émotionnelle')">
            <a-textarea :placeholder="$lang('douleur émotionnelle')" v-model="form.demotionnelle" :rows="4"/> 
        </a-form-item>
        <a-form-item v-if="form.categorie=='commercial'" :label="$lang('Plaisir')">
            <a-textarea :placeholder="$lang('plaisir')" v-model="form.plaisir" :rows="4"/> 
        </a-form-item>
        <a-form-item v-if="form.categorie=='commercial'" :label="$lang('Motivation')">
            <a-input-number style="width: 100%;" :min="0" :max="10" placeholder="Motivation 0 à 10" v-model="form.motivation" />
        </a-form-item>
        <a-form-item v-if="form.categorie=='commercial'" :label="$lang('Objections')">
            <a-textarea :placeholder="$lang('objections')" v-model="form.objections" :rows="4"/> 
        </a-form-item>
    </div>
</template>
<script>
	
    import application from '../store/application' ; 
    import mobile from '../store/mobile' ; 
    import placedata from '../libs/placedata' ; 
	
	export default {

		props : [], 

		data(){
            return {
                placedata : placedata() , 
                form : mobile.form ,
            }
        },
        methods : {

            formateForm : function () {
                let body = [] ; 
                let doemotion = this.form.demotionnelle
                body = [ ...body , { type : 'text' , name : 'demotionnelle' , value : doemotion } ]
                let autre = this.form.autre 
                body = [ ...body , { type : 'text' , name : 'autre' , value : autre } ]
                let comment = this.form.comment 
                body = [ ...body , { type : 'text' , name : 'comment' , value : comment } ]
                let vitesse_closing_select = this.form.vitesseclosing 
                body = [ ...body , { type : 'text' , name : 'vitesseclosing' , value : vitesse_closing_select } ]
                let soncas_select = this.form.socas 
                body = [ ...body , { type : 'text' , name : 'socas' , value : ( soncas_select?soncas_select.join(','):'' ) } ]
                let produit_select = this.form.produit 
                body = [ ...body , { type : 'text' , name : 'produit' , value : produit_select } ]
                let commercial_autre = this.form.commercial_autre 
                body = [ ...body , { type : 'text' , name : 'commercial_autre' , value : commercial_autre } ]
                let commercial = this.form.commercial  
                body = [ ...body , { type : 'text' , name : 'commercial' , value : commercial } ]
                let sav_autre = this.form.sav_autre 
                body = [ ...body , { type : 'text' , name : 'sav_autre' , value : sav_autre } ]
                let sav = this.form.sav 
                body = [ ...body , { type : 'text' , name : 'sav' , value : sav } ]
                let comptabilite_autre = this.form.comptabilite_autre 
                body = [ ...body , { type : 'text' , name : 'comptabilite_autre' , value : comptabilite_autre } ]
                let comptabilite = this.form.comptabilite 
                body = [ ...body , { type : 'text' , name : 'comptabilite' , value : comptabilite } ]
                let categorie_select = this.form.categorie 
                body = [ ...body , { type : 'text' , name : 'categorie' , value : categorie_select } ]
                body = [ ...body , { type : 'text' , name : 'plaisir' , value : this.form.plaisir } ]
                body = [ ...body , { type : 'text' , name : 'motivation' , value : this.form.motivation } ]
                body = [ ...body , { type : 'text' , name : 'objections' , value : this.form.objections } ]
                return body ;
            },

        },
		created(){
		
		}
	}
</script>