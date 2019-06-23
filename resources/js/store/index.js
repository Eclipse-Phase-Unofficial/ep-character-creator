import Vue from 'vue';
import Vuex from'vuex';

Vue.use(Vuex);

export const store = new Vuex.Store({
    modules: {
        highLevel: require('./modules/highLevelCreator').default,
        character: require('./modules/character').default,
    },
    state: {
        firstTime: true
    },
    mutations: {
        markFirstTime(state, payload) {
            state.firstTime = false;
        }
    },
});

export default store;
