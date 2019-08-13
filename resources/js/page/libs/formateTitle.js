import placedata from './placedata' ; 

export default function formateTitle ( form ){
	let text = '' ; 
    return placedata('categorieArray' , form.categorie)+" "+form.produit+" -"+( form.categorie !== 'autre' ? ( form[ form.categorie]=='_____'?  form[ form.categorie+"_autre"] :  form[ form.categorie]) : form.autre )
}