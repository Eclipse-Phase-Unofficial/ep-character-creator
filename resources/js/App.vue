<template>
    <div style="height: 100%; width: 100%;">
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

    export default {
        name: "App.vue",
        methods: {
            inEU: () => inEU(),
        }
    }
</script>

<style scoped>

</style>