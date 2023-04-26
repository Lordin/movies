import './bootstrap';

window.Vue = require('vue').default;

window.moment = require('moment');
window.momentTimezone = require('moment-timezone');

// Add toasted
import Toasted from 'vue-toasted';
Vue.use(Toasted);

Vue.component('movies', require('./components/Movies.vue').default);

const app = new Vue({
    el: '#app'
});

window.helper = require('./helper.js');