import Vue from "vue";
import Router from "vue-router";
Vue.use(Router);

import News from "./components/news";

const routes = [
    {
        path: '/news',
        component: News
    }
];

export default new Router({
    mode: "history",
    routes
});
