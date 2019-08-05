import placedata from './placedata' ; 

export default function formateDescription ( form ){
	let text = '' ; 
    for( let { name , value } of form ){
        if ( name == 'soncas-select' && value !== '') {
            let val = placedata('soncasArray')  ;
            text += 'SONCAS : '+"\n"
            for( let pitem of val ){
                pitem.value==value?text += ` - ${pitem.key} `+"\n":'';
            }
        }else if( name == 'vitesse-closing-select' && value ){
            text += 'Vitesse Closing : '+"\n";
            let ex = value.split(',') ; 
            let val = placedata('vitesseclosingArray')  ;
            for( let i of ex ){
                for( let pitem of val ){
                    pitem.value==i?text += ` - ${pitem.key}</br>`:'';
                }
            }
        }else if( name == 'doemotion' && value !== ''){
            text += 'Douleur émotionnelle : '+"\n"
            text += ` - ${value}`+"\n"
        }else if( name == 'comment' && value !== ''){
            text += 'Commentaire : '+"\n"
            text += ` - ${value} `+"\n"
        }
    }
    return text
}