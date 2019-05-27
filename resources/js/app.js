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
let Vue = require('vue');
var Vuex = require('vuex');

Vue.use(Vuex);

Vue.component('points-tracker', require('./components/PointsTracker').default);
Vue.component('panel-one', require('./components/PanelOne').default);
Vue.component('about', require('./components/About').default);
Vue.component('validation', require('./components/ValidationCheck').default);
Vue.component('load-dialog', require('./components/LoadDialog').default);

const store = new Vuex.Store({
    modules: {
        highLevel: require('./store/modules/highLevelCreator').default,
        character: require('./store/modules/character').default,
    }
});

window.app = new Vue({
    el: '#container',
    store
});
