require('./bootstrap');

import Vue from 'vue'

Vue.component('CodeUploaderComponent', require("@component/CodeUploaderComponent").default);
Vue.component('RegistrationComponent', require("@component/RegistrationComponent").default);
Vue.component('WinnerComponent', require("@component/WinnerComponent").default);

new Vue({
}).$mount('#app')
