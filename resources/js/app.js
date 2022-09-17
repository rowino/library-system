import Vue from 'vue'
import VueCompositionApi from '@vue/composition-api'
import './bootstrap'
import App from './App.vue'
import vuetify from './vuetify'
import router from './router'

Vue.use(VueCompositionApi)

new Vue({
  vuetify,
  router,
  render: (h) => h(App),
}).$mount('#app')
