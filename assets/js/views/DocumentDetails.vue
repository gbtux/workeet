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
                        <v-flex md12>
                            <v-list two-line>
                                <v-list-tile avatar>
                                    <v-list-tile-avatar>
                                        <v-icon class="primary white--text">face</v-icon>
                                    </v-list-tile-avatar>
                                    <v-list-tile-content>
                                        <v-list-tile-title>{{ document.author.username }}</v-list-tile-title>
                                        <v-list-tile-sub-title>{{ getDate(document.dateModification) }}</v-list-tile-sub-title>
                                    </v-list-tile-content>
                                    <v-list-tile-action>
                                        <v-list-tile-action-text>{{ getSize(document.size)}} | {{ document.extension}}</v-list-tile-action-text>
                                    </v-list-tile-action>
                                </v-list-tile>
                                <v-list-tile avatar>
                                    <v-list-tile-avatar>
                                        <v-icon class="blue white--text">alarm_add</v-icon>
                                    </v-list-tile-avatar>
                                    <v-list-tile-content>
                                        <v-list-tile-title>{{ getDate(document.dateCreation) }}</v-list-tile-title>
                                    </v-list-tile-content>
                                    <v-list-tile-action>
                                        <v-list-tile-action-text>créé le</v-list-tile-action-text>
                                    </v-list-tile-action>
                                </v-list-tile>
                                <v-list-tile avatar>
                                    <v-list-tile-avatar>
                                        <v-icon class="pink white--text">share</v-icon>
                                    </v-list-tile-avatar>
                                    <v-list-tile-content>
                                        <v-list-tile-title>partagé {{ document.partages.length }} fois</v-list-tile-title>
                                    </v-list-tile-content>
                                </v-list-tile>
                            </v-list>
                        </v-flex>
                    </v-card-text>
                </v-card>
            </v-tab-item>
            <v-tab-item>
                <v-card flat>
                    <v-card-text>
                        <v-flex md12 style="padding: 0; padding-left: 30px;">
                            <v-form>
                                <v-switch v-model="ldocument.public" color="red" @change="updatePublic">
                                    <template v-slot:label>
                                        <strong class="red--text">public ?</strong>
                                    </template>
                                </v-switch>
                            </v-form>
                        </v-flex>
                        <v-flex md12 style="padding-top: 0">
                            <div class="subheading">Partager à d'autres utilisateurs ou groupes</div>
                            <v-flex xs12>
                                <v-autocomplete
                                        v-model="selectUsers"
                                        :search-input.sync="searchUsers"
                                        :items="userItems"
                                        chips
                                        label="Selectionnez des utilisateurs"
                                        item-text="username"
                                        item-value="id"
                                        multiple
                                >
                                    <template v-slot:selection="data">
                                        <v-chip
                                                :selected="data.selected"
                                                close
                                                class="chip--select-multi"
                                                @input="removeUser(data.item)"
                                        >
                                            <v-avatar>
                                                <v-icon>account_circle</v-icon>
                                            </v-avatar>
                                            {{ data.item.username }}
                                        </v-chip>
                                    </template>
                                    <template v-slot:item="data">
                                        <template v-if="typeof data.item !== 'object'">
                                            <v-list-tile-content v-text="data.item"></v-list-tile-content>
                                        </template>
                                        <template v-else>
                                            <v-list-tile-avatar>
                                                <v-icon>account_circle</v-icon>
                                            </v-list-tile-avatar>
                                            <v-list-tile-content>
                                                <v-list-tile-title v-html="data.item.username"></v-list-tile-title>
                                                <!--<v-list-tile-sub-title v-html="data.item.group"></v-list-tile-sub-title>-->
                                            </v-list-tile-content>
                                        </template>
                                    </template>
                                </v-autocomplete>
                            </v-flex>
                            <v-flex xs12 style="padding-top: 0">
                                <v-autocomplete
                                        v-model="selectGroups"
                                        :search-input.sync="searchGroups"
                                        :items="groupItems"
                                        chips
                                        label="Selectionnez des groupes"
                                        item-text="name"
                                        item-value="id"
                                        multiple
                                >
                                    <template v-slot:selection="data">
                                        <v-chip
                                                :selected="data.selected"
                                                close
                                                class="chip--select-multi"
                                                @input="removeGroup(data.item)"
                                        >
                                            <v-avatar>
                                                <v-icon>account_circle</v-icon>
                                            </v-avatar>
                                            {{ data.item.name }}
                                        </v-chip>
                                    </template>
                                    <template v-slot:item="data">
                                        <template v-if="typeof data.item !== 'object'">
                                            <v-list-tile-content v-text="data.item"></v-list-tile-content>
                                        </template>
                                        <template v-else>
                                            <v-list-tile-avatar>
                                                <v-icon>account_circle</v-icon>
                                            </v-list-tile-avatar>
                                            <v-list-tile-content>
                                                <v-list-tile-title v-html="data.item.name"></v-list-tile-title>
                                                <!--<v-list-tile-sub-title v-html="data.item.group"></v-list-tile-sub-title>-->
                                            </v-list-tile-content>
                                        </template>
                                    </template>
                                </v-autocomplete>
                            </v-flex>
                            <v-flex xs12 style="padding-top: 0">
                                <v-select :items="sharedTypes" v-model="shareTypeSelected" label="Type de partage"></v-select>
                                <v-btn color="primary" @click="addShare">Ajouter</v-btn>
                            </v-flex>
                        </v-flex>
                        <v-flex md12>
                            <div class="subheading">Partages existant</div>
                            <v-list two-line>
                                <v-list-tile avatar v-for="partage in document.partages" :key="partage.id">
                                    <v-list-tile-avatar v-if="partage.utilisateur">
                                        <v-icon class="primary white--text">face</v-icon>
                                    </v-list-tile-avatar>
                                    <v-list-tile-avatar v-if="partage.groupe">
                                        <v-icon class="primary white--text">account_box</v-icon>
                                    </v-list-tile-avatar>
                                    <v-list-tile-content>
                                        <v-list-tile-title v-if="partage.utilisateur">{{ partage.utilisateur.username }}</v-list-tile-title>
                                        <v-list-tile-title v-if="partage.groupe">{{ partage.groupe.name}}</v-list-tile-title>
                                    </v-list-tile-content>
                                    <v-list-tile-action>
                                        <v-flex>
                                            <v-icon v-if="partage.typePartage === 'write'">lock_open</v-icon>
                                            <v-icon v-if="partage.typePartage === 'read'">lock</v-icon>
                                            <v-icon color="red">delete</v-icon>
                                        </v-flex>
                                    </v-list-tile-action>
                                </v-list-tile>
                            </v-list>
                        </v-flex>
                    </v-card-text>
                </v-card>
            </v-tab-item>
            <v-tab-item>
                <v-card flat>
                    <v-card-text>
                        <v-flex md12 style="padding-left: 0">
                            <v-list two-line>
                                <v-list-tile avatar v-for="event in document.doc_evenements" :key="event.id">
                                    <v-list-tile-avatar>
                                        <v-icon class="primary white--text">face</v-icon>
                                    </v-list-tile-avatar>
                                    <v-list-tile-content>
                                        <v-list-tile-title>{{ event.utilisateur.username }}</v-list-tile-title>
                                        <v-list-tile-sub-title v-if="event.description">{{ event.description}}</v-list-tile-sub-title>
                                    </v-list-tile-content>
                                    <v-list-tile-action>
                                        <v-list-tile-action-text>{{ event.typeEvent}} | {{ getDate(event.dateEvent) }}</v-list-tile-action-text>
                                    </v-list-tile-action>
                                </v-list-tile>
                            </v-list>
                        </v-flex>
                    </v-card-text>
                </v-card>
            </v-tab-item>
        </v-tabs>
    </v-flex>
</template>

<script>

    import {mapGetters} from 'vuex'
    import _ from 'lodash'
    import moment from 'moment'
    import filesize from 'filesize'
    import axios from 'axios'

    export default {
        name: "DocumentDetails",
        props: {
            id: String
        },
        data() {
            return {
                tabActive: null,
                selectUsers: null,
                userItems: [],
                searchUsers: null,
                selectGroups: null,
                groupItems: [],
                searchGroups: null,
                sharedTypes: [ {text: 'Lecture', value: 'read'}, {text: 'Ecriture', value: 'write'} ],
                shareTypeSelected: null
            }
        },
        computed: {
            ...mapGetters('repertoire', {document: 'document'}),
            pdfUrl() {
                return url_api + '/documents/' + this.id + "/pdf#toolbar=0&navpanes=0&scrollbar=0&page=0&zoom=scale&view=Fit"
            },
            ldocument() {
                return _.cloneDeep(this.document)
            }
        },
        watch: {
            'id' : 'loadDocument',
            searchUsers (val) {
                val && val !== this.selectUsers && this.queryUsersSelections(val)
            },
            searchGroups (val) {
                val && val !== this.selectGroups && this.queryGroupsSelections(val)
            }
        },
        mounted () {
            this.$store.dispatch('repertoire/loadDocument', {id: this.id})
        },
        methods: {
            loadDocument() {
                this.$store.dispatch('repertoire/loadDocument', {id: this.id})
            },

            getDate(adate) {
                return moment(adate).locale('fr').fromNow()
            },

            getSize(size) {
                return filesize(size)
            },
            updatePublic() {
                this.$store.dispatch('repertoire/updateDocumentPublic', {id: this.id, isPublic: this.ldocument.public})
            },

            queryUsersSelections(v) {
                axios.get(url_api + '/users?search='+v).then(response => {
                    this.userItems = response.data
                })
            },
            removeUser (item) {
                console.log(item)
            },
            queryGroupsSelections(val) {
                axios.get(url_api + '/groups?search='+ val).then(response => {
                    this.groupItems = response.data
                })
            },
            addShare() {
                this.$store.dispatch('repertoire/createShare', {id: this.id, users: this.selectUsers, groups: this.selectGroups, shareType: this.shareTypeSelected}).then(response => {
                    this.selectGroups = null
                    this.selectUsers = null
                    this.shareTypeSelected = null
                })
            }
        }
    }
</script>

<style scoped>

</style>