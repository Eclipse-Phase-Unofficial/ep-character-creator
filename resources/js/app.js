import './bootstrap';
import './helpers';

//jQuery-ui
require('jquery-ui/ui/widgets/tooltip');

//UiKit
require('uikit');

//Background slide show
require('./background').init();

//Vue and associated pieces
const Vue = require('vue');
const Vuex = require('vuex');
const VueRouter = require('vue-router').default;
import VueAnalytics from 'vue-analytics';

Vue.use(Vuex);
Vue.use(VueRouter);

//Modals
Vue.component('about', require('./components/modals/About').default);
Vue.component('validation', require('./components/modals/ValidationCheck').default);
Vue.component('load-dialog', require('./components/modals/LoadDialog').default);
Vue.component('new-character-modal', require('./components/modals/NewCharacterModal').default);


Vue.component('points-tracker', require('./components/PointsTracker').default);
Vue.component('panel-one', require('./components/PanelOne').default);
Vue.component('main-menu', require('./components/MainMenu').default);

const store = new Vuex.Store({
    modules: {
        highLevel: require('./store/modules/highLevelCreator').default,
        character: require('./store/modules/character').default,
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

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'main',
            component: require('./pages/Main').default
        },
        {
            path: '/welcome',
            name: 'welcome',
            component: require('./pages/Welcome').default
        },
    ],
});

Vue.use(VueAnalytics, {
    //If the variable isn't set dynamically, try the compiled in version.  If that fails, then fall back to a safe default.
    id: window.env.MIX_GOOGLE_ANALYTICS_ID || process.env.MIX_GOOGLE_ANALYTICS_ID || 'UA-463340-1',
    // debug: {
    //     enabled: true,
    //     sendHitTask: false,
    // },
    router,
});

//Do an initial check on the creator during the first page load
//This must be done here, so we can wait for the asynchronous call to complete before finishing routing
router.beforeEach((to, from, next) => {
    if(store.state.firstTime) {
        store.commit('markFirstTime');
        store.dispatch('highLevel/getHighLevelCreatorInfo')
            .then(() => {
                next()
            }).catch(() => {next()});
        return
    }
    next();
});

router.beforeEach((to, from, next) => {
    //Go to the welcome page if the creator does not exist
    if(!store.getters['highLevel/creatorExists'] && to.name !== 'welcome') {
        next({name: 'welcome'});
        return;
    }
    //Do not allow access to the welcome page while a creator exists
    if(store.getters['highLevel/creatorExists'] && to.name === 'welcome') {
        //Prevent infinite recursion, with a default
        if (from.name === 'welcome') {
            next({name: 'main'});
            return;
        }
        next(from);
        return;
    }
    next();
});

window.app = new Vue({
    el: '#container',
    store,
    router,
});
