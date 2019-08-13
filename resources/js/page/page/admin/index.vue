<template>
    <div :style="{ marginLeft: 'auto', marginRight: 'auto', background: '#fff', padding: '24px', minHeight: '380px' , maxWidth : '992px' }">
        <a-row :gutter="12">
            <a-col>
                <h1>Page home du route</h1>
                <p>Backup de la base de donner</p>
                <p>Voire statistique serveur</p>
                <p>Excluse des utilisateur</p>
                <p>Envouier des notification a chaque utilisateur</p>
            </a-col>
        </a-row>
    </div>

</template>
<script>

    import application from '../../store/application' ; 
    import team from '../../store/team' ;  

    export default {
        props : [ ], 
        data(){
            return {
                
            }
        },
        computed: {
        
        },
        methods : { 

        },
        created(){ 

        }
    }
</script>