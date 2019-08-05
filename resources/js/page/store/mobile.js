import api from '../libs/api' ; 
import store from './store' ; 

class option extends store{

	constructor(){
		super();
		this.state = {
			lists : [] , 
			assigned : { trello : [] , infusionsoft : [] } , 
			priority : [] , 
			applications : { trello : 19 , infusionsoft : 21 } , 
			//les formulaires d'édition de trouve ici
		}
		this.form = {
            categorie : 'comptabilite' , 
            comptabilite : 'Envoyer document' ,
            comptabilite_autre : '' ,
            sav : 'Installation logiciel' , 
            sav_autre : '' , 
            commercial : '' , 
            commercial_autre : '' , 
            produit : '' , 
            vitesseclosing : 0 , 
            socas : [] ,  
            comment : '' , 
            demotionnelle : '' ,
            autre : '' , 
            //
            pour : '' ,
            prioriter : '' ,
            date : null , 
            contactId : null , 
            contactSearch : '' , 
            contactPage : 1 , 
            contactTotal : 1 , 
            note : null , 
            //note vocal id 
            NOTEID : null , 
            plaisir : '' ,
            motivation : '' ,
            objections : '' ,
        }
	}

	/*
	 * Récupération des option 
	*/
	async create(){
		/*$data = array(
            'attachable_id' 	=> 1 , 
            'attachable_type' 	=> 'App\User' , 
            'file' 	=> $this->uploadFile('google.txt') , 
        ); */
        let { name , type } = option ; 
        let body = { name , type } ;
		let [ err , { data } ] = await api( '/api/mobile',{
			method: "POST",
			body : JSON.stringify({trello:1,infusionsoft:1}),
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

} 

export default new option() ;