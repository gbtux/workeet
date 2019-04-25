<template>
    <div>
        <v-card id="create">
            <v-container grid-list-xl fluid>
                <v-layout row wrap>
                    <v-flex sm12>
                        <h3>{{ repertoire.nom }}</h3>
                    </v-flex>
                    <v-flex sm12>
                        <v-breadcrumbs :items="repertoire.breadcrumb" divider=">">
                            <template v-slot:item="props">
                                <router-link :to="{name: 'Home', params: {hash: props.item.hash}}">{{ props.item.name.toUpperCase() }}</router-link>
                            </template>
                        </v-breadcrumbs>
                    </v-flex>
                    <v-flex lg12>
                        <v-data-table
                            :headers="headersDirectory"
                            :items="repertoire.sousRepertoires"
                            class="elevation-1"
                            v-model="selectedDirectories"
                            item-key="hash"
                            select-all
                            hide-actions
                            v-if="repertoire.sousRepertoires.length > 0"
                        >
                            <template v-slot:items="props">
                                <td><v-checkbox v-model="props.selected" primary hide-details></v-checkbox></td>
                                <td style="text-align: center">
                                    <img class="img-fluid" :src="getDirectoryImage()" style="max-height: 70%">
                                </td>
                                <td><router-link :to="{name: 'Home', params: {hash: props.item.hash}}">{{ props.item.nom }}</router-link></td>
                                <td class="text-xs-right">{{ props.item.documents.length}}</td>
                            </template>
                        </v-data-table>
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
                                <td>
                                    <!-- <img class="img-fluid" :src="getImage(props.item.extension)" style="max-height: 70%"> -->
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
                        <DocumentDetails :id="currentSelected" :isShared="false" v-if="modeDrawer === 'showDetails'"></DocumentDetails>
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
                <v-btn fab dark small color="indigo" @click.prevent="createDirectoryDialog = true">
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

        <v-dialog v-model="fullscreenDialog" fullscreen hide-overlay transition="dialog-bottom-transition">
            <v-card>
                <v-toolbar dark color="primary">
                    <v-btn icon dark @click="fullscreenDialog = false">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>{{ selectedFullscreenTitle }}</v-toolbar-title>
                </v-toolbar>
                <v-flex md12 style="height: calc(100vh - 64px)">
                    <object :data="selectedFullscreenUrl" type="application/pdf" width="100%" height="100%" ></object>
                </v-flex>
            </v-card>
        </v-dialog>
        <v-dialog v-model="createDirectoryDialog" max-width="500px" persistent>
            <v-card>
                <v-card-title>
                    Création d'un répertoire
                </v-card-title>
                <v-card-text>
                    <v-form>
                        <v-text-field v-model="newDirectory" label="Nom du répertoire"></v-text-field>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-btn color="primary" flat @click.prevent="closeCreateDirectoryDialog">Annuler</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="success" flat @click.prevent="createDirectory">Créer le répertoire</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>

    import vue2Dropzone from 'vue2-dropzone'
    import 'vue2-dropzone/dist/vue2Dropzone.min.css'
    import {mapGetters} from 'vuex'
    import filesize from 'filesize'
    import moment from 'moment'
    import DocumentDetails from "./DocumentDetails";
    import 'css-file-icons/build/css-file-icons.css'

    export default {
        name: "Home",
        components: {
            DocumentDetails,
            vueDropzone: vue2Dropzone
        },
        data() {
            return {
                createDirectoryDialog: false,
                newDirectory: '',
                fullscreenDialog: false,
                selectedFullscreenTitle: '',
                selectedFullscreenUrl: '',
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
                    headers: {
                        "directory": home_id
                    }
                },
                selected: [],
                pagination: {
                    sortBy: 'dateCreation',
                    descending: true
                },
                rowsPerPageItems: [20,30,40],
                headers: [
                    {text: 'Type', value: 'extension', sortable: true, align: 'left', width: '10%'},
                    {text: 'Nom', value: 'name', sortable: true, align: 'left', width: '60%'},
                    {text: '', value: '', width: '10%'},
                    {text: 'Dernière modification', value: 'lastModification', sortable: true, width: '10%', align: 'right'},
                    {text: 'Taille', value: 'size', sortable: true, width: '10%', align: 'right'},
                ],
                headersDirectory: [
                    {text: 'Type', value: 'extension', sortable: true, align: 'left', width: '10%'},
                    {text: 'Nom', value: 'nom', sortable: true, align: 'left', width: '90%'},
                    {text: 'Nombre de fichiers', value: 'documents', sortable: true, align: 'right', width: '10%'},
                ],
                selectedDirectories: []
            }
        },
        computed: {
            ...mapGetters('repertoire', {repertoire: 'repertoire'}),
        },
        watch: {
            '$route.params.hash': 'loadRepertoire'
        },
        mounted() {
            this.$store.dispatch('repertoire/loadRepertoire', {id: this.$route.params.hash ? this.$route.params.hash : home_id})
        },
        methods: {
            loadRepertoire() {
                this.$store.dispatch('repertoire/loadRepertoire', {id: this.$route.params.hash ? this.$route.params.hash : home_id})
                this.dropzoneOptions.headers.directory = this.$route.params.hash ? this.$route.params.hash : home_id
            },
            /*getImage(extension) {
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
            },*/

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
                this.$refs.myVueDropzone.removeAllFiles()
            },

            showDetails(id) {
                this.currentSelected = id
                this.modeDrawer = 'showDetails'
                this.rightDrawer = true
            },

            getUrl(id) {
                return url_api + '/documents/' + id + '/download'
            },

            showFullscreen(item) {
                this.selectedFullscreenTitle = item.nom
                this.selectedFullscreenUrl = url_api + '/documents/' + item.id + "/pdf#page=0&zoom=scale&view=Fit"
                this.fullscreenDialog = true
            },

            closeCreateDirectoryDialog() {
                this.newDirectory = ''
                this.createDirectoryDialog = false
            },

            createDirectory() {
                this.fab = false
                this.createDirectoryDialog = false
                this.$store.dispatch('repertoire/createRepertoire', {id: this.$route.params.hash ? this.$route.params.hash : home_id, repertoire: this.newDirectory}).then(response => {
                    this.newDirectory = ''
                    this.$store.dispatch('repertoire/loadRepertoire', {id: this.$route.params.hash ? this.$route.params.hash : home_id})
                })
            },
            getDirectoryImage() {
                return 'build/assets/img/iconfinder_envelope-2_416374.svg'
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