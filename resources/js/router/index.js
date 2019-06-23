import Vue from 'vue';
import VueRouter from 'vue-router';

import store from '../store';

Vue.use(VueRouter);

export const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'main',
            component: require('../pages/Main').default
        },
        {
            path: '/welcome',
            name: 'welcome',
            component: require('../pages/Welcome').default
        },
    ],
});

//Do an initial check on the creator during the first page load
//This must be done here, so we can wait for the asynchronous call to complete before finishing routing
router.beforeEach((to, from, next) => {
    if(store.state.firstTime) {
        store.commit('markFirstTime');
        store.dispatch('highLevel/getHighLevelCreatorInfo')
            .then(() => {
                next();
            }).catch(() => {next();});
        return;
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

export default router;