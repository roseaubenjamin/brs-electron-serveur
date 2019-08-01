import api from '../libs/api' ; 
import store from './store' ; 
import Echo from 'laravel-echo' ; 

class bot extends store{

	constructor(){
		super();
		this.stade = {
			auth : {}
		}
	}

	socketConnect(){
		//connection soket 
		new Echo({
			broadcaster : 'socket.io' , 
			host : window.location.hostname + ':6001'
		})
		//.channel('office-dashboard')
		.private(`App.User.${this.stade.auth.id}`)
		.listen('newMessage',function ( e ) {
			console.log( e ) ; 
		})
	}

	/*
 	 * Ajouter le Status Online a votre compte 
	*/
	async addStatusUser( bot ){
		let [ err , res ] = await api('/api/status/online/'+( bot?bot:'' )) ;
		console.log( res )
	}

	/*
	 * Ajouter le status offline a votre compte 
	*/
	async remStatusUser( bot ){
		let [ err , res ] = await api('/api/status/offline/'+( bot )) ;
		console.log( res )
	}

	/*
	 * Inscrire sur un status d'un utilisateur pour avoire des news si cette utilisateur 
	 * se connecte ou se déconnect du system 
	*/
	async attacheStatusUser( bot ){
		let [ err , res ] = await api('/api/status/subscribe/'+( bot ) ) ;
		console.log( res )
	}
	
	/*
	 * Désabonement de votre socket au socket de l'utilisateur 
	*/
	async dettacheStatusUser( bot ){
		let [ err , res ] = await api('status/unsubscribe/{bot}/') ;
		console.log( res )
	}

	async addUser( user ){
		if ( this.stade && this.stade.auth && this.stade.auth.id ) return !0 ; 
		this.stade.auth = Object.assign({} , {...user})
		this.socketConnect() ; 
		return !1 ; 
	}
} 

export default new bot() ;