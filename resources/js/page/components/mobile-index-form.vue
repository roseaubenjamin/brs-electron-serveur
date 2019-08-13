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
    import form from '../store/form' ; 
    import placedata from '../libs/placedata' ; 
	import formateDescription from '../libs/formateDescription' ; 
    import getForm from '../libs/getForm' ; 

	export default {

		props : [], 

		data(){
            return {
                placedata : placedata() , 
                form : mobile.form ,
            }
        },
		created(){
		    //Récupération des forms par défault de l'utilisateur 
            this.on('placeforme', async ( id ) => {
                if( id ){
                    let [ err , f ] = await form.find( id ) ; 
                    console.log( f )
                }
            })

            this.on('updateforme', async ( id ) => {
                console.log( "lancer une evenement pour crée les formulaire " , id ) ; 
                console.log( post )
            })
		}
	}
</script>