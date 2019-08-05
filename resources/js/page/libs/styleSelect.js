export default async function styleSelect( data ) {
	if ( data.length == 2 || data.length == 1 ) {
        return { width : '50%' }
    }else if ( data.length == 3 ) {
        return { width : '33.33333333333333333333%' }
    }else if ( data.length > 3 && data.length< 6) {
        return { width : '50%' }
    }else if ( data.length >= 6 ) {
        return { width : '33.33333333333333333333%' }
    }
}