import api from '../libs/api' ; 
import store from './store' ; 

class form extends store{

	constructor(){
		super();
		this.state = {
			lists : []
		}
	}

	/*
	 * Récupération des option 
	*/
	async find( id ){
		let [ err , { data } ] = await api( '/api/form/'+id )  ; 
		if ( err ) 
			return [ err , null ]
		return [ null , data ]
	}

	async create( id , body ){
		console.log( body )
		let [ err , { data } ] = await api( '/api/form/'+id , {
			method : 'POST' , 
			body : JSON.stringify({ forms : JSON.stringify(body) })  
		})  ; 
		if ( err ) 
			return [ err , null ]
		return [ null , data ]
	}

} 

export default new form() ;