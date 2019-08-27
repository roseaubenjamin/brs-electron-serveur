import placedata from './placedata' ; 

export default function formateTitle ( form ){
    let def = placedata('categorieArray' , form.categorie) ; 
	let product = form.produit ; 
	let info = '' ; 
	let temps = '' ; 
	if( form.categorie !== 'autre' ){
		if( form[ form.categorie] == '_____' ){
			temps = form[ form.categorie+"_autre"]
		}else{
			temps = form[ form.categorie]
		}
	}else{
		temps = form.autre 
	}
	if( temps ) info = ' - ' + temps ;
    let text = def +' '+ product +' '+ info ; 
    return text ; 

}