export default async function api( endpoint , op = {} ) {
	let headers = { 
		'X-Requested-With' : 'XMLHttpRequest' , 
		'Accept' : 'application/json' , 
		'Content-Type' : 'application/json' ,  
		'X-CSRF-TOKEN' : document.querySelector( 'meta[name="csrf-token"]' ).getAttribute('content') , 
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