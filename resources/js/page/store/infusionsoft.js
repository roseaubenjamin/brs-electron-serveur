import api from '../libs/api' ; 
import store from './store' ; 

class infusionsoft extends store{
	constructor(){
		super();
		this.state = {
			teams : []
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

	async fetchContact( id , callback ){
		let err , data ; 
		do{
			[ err , { data } ] = await api( '/api/infusionsoft/fetchContact/'+id ) ;
			callback?callback( data ):'' ; 
		}while( !data || !data.success )
		if ( err ) 
			return [ err , null ]
		return [ null , this.state.teams ]
	}

	//récupération des applications qui n'on pas de contact id 
	//ou qui a de nouvelle contact 
	async findContactSynhronisation(){
		let [ err , { data } ] = await api( '/api/infusionsoft/check/membres' ) ;
		if ( err ) 
			return [ err , null ]
		return [ null , data ]
	}

	async findNote( app , id ){
		let [ err , { data } ] = await api( `/api/infusionsoft/note/${app}/${id}` ) ;
		if ( err ) 
			return [ err , null ]
		return [ null , data ]
	}

	async findTask( app , id ){
		let [ err , { data } ] = await api( `/api/infusionsoft/task/${app}/${id}` ) ;
		if ( err ) 
			return [ err , null ]
		return [ null , data ]
	}

} 

export default new infusionsoft() ;