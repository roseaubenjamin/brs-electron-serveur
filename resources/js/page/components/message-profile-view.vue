<template>
	<div style="width: 100%; height: 100%; padding: 1rem ; ">
        <a-spin :spinning="!attachedRedy" tip="Loading...">
            <div class="spin-content" v-if="botStore.focus && botStore.focus[this.bot] ">
                <img
                    alt="example"
                    style="max-width: 100%;"
                    :src="botStore.focus[this.bot].avatar?botStore.focus[this.bot].avatar:baseUrl('/img/default.jpg')" 
                    slot="cover"
                    default.jpg
                />
                <p><strong>{{botStore.focus[this.bot].psoeudo}}</strong></p>
            </div>
        </a-spin>
    </div>
</template>
<script>

    import botStore  from '../store/bot' ; 
    import baseUrl from '../libs/baseUrl' ; 

	export default {
        props : ['bot'], 
		data() {
		    return {
                botStore : botStore.stade , 
                attachedRedy : false , 
		    }
		},
		watch : {
			bot : function(){
				this.init() ; 
			}
		},
		methods: {
			baseUrl , 
		    init : async function ( ) {
		    	console.log('this.botthis.botthis.bot' , this.bot )
		    	this.attachedRedy = false ; 
                //on fait la récupération des informations 
                await botStore.findOne( this.bot ) ; 
                this.attachedRedy = true ; 
                //on fait aussi la récupération des conversation 
		    }
		},
		created(){
			this.init() ; 
        }
	}
</script>