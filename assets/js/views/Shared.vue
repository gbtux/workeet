<template>
    <div id="social">
        <v-container grid-list-xl fluid>
            <v-layout row class="align-center layout px-4 pt-4 app--page-header">
                <div class="page-header-left">
                    <h3 class="pr-3">Fichiers partagés</h3>
                </div>
            </v-layout>
        </v-container>
        <v-container grid-list-xl fluid>
            <v-layout row wrap>
                <v-flex lg12>
                    <v-data-table
                            :headers="headers"
                            :items="shared"
                            class="elevation-1"
                            v-model="selected"
                            item-key="id"
                            select-all
                            :rows-per-page-items="rowsPerPageItems"
                            :pagination.sync="pagination"
                    >
                        <template v-slot:items="props">
                            <td>
                                <v-checkbox v-model="props.selected" primary hide-details></v-checkbox>
                            </td>
                            <td>
                                <div class="fi fi-size-xs" :class="'fi'-props.item.extension">
                                    <div class="fi-content">{{ props.item.extension }}</div>
                                </div>
                            </td>
                            <td><a href="" style="text-decoration: none; color: inherit;" @click.prevent="showDetails(props.item.id)">{{ props.item.nom }}</a></td>
                            <td class="text-xs-right">
                                <v-icon style="cursor: pointer" @click="showFullscreen(props.item)">visibility</v-icon>
                                <a :href="getUrl(props.item.id)" style="text-decoration: none"><i class="v-icon v-icon--link material-icons theme--light">play_for_work</i></a>
                            </td>
                            <td class="text-xs-right">{{ getDate(props.item.dateCreation)}}</td>
                            <td class="text-xs-right">{{ getSize(props.item.size)}}</td>
                        </template>
                    </v-data-table>
                </v-flex>
                <v-navigation-drawer class="setting-drawer" temporary right v-model="rightDrawer" hide-overlay fixed width="500">
                    <DocumentDetails :id="currentSelected" :isShared="true" v-if="modeDrawer === 'showDetails'"></DocumentDetails>
                </v-navigation-drawer>
            </v-layout>
        </v-container>
    </div>
</template>

<script>

    import {mapGetters} from 'vuex'
    import moment from 'moment'
    import filesize from 'filesize'
    import DocumentDetails from "./DocumentDetails";
    import 'css-file-icons/build/css-file-icons.css'

    export default {
        name: "Shared",
        components: {
            DocumentDetails
        },
        data() {
            return {
                headers: [
                    {text: 'Type', value: 'extension', sortable: true, align: 'left', width: '10%'},
                    {text: 'Nom', value: 'name', sortable: true, align: 'left', width: '60%'},
                    {text: '', value: '', width: '10%'},
                    {text: 'Dernière modification', value: 'lastModification', sortable: true, width: '10%', align: 'right'},
                    {text: 'Taille', value: 'size', sortable: true, width: '10%', align: 'right'},
                ],
                selected: [],
                pagination: {
                    sortBy: 'dateCreation',
                    descending: true
                },
                rowsPerPageItems: [20,30,40],
                modeDrawer: '',
                rightDrawer: false,
                currentSelected: ''
            }
        },
        computed: {
            ...mapGetters('repertoire', {shared: 'shared'}),
        },
        mounted() {
            this.$store.dispatch('repertoire/loadShared')
        },
        methods: {
            getSize(size) {
                return filesize(size)
            },
            getDate(adate) {
                return moment(adate).locale('fr').fromNow()
            },
            getUrl(id) {
                return url_api + '/documents/' + id + '/download'
            },
            showDetails(id) {
                this.currentSelected = id
                this.modeDrawer = 'showDetails'
                this.rightDrawer = true
            },
        }
    }
</script>

<style scoped>

</style>