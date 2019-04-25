<template>
    <v-container>
        <v-flex lg12>
            <h1>Modifier un contact</h1>
            <v-form>
                <v-select :items="civility_items"  v-model="lcontact.civility" label="Civilité" outline></v-select>
                <v-text-field v-model="lcontact.prenom" label="Prénom"></v-text-field>
                <v-text-field v-model="lcontact.nom" label="Nom*" required></v-text-field>
                <v-text-field v-model="lcontact.email" label="Email"></v-text-field>
                <v-text-field v-model="lcontact.mobile" label="Mobile"></v-text-field>
                <v-text-field v-model="lcontact.telephone" label="Téléphone"></v-text-field>
                <v-text-field v-model="lcontact.adresse" label="Adresse"></v-text-field>
                <v-text-field v-model="lcontact.codePostal" label="Code postal"></v-text-field>
                <v-text-field v-model="lcontact.ville" label="Ville"></v-text-field>
                <v-expansion-panel>
                    <v-expansion-panel-content>
                        <template v-slot:header>
                            <div>Autres informations</div>
                        </template>
                        <v-card>
                            <v-container>
                                <v-form>
                                    <v-text-field v-model="lcontact.facebook" label="Facebook"></v-text-field>
                                    <v-text-field v-model="lcontact.twitter" label="Twitter"></v-text-field>
                                    <v-text-field v-model="lcontact.linkedin" label="LinkedIn"></v-text-field>
                                    <v-text-field v-model="lcontact.skype" label="Chat"></v-text-field>
                                </v-form>
                            </v-container>
                        </v-card>
                    </v-expansion-panel-content>
                </v-expansion-panel>
                <v-btn color="primary" @click="submit">Enregistrer</v-btn>
                <v-btn @click="clear">annuler</v-btn>
            </v-form>
        </v-flex>
    </v-container>
</template>

<script>

    import {mapGetters} from 'vuex'
    import _ from 'lodash'

    export default {
        name: "ContactUpdate",
        props: {
            id: Number
        },
        data() {
            return {
                civility_items: [{text: 'Monsieur', value: 'Mr'},{text: 'Madame', value: 'Mme'}],
            }
        },
        computed: {
            ...mapGetters('contact', {contact: 'contact'}),
            lcontact() {
                return _.cloneDeep(this.contact)
            },
        },
        watch: {
            'id' : 'loadContact'
        },
        mounted () {
            this.$store.dispatch('contact/loadContact', {id: this.id})
        },
        methods: {
            submit() {
                this.$store.dispatch('contact/saveContact', {id: this.id, contact: this.lcontact}).then(() => {
                    this.$root.$emit('closeRightDrawer')
                })
            },
            clear() {
                this.$root.$emit('closeRightDrawer')
            },
            loadContact() {
                this.$store.dispatch('contact/loadContact', {id: this.id})
            }
        }
    }
</script>
