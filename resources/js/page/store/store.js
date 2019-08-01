export default class store{

	constructor(){
		this.event = {}
	}

	$on( event , cbl ){
		if ( this.event[event] ) 
			this.event[event].push( cbl )
		else{
			this.event[event] = [] ; 
			this.event[event].push( cbl )
		}
	}

	$emit( event , data ){
		if ( this.event[event] ) {
			this.event[event].forEach(function( item ){
				item( data )
			})
		}
	}

} 