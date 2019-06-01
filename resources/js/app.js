import './bootstrap';

//jQuery-ui
require('jquery-ui/ui/widgets/tooltip');

//UiKit
require('uikit');

//Google Analytics
require('./googleAnalytics').init();

//Background slide show
require('./background').init();

//Vue and associated pieces
const Vue = require('vue');
const Vuex = require('vuex');
const VueRouter = require('vue-router').default;

Vue.use(Vuex);
Vue.use(VueRouter);

Vue.component('points-tracker', require('./components/PointsTracker').default);
Vue.component('panel-one', require('./components/PanelOne').default);
Vue.component('about', require('./components/About').default);
Vue.component('validation', require('./components/ValidationCheck').default);
Vue.component('load-dialog', require('./components/LoadDialog').default);
Vue.component('main-menu', require('./components/MainMenu').default);

const store = new Vuex.Store({
    modules: {
        highLevel: require('./store/modules/highLevelCreator').default,
        character: require('./store/modules/character').default,
    }
});

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'main',
            component: require('./pages/Main').default
        }
    ],
});

window.app = new Vue({
    el: '#container',
    store,
    router,
});
