import api from '../libs/api' ; 
import store from './store' ; 

class note extends store{

	constructor(){
		super();
		this.state = {
			lists : []
		}
	}

	/*
	 * Récupération des option 
	*/
	async find( ob ){
		let [ err , { data } ] = await api( '/api/note?'+ super.$toparams( ob ) )  ; 
		if ( err ) 
			return [ err , null ]
		return [ null , data ]
	}

} 

export default new note() ;