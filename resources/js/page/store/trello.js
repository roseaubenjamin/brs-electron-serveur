import api from '../libs/api' ; 
import store from './store' ; 

class trello extends store{

	constructor(){
		super();
		this.state = {
			boards : [] , 
			lists : [] , 
			labels : [] , 
			teams : [] , 
		}
	}

	/*
 	 * Récupèration de tout les boards de trello 
	*/
	async allBoard( id ){
		let [ err , { data } ] = await api( '/api/trello/boards/'+ id ) ;
		if ( err ) 
			return [ err , null ]
		console.log( data ) ; 
		this.state.boards = [ ...this.state.boards , ...data ] 
		return [ null , this.state.boards ]
	}

	/*
	 * Récupération de tout les label de trello 
	*/
	async allList( id ){
		let [ err , { data } ] = await api( '/api/trello/lists/'+id ) ;
		if ( err ) 
			return [ err , null ]
		this.state.lists = [ ...data ]
		return [ null , this.state.lists ]
	}

	async allMembres( id ){
		let [ err , { data } ] = await api( '/api/trello/membres/'+id ) ;
		if ( err ) 
			return [ err , null ]
		this.state.teams = [ ...data ]
		return [ null , this.state.teams ]
	}

	/*
	 * Récupération de label de trello  
	*/
	async allLabel( id ){
		let [ err , { data } ] = await api( '/api/trello/labels/'+id ) ;
		if ( err ) 
			return [ err , null ]
		this.state.labels = [ ...data ]
		return [ null , this.state.labels ]
	}

	/*
	 * Ajoute de card dans trello
	*/
	async card( body ){
		console.log( body )
		let [ err , { data } ] = await api( '/api/trello/card' , 'POST' , body  ) ;
		if ( err ) 
			return [ err , null ]
		return [ null , data ]
	}

	async cardUpdate( body , update ){
		console.log( 'cardUpdate' , body )
		let [ err , { data } ] = await api( '/api/trello/card' , 'PUT' , body  ) ;
		if ( err ) 
			return [ err , null ]
		return [ null , data ]
	}

	async itemCard( id , appId ){
		let [ err , { data } ] = await api( '/api/trello/card/' + id  + '/?appId='+appId )  ; 
		if ( err ) 
			return [ err , null ]
		return  [ err , data ]
	}

} 

export default new trello() ;