<template>
    <v-card id="create">
        <v-container grid-list-xl fluid>
            <v-layout row wrap>
                <v-flex sm12>
                    <h3>Mon espace</h3>
                </v-flex>
                <v-flex lg12>
                    <v-data-table
                            :headers="headers"
                            :items="documents"
                            class="elevation-1"
                            v-model="selected"
                            item-key="id"
                            select-all
                    >
                        <template v-slot:items="props">
                            <td>
                                <v-checkbox v-model="props.selected" primary hide-details></v-checkbox>
                            </td>
                            <td><img class="img-fluid" :src="getImage(props.item.extension)" style="max-height: 70%"></td>
                            <td> {{ props.item.name }}
                            <td class="text-xs-right">
                                <v-icon>visibility</v-icon>
                                <v-icon>share</v-icon>
                                <v-icon style="cursor: pointer" @click="alert('test')">play_for_work</v-icon>
                            </td>
                            <td class="text-xs-right">il y a 2 jours</td>
                            <td class="text-xs-right">258 ko</td>
                        </template>
                    </v-data-table>
                </v-flex>
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
                                <vue-dropzone ref="myVueDropzone" id="dropzone" :options="dropzoneOptions" :useCustomSlot=true>
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

    export default {
        name: "Home",
        components: {
            vueDropzone: vue2Dropzone
        },
        data() {
            return {
                fab: false,
                uploadDialog: false,
                dropzoneOptions: {
                    url: url_upload,
                    thumbnailWidth: 200,
                    addRemoveLinks: true,
                    headers: { "directory": home_id }
                },
                selected: [],
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