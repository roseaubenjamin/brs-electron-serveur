import api from '../libs/api' ; 
import store from './store' ; 
import lang from '../libs/lang' ; 

class application extends store{

	constructor(){
		super();
		this.state = {
			applications : [] , 
			trellos : [] , 
			infusionsofts : [] , 
			item : {} , 
		}
	}

	/*
	 * Add borad a l'application trello
	*/
	async update( id , body ){
		let [ err , { data } ] = await api( '/api/application/'+id ,{
			method: "PUT",
			body : JSON.stringify(body),
		}) ;
        if ( err ) 
			return [ err , null ]
		this.addItem( data ) ; 
       	return [ null , true ]
	}

	async destroyApplication( id ){
        let [ err , { data } ] = await api( '/api/application/delete/'+id , 'DELETE' ) ;
        if ( err ) 
			return [ err , null ]
       	return [ null , true ]
	}

	async addApplication( option ){
		let { name , type } = option ; 
        let body = { name , type } ;
		let [ err , { data } ] = await api( '/api/application',{
			method: "POST",
			body : JSON.stringify(body),
		}) ;
		if ( err ) 
			return [ err , null ]
		let win = window.open( data , '_blank');
        return win.focus();
	}

	/*
 	 * Reresh token manuelement 
	*/
	async reAuthorize( id ){
		let [ err , { data } ] = await api( `/api/application/reauthorize/all/${id}` ) ;
		if ( err ) 
			return [ err , null ]
		let win = window.open( data, '_blank');
        return win.focus();
	}

	/*
	 * Récupération de tout les applications 
	*/
	async allApplication(){
		let [ err , { data } ] = await api( '/api/application' ) ;
		if ( err ) 
			return [ err , null ]
		this.state.applications = [ ...data ]
		this.trellos()
		this.infusionsofts()
		return [ null , this.state.applications ]
	}

	/*
	 * Focus sur une application en particulier pour récupérer ces donners 
	*/
	async itemApplication( id ){
		let [ err , { data } ] = await api( '/api/application/'+id ) ;
		if ( err ) 
			return [ err , null ]
		this.addItem( data ) ; 
		return [ null , this.state.item ]
	}

	addItem( data ){
		let op = Object.assign( {},{ ...data } ) ;
		this.state.item[ data.id ] =  Object.assign( {},{ ...this.state.item , ...op } ) ; 
	}

	async itemApplicationByUnique( unique ){
		let [ err , { data } ] = await api( '/api/application/unique/'+unique ) ;
		if ( err ) 
			return [ err , null ]
		this.addItem( data ) ; 
		return [ null , this.state.item ]
	}

	/*
	 * Liste de tout les applications trellos 
	*/
	trellos(){
		if ( this.state.applications.length ) {
			return this.state.trellos = this.state.applications.filter( e => e.type == 'trello' )
		}
		return [] ; 
	}

	/*
	 * Liste de tout les applications infusionsoft  
	*/
	infusionsofts(){
		if ( this.state.applications.length ) {
			return this.state.infusionsofts = this.state.applications.filter( e => e.type == 'infusionsoft' )
		}
		return [] ; 
	}
} 

export default new application() ;