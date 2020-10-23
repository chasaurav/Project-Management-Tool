require('./bootstrap');

window.Vue = require('vue');
import router from "./router";
import store from "./store";
import TaskComponent from './components/task.vue';
import SettingsComponent from './components/settings.vue';
import ScheduleComponent from './components/schedule.vue';

Vue.component('Task', TaskComponent);
Vue.component('Settings', SettingsComponent);
Vue.component('Schedule', ScheduleComponent);

const app = new Vue({
    el: '#app',
    router,
    store
});
