<template>
    <div id="app">
        <vue-cookie-accept-decline
                v-if="inEU()"
                :elementId="'gaPanel'"
                :debug="false"
                :position="'bottom-right'"
                :type="'floating'"
                :disableDecline="true"
                :transitionName="'slideFromBottom'">
        </vue-cookie-accept-decline>
        <router-view></router-view>
    </div>
</template>

<script>
    import inEU from '@segment/in-eu';
    import VueCookieAcceptDelcine from 'vue-cookie-accept-decline/src/vue-cookie-accept-decline';

    export default {
        name: "App.vue",
        components: {'vue-cookie-accept-decline': VueCookieAcceptDelcine},
        methods: {
            inEU: () => inEU(),
        }
    }
</script>

<style scoped>
    #app{
        margin: 0;
        width: 100%;
        height: 100%;
    }
</style>