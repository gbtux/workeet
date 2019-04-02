import Vuex from 'vuex'
import Vue from 'vue'

Vue.use(Vuex)

import repertoire from './modules/repertoire'

export default new Vuex.Store({
    strict: true,
    modules: {
        repertoire
    }
})