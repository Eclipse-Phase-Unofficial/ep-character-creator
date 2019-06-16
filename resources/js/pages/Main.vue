<template>
    <div>
        <!-- Ego/Morph MENU -->
        <section id="primary" class="panel">
            <nav id="main-nav">
                <panel-one></panel-one>
            </nav>
        </section>

        <!-- AJAX Panels-->
        <section id="secondary" class="panel"></section>
        <section id="tertiary" class="panel"></section>
        <section id="quaternary" class="panel"></section>

        <points-tracker></points-tracker>
        <main-menu></main-menu>
    </div>
</template>

<script>
    import MainMenu from "../components/MainMenu";
    import PointsTracker from "../components/PointsTracker";
    import PanelOne from "../components/PanelOne";
    export default {
        name: "Main",
        components: {PanelOne, PointsTracker, MainMenu}
    }
</script>

<style scoped>

</style>
