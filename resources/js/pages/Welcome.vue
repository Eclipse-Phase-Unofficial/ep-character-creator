<template>
<!--    <div id="popup">-->
    <div class="game-box uk-padding-remove">
    <article class="game-box uk-article">
        <h1 class="uk-article-title">Welcome to the Eclipse Phase Character Creator</h1>
        <p class="uk-article-meta">A website for players of the <a href="http://eclipsephase.com/">Eclipse Phase Role Playing Game.</a></p>
<!--    TODO:  This is the welcome page for people who don't have a creator already set up-->
<!--    TODO:  Better Styling-->
<!--    People should be able to create a new character, or load an old one from this page.-->
        <p>It looks like this is your first time here, or your session has expired.</p>

        <div class="uk-flex uk-flex-around uk-flex-wrap">
        <div class="uk-card uk-card-body uk-card-default uk-width-1-3@m uk-text-center game-style">
            <h3 class="uk-card title">Choose!</h3>
            <button class="uk-button uk-button-primary uk-width-1-1 uk-margin-small-bottom" href="#new-character-modal" uk-toggle>New Character</button>
            <button class="uk-button uk-button-secondary uk-width-1-1 uk-margin-small-bottom" href="#load-modal" uk-toggle>Continue?</button>
            <button class="uk-button uk-button-default uk-width-1-1 uk-margin-small-bottom" href="#about-modal" uk-toggle>More Information</button>
            <new-character-modal id="new-character-modal"></new-character-modal>
            <load-dialog id="load-modal"></load-dialog>
            <about id="about-modal"></about>
        </div>

        <div class="uk-card uk-card-body uk-card-default uk-width-1-3@m game-style">
            <h3 class="uk-card title">What is this?</h3>
            <p>
                While Eclipse Phase is a fun game to play, character creation can be complicated, and involves large amounts of math.
                Making matters worse, until the second version of the game is released, each Morph adds even more math.
            </p>
            <p>
                This website solves those challenges by showing almost all options in a (relatively, I'm still working on it) easy to use interface.
                One which does all the math for you.
            </p>
        </div>
        </div>

        <p>Please submit all suggestions and bug reports to the GitHub <a href="https://github.com/Eclipse-Phase-Unofficial/ep-character-creator/issues" target="_blank">Issues</a> page.</p>
        <p>If your session has expired and you were still working on your character feel free to open an issue.  I have ideas on how to fix it, but have prioritized fixing major bugs and modernizing the code.</p>

        <h3>Privacy Policy:</h3>
        <p class="disclaimer">
            In addition, automated bug reporting and Google Analytics are used to create a better site experience, and to reassure myself that all my work was not wasted.
            The creators of this site do not sell your data to anyone.
            This policy may be updated at any time.
        </p>

<!--    Idea on how to fix session expiration:  Timer in Javascript that retrieves the save pack and stores it in localStorage every few minutes.  On page load it checks the store and auto loads it.-->
    </article>
    </div>
</template>

<script>
    import About from "../components/modals/About";
    import LoadDialog from "../components/modals/LoadDialog";
    import NewCharacterModal from "../components/modals/NewCharacterModal";

    export default {
        name: "Welcome",
        components: {About, LoadDialog, NewCharacterModal},
    }
</script>

<style scoped>
    .disclaimer {
        font-size: x-small;
    }
</style>