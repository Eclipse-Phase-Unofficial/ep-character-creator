<template>
    <div id="mainMenu">
        <a href="/api/creator/save">
            <button @click="$ga.event('character', 'save')" class="popupButton">
                Save
            </button>
        </a>
        <button class="popupButton" href="#load-modal" uk-toggle>
            Load
        </button>
        <load-dialog id="load-modal"></load-dialog>
        <button class="popupButton" href="#validation-modal" uk-toggle>
            Check
        </button>
        <validation-check id="validation-modal"></validation-check>
        <div class='dropdown'>
            <button class="popupButton" id="exportButton">
                Export
                &#x25BC;
            </button>
            <div class='dropdown-content'>
                <ul>
                    <li>
                        <a @click="$ga.event('character', 'export', 'pdf')" href="/export/pdf" target="_blank">
                            PDF
                        </a>
                    </li>
                    <li>
                        <a @click="$ga.event('character', 'export', 'txt')" href="/export/txt" target="_blank">
                            TXT
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <button class="popupButton" href="#new-character-modal" uk-toggle>
            New
        </button>
        <new-character-modal id="new-character-modal"></new-character-modal>
        <button class="popupButton" href="#about-modal" uk-toggle>
            About
        </button>
        <about id="about-modal"></about>
    </div>
</template>

<script>
    import About from "./modals/About";
    import NewCharacterModal from "./modals/NewCharacterModal";
    import ValidationCheck from "./modals/ValidationCheck";
    import LoadDialog from "./modals/LoadDialog";
    export default {
        name: "MainMenu",
        components: {LoadDialog, ValidationCheck, NewCharacterModal, About}
    }
</script>

<style scoped>

</style>