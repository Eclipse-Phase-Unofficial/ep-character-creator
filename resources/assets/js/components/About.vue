<template>
    <div class="popup_contents">
        <h1><b><u> About</u></b></h1>
        <p>Eclipse Phase Character Creator ({{versionName}})</p>
        <p>Version {{version}} ({{releaseDate}})</p>
        <p>A character creator for the <a href="http://eclipsephase.com" target="_blank">Eclipse Phase</a> role-playing game.</p>
        <p>
            Please submit all suggestions and bug reports to the
            <a href="https://github.com/EmperorArthur/ep-character-creator/issues" target="_blank">Issues</a> page.
        </p>
        <p>
            Created by:
            <b>Russell Bewley,</b>
            <b>Stoo Goof,</b>
            <b>Derek Payne,</b>
            <b>Cédric Reinhardt,</b>
            <b>Jigé Pont,</b>
            <b>Olivier Murith,</b>
            <b>Arthur Moore</b>
        </p>
        <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/deed.en_US" target="_blank">
            <img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-sa/3.0/88x31.png" />
        </a>
        <p>
            <small>
                This work is licensed under a
                <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/deed.en_US" target="_blank">
                    Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unsupported License
                </a>
                .
            </small>
        </p>
    </div>
</template>

<script>
    import urls from "../urls";

    export default {
        name: "About",
        data: function () { return {
            //TODO:  Get these via an API
            versionName: '',
            releaseDate: '',
            version: 0
        }},
        mounted() {
            //TODO:  This could be done in VueX and used everywhere
            axios.get(urls.version)
            .then(response => {
                this.version = response.data.version;
                this.versionName = response.data.versionName;
                this.releaseDate = response.data.releaseDate;
            })
            .catch(error => {
                console.log('Error getting version data');
                console.log(error)
            });
        }
    }
</script>

<style scoped>

</style>