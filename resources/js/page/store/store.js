export default class store{

	constructor(){
		this.event = {}
	}

	//converstion object en paramètre
	$toparams( obj ){
		var str = "";
		for (var key in obj) {
		    if (str != "") {
		        str += "&";
		    }
		    str += key + "=" + encodeURIComponent(obj[key]);
		}
		return str ;
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