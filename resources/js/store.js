import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        tasks: [],
        settings: [],
    },
    getters: {
        getTasks(state) {
            return { tasks: state.tasks, settings: state.settings };
        }
    },
    mutations: {
        updateDetails(state, data) {
            state.tasks = data.tasks;
            state.settings = data.settings;
        }
    },
    actions: {
        getDetailsForSchedule({ commit }) {
            fetch("/schedule")
                .then((res) => res.json())
                .then((data) => commit('updateDetails', data));
        }
    }
});
