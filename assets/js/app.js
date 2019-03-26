require('../css/app.css');


import Vue from "vue";
import './plugins/vuetify'
import App from "./App.vue"
import router from "./router/"
//import store from "./store/"
//import VueResource from 'vue-resource'
import VuetifyConfirm from 'vuetify-confirm'

import 'roboto-fontface/css/roboto/roboto-fontface.css'
import 'font-awesome/css/font-awesome.css'

//Vue.use(VueResource)
Vue.use(VuetifyConfirm)

Vue.config.productionTip = false;

const imagesContext = require.context('../img', true, /\.(png|jpg|jpeg|gif|ico|svg|webp)$/);
imagesContext.keys().forEach(imagesContext)


new Vue({
    router,
    //store,
    render: h => h(App)
}).$mount("#app")