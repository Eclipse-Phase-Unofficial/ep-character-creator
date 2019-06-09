<template>
    <div :id="id" class="uk-flex-top" v-on:toggle="shown" uk-modal>
        <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical game-style" style="min-width: 80ch">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <div class="uk-text-center">
                <h1><b><u>Load Character</u></b></h1>
                Load a character you saved earlier to make changes, or to update it as you earn rez points.
                <form class="uk-form-horizontal" style="display: inline-block" @submit.prevent="loadCharacter">
                    <input id='uploadedfile' name='uploadedfile' type='file' @change="file = $event.target.files[0]" required>
                    <div>
                        <label class="uk-form-label" for="creationMode">Continue in creation mode?</label>
                        <div class="uk-form-controls">
                            <input class="uk-checkbox" type="checkbox" id="creationMode" style="margin: 0.32em; border: 2px solid black" v-model="creationMode">
                        </div>
                    </div>
                    <div v-if="!creationMode">
                        <label class="uk-form-label" for="rezPoints">Rez points earned</label>
                        <div class="uk-form-controls">
                            <input class="uk-input uk-form-width-xsmall" id="rezPoints" type="number" min="0" v-model="rezPoints">
                        </div>
                    </div>
                    <div v-if="!creationMode">
                        <label class="uk-form-label" for="reputationPoints">Reputation points earned</label>
                        <div class="uk-form-controls">
                            <input class="uk-input uk-form-width-xsmall" id="reputationPoints" type="number" min="0" v-model="reputationPoints">
                        </div>
                    </div>
                    <div v-if="!creationMode">
                        <label class="uk-form-label" for="creditsEarned">Credits earned</label>
                        <div class="uk-form-controls">
                            <input class="uk-input uk-form-width-xsmall" id="creditsEarned" type="number" min="0" v-model="creditsEarned">
                        </div>
                    </div>
                    <br>
                    <button type="submit" class="popupInnerButton">
                        Load
                    </button>
                    <button type="button" class="closeButton popupInnerButton" :href="'#' + id" uk-toggle>
                        Cancel
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import urls from "../../urls";

    export default {
        name: "SaveDialog",
        props: {
            id: String
        },
        data: function () {
            return {
                file: null,
                creationMode: true,
                rezPoints: 0,
                reputationPoints: 0,
                creditsEarned: 0,
            }
        },
        methods: {
            // This happens whenever the Modal is shown (via UiKit)
            shown: function (event) {
                ga('set', 'page', '/load');
                ga('send', 'pageview');
            },
            loadCharacter: function (event) {
                // Max size of 8MB.  If we're hitting this limit there's a problem.
                if(this.file.size > 8388608)
                {
                    alert('File is too large. Are you sue this is a valid character file?');
                    return
                }
                let me = this;
                startLoading();
                readJsonFile(this.file)
                    .then(json => {
                        axios.post(urls.load, {
                            'file': json,
                            'creationMode': me.creationMode,
                            'rezPoints': me.rezPoints,
                            'reputationPoints': me.reputationPoints,
                            'creditsEarned': me.creditsEarned,
                        })
                            .then(response => {
                                endLoading();
                                //TODO:  Don't reload, just close everything and update as appropriate on load finishing
                                location.reload();
                            })
                            .catch(error => {
                                endLoading();
                                console.log('Error Loading Character');
                                console.log(error);
                                if (error.response){
                                    alert(error.response.data.message);
                                }
                            });
                    }).catch(error => {
                        endLoading();
                        alert(error.message);
                });
            }
        }
    }
</script>

<style scoped>

</style>