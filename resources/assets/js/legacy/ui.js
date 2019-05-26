// ================= Static UI elements =================
// This is for elements that the user should see regardless of where they are on the site.
// For example, the menu buttons, and Points remaining display
// Requires: popup.js
//**************************************************
//**********Menu Buttons**********

// Load button
$("#loadButton").click(function() {
    loadPopup("#load_popup","popup-contents/load");
});

// Save button
$("#saveButton").click(function() {
    loadPopup("#save_popup","popup-contents/save_popup");
});

// Reset button
$("#settingsButton").click(function() {
        loadPopup("reload_popup","popup-contents/reset",false,true);
});

//**************************************************

// Adjust the points remaining display
function setRemainingPoint(ajaxData){
    window.app.$store.dispatch('highLevel/getHighLevelCreatorInfo');
}
