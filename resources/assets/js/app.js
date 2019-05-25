import './bootstrap';

//jQuery-ui
require('jquery-ui/ui/widgets/tooltip');

//Google Analytics
require('./googleAnalytics').init();

//Background slide show
require('./background').init();

let Vue = require('vue');

Vue.component('points-tracker', require('./components/PointsTracker').default);

const app = new Vue({
    el: '#container'
});
