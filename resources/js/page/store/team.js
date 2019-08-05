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

} 

export default new team() ;