<template>
    <div id="mainMenu">
        <button class="popupButton" id="saveButton">
            Save
        </button>
        <button class="popupButton" href="#load-modal" uk-toggle>
            Load
        </button>
        <load-dialog id="load-modal"></load-dialog>
        <button class="popupButton" href="#validation-modal" uk-toggle>
            Check
        </button>
        <validation id="validation-modal"></validation>
        <div class='dropdown'>
            <button class="popupButton" id="exportButton">
                Export
                &#x25BC;
            </button>
            <div class='dropdown-content'>
                <ul>
                    <li><a id="exportPdfButton" href="/export/pdf" target="_blank">PDF</a></li>
                    <li><a id="exportTxtButton" href="/export/txt" target="_blank">TXT</a></li>
                </ul>
            </div>
        </div>
        <button class="popupButton" id="settingsButton">
            Reset
        </button>
        <button class="popupButton" href="#about-modal" uk-toggle>
            About
        </button>
        <about id="about-modal"></about>
    </div>
</template>

<script>
    export default {
        name: "MainMenu"
    }
</script>

<style scoped>

</style>