<template>
    <div :id="id" class="uk-flex-top" v-on:toggle="shown" uk-modal>
        <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical game-style" style="min-width: 80ch">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <div class="popup_contents">
                <h1><b><u>New Character</u></b></h1>
                <p>Create a character from scratch.</p>
                <p>Please choose how many creation points (Min 700 CP) to create your character with.  1000 is the recommended "normal" amount.</p>
                <form class="uk-form-horizontal" style="display: inline-block" @submit.prevent="newCharacter">
                    <div>
                        <label class="uk-form-label" for="creationPoints">Creation Points to start with</label>
                        <div class="uk-form-controls">
                            <input class="uk-input uk-form-width-small" id="creationPoints" type="number" min="700" v-model="creationPoints">
                        </div>
                    </div>
                    <br>
                    <button type="submit" class="popupInnerButton">
                        Begin!
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
        name: "NewCharacterModal",
        props: {
            id: String
        },
        data: function () {return {
            'creationPoints': 1000,
        }},
        methods: {
            // This happens whenever the Modal is shown (via UiKit)
            shown: function (event) {
                ga('set', 'page', '/new');
                ga('send', 'pageview');
            },
            newCharacter: function (event) {
                startLoading();
                axios.post(urls.creator, {
                    'creationPoints': this.creationPoints,
                })
                    .then(response => {
                        endLoading();
                        //TODO:  Don't reload, just close everything and update as appropriate on load finishing
                        location.reload();
                    })
                    .catch(error => {
                        endLoading();
                        console.log('Error Creating Character');
                        console.log(error);
                        if (error.response){
                            alert(error.response.data.message);
                        }
                    });
            }
        }
    }
</script>

<style scoped>

</style>