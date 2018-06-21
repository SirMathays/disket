
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import vueSmoothScroll from 'vue-smooth-scroll'
import BootstrapVue from 'bootstrap-vue'
import VueClipboard from 'vue-clipboard2'
 
import FairwaySetup from './components/FairwaySetup'
import FairwayMain from './components/FairwayMain'
import SearchBlock from './components/SearchBlock'

Vue.use(vueSmoothScroll)
Vue.use(BootstrapVue)
Vue.use(VueClipboard)

const app = new Vue({
    el: '#app',
    components: {
      'fairway-setup': FairwaySetup,
      'fairway-main': FairwayMain,
      'search-block': SearchBlock,
    }
});
