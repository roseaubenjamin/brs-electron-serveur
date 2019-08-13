import placedata from './placedata' ; 

export default function getForm ( form ){
	let body = [] ; 
    let doemotion = form.demotionnelle
    body = [ ...body , { type : 'text' , name : 'demotionnelle' , value : doemotion } ]
    let autre = form.autre 
    body = [ ...body , { type : 'text' , name : 'autre' , value : autre } ]
    let comment = form.comment 
    body = [ ...body , { type : 'text' , name : 'comment' , value : comment } ]
    let vitesse_closing_select = form.vitesseclosing 
    body = [ ...body , { type : 'text' , name : 'vitesseclosing' , value : vitesse_closing_select } ]
    let soncas_select = form.socas 
    body = [ ...body , { type : 'text' , name : 'socas' , value : ( soncas_select?soncas_select.join(','):'' ) } ]
    let produit_select = form.produit 
    body = [ ...body , { type : 'text' , name : 'produit' , value : produit_select } ]
    let commercial_autre = form.commercial_autre 
    body = [ ...body , { type : 'text' , name : 'commercial_autre' , value : commercial_autre } ]
    let commercial = form.commercial  
    body = [ ...body , { type : 'text' , name : 'commercial' , value : commercial } ]
    let sav_autre = form.sav_autre 
    body = [ ...body , { type : 'text' , name : 'sav_autre' , value : sav_autre } ]
    let sav = form.sav 
    body = [ ...body , { type : 'text' , name : 'sav' , value : sav } ]
    let comptabilite_autre = form.comptabilite_autre 
    body = [ ...body , { type : 'text' , name : 'comptabilite_autre' , value : comptabilite_autre } ]
    let comptabilite = form.comptabilite 
    body = [ ...body , { type : 'text' , name : 'comptabilite' , value : comptabilite } ]
    let categorie_select = form.categorie 
    body = [ ...body , { type : 'text' , name : 'categorie' , value : categorie_select } ]
    body = [ ...body , { type : 'text' , name : 'plaisir' , value : form.plaisir } ]
    body = [ ...body , { type : 'text' , name : 'motivation' , value : form.motivation } ]
    body = [ ...body , { type : 'text' , name : 'objections' , value : form.objections } ]
    return body ;
}