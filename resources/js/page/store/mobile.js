import api from '../libs/api' ; 
import store from './store' ; 

import formateDescription from '../libs/formateDescription' ; 
import formateTitle from '../libs/formateTitle' ; 
import getForm from '../libs/getForm' ; 


class option extends store{

	constructor(){
		super();
		this.state = {
			lists : [] , 
			assigned : { trello : [] , infusionsoft : [] } , 
			priority : [] , 
			applications : { trello : null , infusionsoft : null } , 
			//les formulaires d'édition de trouve ici
		}
		this.form = {

            categorie : 'comptabilite' , 
            comptabilite : '' ,
            comptabilite_autre : '' ,
            sav : '' , 
            sav_autre : '' , 
            commercial : '' , 
            commercial_autre : '' , 
            produit : '' , 
            vitesseclosing : 0 , 
            socas : [] ,  
            comment : '' , 
            demotionnelle : '' ,
            autre : '' , 
            plaisir : '' ,
            motivation : '' ,
            objections : '' ,

            assigned : '' ,
            prioriter : '' ,
            date : null , 
            contactId : null , 
            contactSearch : '' , 
            note : null , 
            //note vocal id 
            NOTEID : null , 
            
        }
	}

	/*
	 * Récupération des option 
	*/
	async create( apps ){
        let { name , type } = option ; 
        let body = { name , type } ;
		let [ err , { data } ] = await api( '/api/mobile',{
			method: "POST",
			body : JSON.stringify( apps ),
		}) ;
		if ( err ) 
			return [ err , null ]
		console.log( data )
	}

	async allMobile(){
		let [ err , { data } ] = await api( '/api/mobile' ) ;
		if ( err ) 
			return [ err , null ]
		this.state.lists = [ ...data ]
		return [ null , this.state.lists ]
	}

	async destroyMobile( id ){
		let [ err , { data } ] = await api( '/api/mobile/'+id , {
			method : 'DELETE' 
		}) ;
		if ( err ) 
			return [ err , null ]
		return [ null , data ]
	}

	async find( id ){
		let [ err , { data } ] = await api( '/api/mobile/'+id ) ;
		if ( err ) 
			return [ err , null ]
		console.log( data )
		this.state.applications = Object.assign({},{
			infusionsoft : parseInt( data.infusionsoft ) , 
			trello : parseInt( data.trello ) , 
		});
		return [ null , data ]
	}

	async findAssigned( id , type ){
		let [ err , { data } ] = await api( '/api/mobile/assigned/'+id +'?type='+type ) ;
		if ( err ) 
			return [ err , null ]
		this.state.assigned[type] = [ ...data ]
		return [ null , this.state.assigned[type] ]
	}

	async assigned( id , body , type = 'trello' ){
		let [ err , { data } ] = await api( '/api/mobile/assigned/'+id+'?type='+type , {
			method : 'POST' , 
			body : JSON.stringify( body )
		}) ;
		if ( err ) 
			return [ err , null ]
		console.log( data )
		return [ null , true ]
	}

	async deassigned( id ){
		let [ err , { data } ] = await api( '/api/mobile/assigned/'+id , {
			method : 'DELETE' , 
		}) ;
		if ( err ) 
			return [ err , null ]
		console.log( data )
		return [ null , true ]
	}

	async priorty( id , body ){
		let [ err , { data } ] = await api( '/api/mobile/priorty/'+id , {
			method : 'POST' , 
			body : JSON.stringify( body )
		}) ;
		if ( err ) 
			return [ err , null ]
		console.log( data )
		return [ null , data ]
	}

	async findPriorty( id ){
		let [ err , { data } ] = await api( '/api/mobile/priorty/'+id) ;
		if ( err ) 
			return [ err , null ]
		console.log( data )
		this.state.priority = [ ...data ]
		return [ null , this.state.priority ]
	}

	async depriorty( id ){
		let [ err , { data } ] = await api( '/api/mobile/priorty/'+id , {
			method : 'DELETE' 
		}) ;
		if ( err ) 
			return [ err , null ]
		return [ null , data ]
	}

	async destroyOption( id ){
		let [ err , { data } ] = await api( '/api/mobile/option/'+id , {
			method : 'DELETE' 
		}) ;
		if ( err ) 
			return [ err , null ]
		return [ null , data ]

	}

	//récupération des mobiles 
	async findMobileListeFromUnique( unique ){
		let [ err , { data } ] = await api( '/api/mobile/unique/'+unique ) ;
		if ( err ) 
			return [ err , null ]
		return [ null , data ]
	}

	//ici on veut faire une duplication ou de copy de note
	//Trello vers INFUSIONSOFT seulement 
	async duplicate( duop ){
		console.log( duop )
		//récupération des informations de cette note 
		//si me note n'existe pas alors on affiche une erreur 
	}

	async vocal( mobile , compte , duplicate ){ 

		if ( !this.form.note && !duplicate ) 
            return [ 'mobile error note' , null ] ; 
		
		if( compte == 'infusionsoft' && !this.form.contactId ){
            return [ 'mobile error contact id' , null ] ; 
		} 

		let formData = new FormData();
		formData.append('compte', compte );
		formData.append('assigned', this.form.assigned );
		formData.append('prioriter', this.form.prioriter );
		formData.append('date', this.form.date );
		formData.append('unique', this.form.NOTEID );
		formData.append('contact_id', this.form.contactId );
		formData.append('file', this.form.note );
		formData.append('title', formateTitle( this.form ) );
		formData.append('duplicate', duplicate );
		formData.append('description', formateDescription( getForm( this.form ) ) );

        //création de note de tache ou de card en native
        let [ err , { data } ] = await api( '/api/mobile/vocal/'+mobile , {
			method : 'POST' , 
			body : formData
		} , false ) ;

        //ici pas de note crée car il y a une erreur 
		if ( err || !data || (data && !data.id) ) 
			return [ err ? err : data , null ]
		return [ null , data ] ;

	}

	async update( mobile , compte ){

		console.log( mobile , compte )
		
		if ( !this.form.note ) 
            return [ 'mobile error note' , null ] ; 
		
		let formData = new FormData();
		formData.append('file', this.form.note );

        //création de note de tache ou de card en native
        let [ err , { data } ] = await api( '/api/note/'+mobile , {
			method : 'POST' , 
			body : formData
		} , false ) ;

        //ici pas de note crée car il y a une erreur 
		if ( err || !data || (data && !data.id) ) 
			return [ err , null ]
		return [ null , data ] ;

	}

} 

export default new option() ;