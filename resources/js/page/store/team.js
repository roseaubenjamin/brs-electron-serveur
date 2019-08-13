import api from '../libs/api' ; 
import store from './store' ; 
import Echo from 'laravel-echo' ; 

class team extends store{

	constructor(){
		super();
		this.state = {
			lists : []
		}
	}

	async all( id ){
		let [ err , { data } ] = await api( '/api/team/'+ id )  ; 
		if ( err ) 
			return [ err , null ]
		this.state.lists = [ ...data ]
		return [ null , this.state.lists ]
	}

	/*
	 * Récupération de mon info sur le teamp de l'application en pramètre 
	*/
	async info( id ){
		let [ err , { data } ] = await api( '/api/team/info/'+ id )  ; 
		if ( err ) 
			return [ err , null ]
		return [ false , data ]
	}

} 

export default new team() ;