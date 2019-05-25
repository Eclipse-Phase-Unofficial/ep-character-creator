<template>
    <div :id="id" class="uk-flex-top" v-on:toggle="shown" uk-modal>
        <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical popup" style="min-width: 80ch">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <div class="popup_contents">
                <h1><b><u>Character Validation</u></b></h1>
                <table align="center" style="text-align: left">
                    <tr v-for="validator in validators" v-bind:key="validator.name">
                        <td v-if="validator.isValid">
                            <span class='valideIcone icon-checkmark-circle'></span>
                        </td>
                        <td v-if="!validator.isValid">
                            <span class='invalidIcone icon-cancel-circle'></span>
                        </td>
                        <td>
                            {{validator.name}}
                        </td>
                        <td v-if="!validator.isValid" v-html="validator.errorMessage"></td>
                    </tr>
                </table>
<!--                Banner if the character is valid or not-->
                <h1><b>
                    <span v-if="isValid"><span class='valideIcone icon-checkmark-circle'></span> Character is valid</span>
                    <span v-if="!isValid"><span class='invalidIcone icon-cancel-circle'></span> Character is NOT valid</span>
                </b></h1>
            </div>
        </div>
    </div>
</template>

<script>
    import urls from "../urls";

    export default {
        name: "ValidationCheck",
        props: {
            id: String
        },
        data: function () {return{
            isValid: false,
            validators: {
                // name: '',
                // isValid: false,
                // errorMessage: ''
            }
        }},
        methods: {
            // This happens whenever the Modal is shown (via UiKit)
            shown: function (event) {
                // ga('set', 'page', '/validate');
                // ga('send', 'pageview');
                axios.get(urls.validate)
                    .then(response => {
                        this.isValid = response.data.isValid;
                        this.validators = response.data.validators;
                    })
                    .catch(error => {
                        console.log('Error Validating data');
                        console.log(error)
                    });
            }
        }
    }
</script>

<style scoped>

</style>