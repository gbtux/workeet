<template>
    <v-card id="create">
        <v-container grid-list-xl fluid>
            <v-layout row wrap>
                <v-flex sm12>
                    <h3>{{ repertoire.nom }}</h3>
                </v-flex>
                <v-flex lg12>
                    <v-data-table
                            :headers="headers"
                            :items="repertoire.documents"
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
                            <td><img class="img-fluid" :src="getImage(props.item.extension)" style="max-height: 70%"></td>
                            <td><a href="" style="text-decoration: none; color: inherit;" @click.prevent="showDetails(props.item.id)">{{ props.item.nom }}</a></td>
                            <td class="text-xs-right">
                                <v-icon>visibility</v-icon>
                                <v-icon>share</v-icon>
                                <v-icon style="cursor: pointer" @click="alert('test')">play_for_work</v-icon>
                            </td>
                            <td class="text-xs-right">{{ getDate(props.item.dateCreation)}}</td>
                            <td class="text-xs-right">{{ getSize(props.item.size)}}</td>
                        </template>
                    </v-data-table>
                </v-flex>
                <v-navigation-drawer class="setting-drawer" temporary right v-model="rightDrawer" hide-overlay fixed width="500">
                    <DocumentDetails :id="currentSelected" v-if="modeDrawer === 'showDetails'"></DocumentDetails>
                </v-navigation-drawer>
            </v-layout>
        </v-container>
        <v-speed-dial v-model="fab" top right direction="bottom" transition="slide-y-reverse-transition">
            <template v-slot:activator>
                <v-btn v-model="fab" color="blue darken-2" dark fab>
                    <v-icon>add</v-icon>
                    <v-icon>close</v-icon>
                </v-btn>
            </template>
            <v-btn fab dark small color="green" @click.stop="uploadDialog = true">
                <v-icon>cloud_upload</v-icon>
            </v-btn>
            <v-btn fab dark small color="indigo">
                <v-icon>create_new_folder</v-icon>
            </v-btn>
            <v-btn fab dark small color="red">
                <v-icon>delete</v-icon>
            </v-btn>
        </v-speed-dial>
        <v-dialog v-model="uploadDialog" max-width="600">
            <v-card>
                <v-card-title class="headline">Envoyer un fichier</v-card-title>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-flex xs12>
                                <vue-dropzone ref="myVueDropzone" id="dropzone" :options="dropzoneOptions" :useCustomSlot=true v-on:vdropzone-complete="uploadCompleted">
                                    <div class="dropzone-custom-content">
                                        <h3 class="dropzone-custom-title">Drag and drop to upload content!</h3>
                                        <div class="subtitle">...or click to select a file from your computer</div>
                                    </div>
                                </vue-dropzone>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="green darken-1" flat="flat" @click="uploadDialog = false">
                        Fermer
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-card>
</template>

<script>

    import vue2Dropzone from 'vue2-dropzone'
    import 'vue2-dropzone/dist/vue2Dropzone.min.css'
    import {mapGetters} from 'vuex'
    import filesize from 'filesize'
    import moment from 'moment'
    import DocumentDetails from "./DocumentDetails";

    export default {
        name: "Home",
        components: {
            DocumentDetails,
            vueDropzone: vue2Dropzone
        },
        data() {
            return {
                modeDrawer: '',
                rightDrawer: false,
                currentSelected: '',
                fab: false,
                uploadDialog: false,
                dropzoneOptions: {
                    url: url_upload,
                    thumbnailWidth: 200,
                    addRemoveLinks: true,
                    chunking: true,
                    forceChunking: true,
                    headers: { "directory": home_id }
                },
                selected: [],
                pagination: {
                    sortBy: 'dateCreation',
                    descending: true
                },
                rowsPerPageItems: [20,30,40],
                headers: [
                    {text: 'Type', value: 'extension', sortable: true, align: 'left', width: '10%'},
                    {text: 'Nom', value: 'name', sortable: true, align: 'left', width: '50%'},
                    {text: '', value: '', width: '10%'},
                    {text: 'Dernière modification', value: 'lastModification', sortable: true, width: '10%', align: 'right'},
                    {text: 'Taille', value: 'size', sortable: true, width: '10%', align: 'right'},
                ],
                documents: [
                    {id: '1', extension: 'pdf', name: 'test.pdf', lastModification: '2018', size: '259'}
                ]
            }
        },
        computed: {
            ...mapGetters('repertoire', {repertoire: 'repertoire'}),
        },
        mounted() {
            this.$store.dispatch('repertoire/loadRepertoire', {id: this.$route.params.hash ? this.$route.params.hash : home_id})
        },
        methods: {
            getImage(extension) {
                let extensions = {
                    'avi' : 'build/assets/img/filetypes/avi.png',
                    'doc' : 'build/assets/img/filetypes/doc.png',
                    'gif' : 'build/assets/img/filetypes/gif.png',
                    'html' : 'build/assets/img/filetypes/html.png',
                    'jpg' : 'build/assets/img/filetypes/jpg.png',
                    'mp3' : 'build/assets/img/filetypes/mp3.png',
                    'mp4' : 'build/assets/img/filetypes/mp4.png',
                    'pdf' : 'build/assets/img/filetypes/pdf.png',
                    'png' : 'build/assets/img/filetypes/png.png',
                    'txt' : 'build/assets/img/filetypes/txt.png',
                    'xls' : 'build/assets/img/filetypes/xls.png',
                    'xlsx' : 'build/assets/img/filetypes/xls.png',
                }
                if (extension in extensions) {
                    return extensions[extension]
                }
                return 'build/assets/img/filetypes/unknown.png'
            },

            getSize(size) {
                return filesize(size)
            },

            getDate(adate) {
                return moment(adate).locale('fr').fromNow()
            },

            uploadCompleted(response) {
                if(response.status !== 'success') {
                    //notify user : bad :/
                }
                this.fab = false
                this.uploadDialog = false
                this.$store.dispatch('repertoire/loadRepertoire', {id: this.$route.params.hash ? this.$route.params.hash : home_id})
            },

            showDetails(id) {
                this.currentSelected = id
                this.modeDrawer = 'showDetails'
                this.rightDrawer = true
            }
        }
    }
</script>

<style scoped>

</style>

<!--

Liste des colonnes

checkbox / Icone / Nom / actions (oeil / share / download) / date lastModification / size

-->