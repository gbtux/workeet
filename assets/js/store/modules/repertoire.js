import Vue from "vue"
import axios from 'axios'

/** Vuex repertoire module store **/

export default {
    namespaced: true,
    state: {
        repertoire: {
            documents: []
        },
        document: {}
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
        }
    }
}