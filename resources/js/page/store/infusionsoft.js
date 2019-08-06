import api from '../libs/api' ; 
import store from './store' ; 

class infusionsoft extends store{
	constructor(){
		super();
		this.state = {
		}
	}

	async allMembres( id ){
		let [ err , { data } ] = await api( '/api/infusionsoft/membres/'+id ) ;
		if ( err ) 
			return [ err , null ]
		this.state.teams = [ ...data ]
		return [ null , this.state.teams ]
	}

	async allContacts( id , search , page = 1 , defaultContact = null ){
		let [ err , res ] = await api( `/api/infusionsoft/contacts/${id}?page=${page}${(search?'&search='+search:'')}${(defaultContact?'&default='+defaultContact:'')}` ) ;
		if ( err ) 
			return [ err , null ]
		return [ null , res ]
	}

	async fetchContact( id ){
		let err , data ; 
		do{
			[ err , { data } ] = await api( '/api/infusionsoft/fetchContact/'+id ) ;
			console.log( data ) ; 
		}while( !data || !data.success )
		if ( err ) 
			return [ err , null ]
		return [ null , this.state.teams ]
	}

} 

export default new infusionsoft() ;