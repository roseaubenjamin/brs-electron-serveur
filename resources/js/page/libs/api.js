export default async function api( endpoint , op = {} , isjson = true ) {
	let headers = { 
		'X-Requested-With' : 'XMLHttpRequest' , 
		'X-CSRF-TOKEN' : document.querySelector( 'meta[name="csrf-token"]' ).getAttribute('content') , 
	}
	if( isjson ){
		headers['Accept'] = 'application/json' ; 
		headers['Content-Type'] = 'application/json' ; 
	}
	if ( op.headers ) {
		op.headers = { ...op.headers , headers }
	}
	let url = `${window.APP_URL}${endpoint}` ;
  	let resp = await fetch( url , { 
  		headers , 
  		credentials : 'same-origin' , 
  		...op
  	})
   	let response = null ; 
  	if ( resp.ok ) { 
		try { response = await resp.json() ; } 
		catch(e) {
			console.log( e )
			return [true, null];
		}
    }else{ 
		try { response = await resp.json() ;} 
		catch(e) {
			console.log( e )
		  	return [true, null];
		}
	}
    return [null, response];
}