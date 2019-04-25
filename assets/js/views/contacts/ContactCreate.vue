<template>
    <v-container>
        <v-flex lg12>
            <h1>Créer un contact</h1>
            <v-form>
                <v-select :items="civility_items"  v-model="contact.civility" label="Civilité" outline></v-select>
                <v-text-field v-model="contact.prenom" label="Prénom"></v-text-field>
                <v-text-field v-model="contact.nom" label="Nom*" required></v-text-field>
                <v-text-field v-model="contact.email" label="Email"></v-text-field>
                <v-text-field v-model="contact.mobile" label="Mobile"></v-text-field>
                <v-text-field v-model="contact.telephone" label="Téléphone"></v-text-field>
                <v-text-field v-model="contact.adresse" label="Adresse"></v-text-field>
                <v-text-field v-model="contact.codePostal" label="Code postal"></v-text-field>
                <v-text-field v-model="contact.ville" label="Ville"></v-text-field>
                <v-expansion-panel>
                    <v-expansion-panel-content>
                        <template v-slot:header>
                            <div>Autres informations</div>
                        </template>
                        <v-card>
                            <v-container>
                                <v-form>
                                    <v-text-field v-model="contact.facebook" label="Facebook"></v-text-field>
                                    <v-text-field v-model="contact.twitter" label="Twitter"></v-text-field>
                                    <v-text-field v-model="contact.linkedin" label="LinkedIn"></v-text-field>
                                    <v-text-field v-model="contact.skype" label="Chat"></v-text-field>
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
    export default {
        name: "ContactCreate",
        data() {
            return {
                civility_items: [{text: 'Monsieur', value: 'Mr'},{text: 'Madame', value: 'Mme'}],
                contact: {
                    civility: '',
                    prenom: '',
                    nom: '',
                    email: '',
                    mobile: '',
                    telephone: '',
                    facebook: '',
                    twitter: '',
                    linkedin: '',
                    skype: '',
                    adresse: '',
                    codePostal: '',
                    ville: ''
                }
            }
        },
        methods: {
            submit() {
                this.$store.dispatch('contact/createContact', {contact: this.contact}).then(() => {
                    this.contact = {
                        civility: '',
                        prenom: '',
                        nom: '',
                        email: '',
                        mobile: '',
                        telephone: '',
                        facebook: '',
                        twitter: '',
                        linkedin: '',
                        skype: '',
                        adresse: '',
                        codePostal: '',
                        ville: ''
                    }
                    this.$root.$emit('closeRightDrawer')
                })
            },
            clear() {
                this.contact = {
                    civility: '',
                    prenom: '',
                    nom: '',
                    email: '',
                    mobile: '',
                    telephone: '',
                    facebook: '',
                    twitter: '',
                    linkedin: '',
                    skype: '',
                    adresse: '',
                    codePostal: '',
                    ville: ''
                }
                this.$root.$emit('closeRightDrawer')
            }
        }
    }
</script>

<style scoped>

</style>