<template>
    <v-flex lg12>
        <h2 class="align-center">{{ document.nom }}</h2>
        <v-tabs v-model="tabActive" color="primary" dark class="mt-3">
            <v-tab ripple>Details</v-tab>
            <v-tab ripple>Partage</v-tab>
            <v-tab ripple>Historique</v-tab>
            <v-tab-item>
                <v-card flat>
                    <v-card-text>
                        <v-flex md12>
                            <object :data="pdfUrl" type="application/pdf" width="100%" height="400px" ></object>
                        </v-flex>
                    </v-card-text>
                </v-card>
            </v-tab-item>
            <v-tab-item>
                <v-card flat>
                    <v-card-text>partage here</v-card-text>
                </v-card>
            </v-tab-item>
            <v-tab-item>
                <v-card flat>
                    <v-card-text>historique here</v-card-text>
                </v-card>
            </v-tab-item>
        </v-tabs>
    </v-flex>
</template>

<script>

    import {mapGetters} from 'vuex'
    import _ from 'lodash'

    export default {
        name: "DocumentDetails",
        props: {
            id: String
        },
        data() {
            return {
                tabActive: null
            }
        },
        computed: {
            ...mapGetters('repertoire', {document: 'document'}),
            pdfUrl() {
                return url_api + '/documents/' + this.id + "/pdf#toolbar=0&navpanes=0&scrollbar=0&page=0&zoom=scale&view=Fit"
            }
        },
        watch: {
            'id' : 'loadDocument'
        },
        mounted () {
            this.$store.dispatch('repertoire/loadDocument', {id: this.id})
        },
        methods: {
            loadDocument() {
                this.$store.dispatch('repertoire/loadDocument', {id: this.id})
            }
        }
    }
</script>

<style scoped>

</style>