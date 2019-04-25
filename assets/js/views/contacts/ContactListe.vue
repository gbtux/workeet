<template>
    <div id="social">
        <v-container grid-list-xl fluid>
            <v-layout row class="align-center layout px-4 pt-4 app--page-header">
                    <div class="page-header-left">
                        <h3 class="pr-3">Contacts</h3>
                    </div>
                    <v-spacer></v-spacer>
                    <div class="page-header-right">
                        <v-btn icon @click="toggleViewMode">
                            <v-icon class="text--secondary" v-if="!modeGrid">dehaze</v-icon>
                            <v-icon class="text--secondary" v-if="modeGrid">view_comfy</v-icon>
                        </v-btn>
                    </div>
            </v-layout>
            <v-layout row wrap v-if="!modeGrid">
                <v-flex lg12>
                    <v-card>
                        <v-form>
                            <v-btn color="success" @click="createContact">Créer un contact</v-btn>
                        </v-form>
                    </v-card>
                </v-flex>

                <v-flex lg3 sm12 v-for="contact in contacts" :key="contact.id">
                    <div class="name-card" style="cursor: pointer" @click="updateContact(contact.id)">
                        <v-card ref="card">
                            <v-card-text>
                                <div class="layout ma-0 align-center row">
                                    <v-avatar :size="48" color="primary">
                                        <span class="white--text headline">{{ contact.nom.charAt(0) }}</span>
                                    </v-avatar>
                                    <div class="flex text-sm-right">
                                        <div class="subheading">{{contact.prenom}} {{contact.nom}}</div>
                                        <span class="caption">{{contact.email}} / {{ contact.mobile}}</span>
                                    </div>
                                </div>
                            </v-card-text>
                        </v-card>
                    </div>
                </v-flex>
            </v-layout>
            <v-layout row wrap v-if="modeGrid">
            </v-layout>
            <v-navigation-drawer class="setting-drawer" temporary right v-model="rightDrawer" hide-overlay fixed width="500">
                <ContactCreate v-if="modeDrawer === 'createContact'"></ContactCreate>
                <ContactUpdate :id="selected" v-if="modeDrawer === 'editContact'"></ContactUpdate>
            </v-navigation-drawer>
        </v-container>
    </div>
</template>

<script>

    import {mapGetters} from 'vuex'
    import ContactCreate from "./ContactCreate";
    import ContactUpdate from "./ContactUpdate";

    export default {
        name: "ContactListe",
        components: {ContactUpdate, ContactCreate},
        data() {
            return {
                modeGrid: false,
                rightDrawer: false,
                modeDrawer: '',
                selected: null
            }
        },
        computed: {
            ...mapGetters('contact', {contacts: 'contacts'})
        },
        mounted () {
            this.$store.dispatch('contact/loadContacts')
            this.$root.$on('closeRightDrawer', () => {
                this.rightDrawer = false
            })
        },
        methods: {
            toggleViewMode() {
                this.modeGrid = !this.modeGrid
            },
            createContact() {
                this.modeDrawer = 'createContact'
                this.rightDrawer = true
            },
            updateContact(id) {
                console.log(id)
                this.modeDrawer = 'editContact'
                this.selected = id
                this.rightDrawer = true
            }
        }
    }
</script>

<style scoped>

</style>