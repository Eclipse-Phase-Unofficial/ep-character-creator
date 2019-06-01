<template>
    <div>
        <!-- Ego/Morph MENU - STATIC CONTENT-->
        <section id="primary" class="panel">
            <nav id="main-nav">
                <panel-one></panel-one>
            </nav>
        </section>

        <!-- AJAX Panels-->
        <section id="secondary" class="panel"></section>
        <section id="tertiary" class="panel"></section>
        <section id="quaternary" class="panel"></section>

        <!-- REMAINAING POINTS - DYNAMIC CONTENT-->
        <points-tracker></points-tracker>
        <main-menu></main-menu>
    </div>
</template>

<script>
    export default {
        name: "Main"
    }
</script>

<style scoped>

</style>