import Vue from "vue"
import axios from 'axios'
/** Vuex contact store **/

export default {
    namespaced: true,
    state: {
        contacts: [],
        contact: {},
    },

    getters: {
        contacts: function (state) {
            return state.contacts
        },
        contact: function (state) {
            return state.contact
        }
    },

    mutations: {
        setContacts: function (state, {contacts}) {
            state.contacts = contacts
        },
        setContact: function (state, {contact}) {
            state.contact = contact
        },
        addContact: function (state, {contact}) {
            if(!Array.isArray(state.contacts)) {
                state.contacts = []
            }
            state.contacts.push(contact)
        },
        updateContact: function (state, {contact}) {
            let conts = []
            state.contacts.forEach(f => {
                if(f.id === contact.id) {
                    conts.push(contact)
                }else{
                    conts.push(f)
                }
            })
            state.contacts = conts
        }
    },

    actions: {
        loadContacts: async function (context) {
            return new Promise((resolve, reject) => {
                axios.get(url_api + '/contacts').then(response => {
                    context.commit('setContacts', {contacts: response.data})
                    resolve()
                }).catch(e => {
                    reject(e)
                })
            })
        },
        loadContact: async function (context, {id}) {
            return new Promise((resolve, reject) => {
                axios.get(url_api + '/contacts/' + id).then(response => {
                    context.commit('setContact', {contact: response.data})
                    resolve()
                }).catch(e => {
                    reject(e)
                })
            })
        },
        createContact: async function(context, {contact}) {
            return new Promise((resolve, reject) => {
                axios.post(url_api + '/contacts', contact).then(response => {
                    context.commit('addContact', {contact: response.data})
                    resolve()
                }).catch(e => {
                    reject(e)
                })
            })
        },
        saveContact: async function(context, {id, contact}) {
            delete contact.id

            return new Promise((resolve, reject) => {
                axios.put(url_api + '/contacts/' + id, contact).then(response => {
                    context.commit('updateContact', {contact: response.data})
                    resolve()
                }).catch(e => {
                    reject(e)
                })
            })
        },

    }

}