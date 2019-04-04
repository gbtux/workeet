import Vue from "vue"
import axios from 'axios'

/** Vuex repertoire module store **/

export default {
    namespaced: true,
    state: {
        repertoire: {
            documents: [],
            sousRepertoires: []
        },
        document: {
            author: {
                username: ''
            },
            size: 0,
            partages: []
        }
    },

    getters: {
        repertoire: function (state) {
            return state.repertoire
        },
        document: function (state) {
            return state.document
        }
    },

    mutations: {
        setRepertoire: function (state, {repertoire}) {
            state.repertoire = repertoire
        },
        setDocument: function (state, {document}) {
            state.document = document
        },
        addSubRepertoire: function (state, {repertoire}) {
            state.repertoire.sousRepertoires.push(repertoire)
        }
    },

    actions: {
        loadRepertoire: async function(context, {id}) {
            return new Promise((resolve, reject) => {
                axios.get(url_api + '/directories/' + id).then(response => {
                    context.commit('setRepertoire', {repertoire: response.data})
                    resolve()
                })
                .catch(e => {
                    reject(e)
                })
            })
        },

        loadDocument: async function(context, {id}) {
            return new Promise((resolve, reject) => {
                axios.get(url_api + '/documents/' + id).then(response => {
                    context.commit('setDocument', {document: response.data})
                    resolve()
                })
                .catch(e => {
                    reject(e)
                })
            })
        },

        updateDocumentPublic: async function(context, {id, isPublic}) {
            return new Promise((resolve, reject) => {
                axios.put(url_api + '/documents/' + id + '/public', {isPublic: isPublic}).then(response => {
                    context.commit('setDocument', {document: response.data})
                    resolve()
                })
                .catch(e => {
                    reject(e)
                })
            })
        },

        createShare: async function(context, {id, users, groups, shareType}) {
            return new Promise((resolve, reject) => {
                axios.post(url_api + '/documents/' + id + '/share', {users: users, groups: groups, type: shareType}).then(response => {
                    context.commit('setDocument', {document: response.data})
                    resolve()
                })
                .catch(e => {
                    reject(e)
                })
            })
            .catch(e => {
                reject(e)
            })
        },

        createRepertoire: async function(context, {id, repertoire}) {
            return new Promise((resolve, reject) => {
                axios.post(url_api + '/directories/' + id + '/sub', {nom: repertoire}).then(response => {
                    context.commit('addSubRepertoire', {repertoire: response.data})
                    resolve()
                })
                .catch(e => {
                    reject(e)
                })
            })
        }
    }
}