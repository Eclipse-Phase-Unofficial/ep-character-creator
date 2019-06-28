import './bootstrap';

//jQuery-ui
require('jquery-ui/ui/widgets/tooltip');

//UiKit
require('uikit');

//Background slide show
require('./background').init();

//Vue and associated pieces
import Vue from 'vue';
import VueAnalytics from 'vue-analytics';
import store from './store';
import router from './router';
import App from './App';

Vue.use(VueAnalytics, {
    //If the variable isn't set dynamically, try the compiled in version.  If that fails, then fall back to a safe default.
    id: window.env.MIX_GOOGLE_ANALYTICS_ID || process.env.MIX_GOOGLE_ANALYTICS_ID || 'UA-463340-1',
    // debug: {
    //     enabled: true,
    //     sendHitTask: false,
    // },
    router,
});

window.app = new Vue({
    el: '#app',
    store,
    router,
    render: h => h(App)
});
