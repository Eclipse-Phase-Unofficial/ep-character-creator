//These are helper functions to make life easier.
//They may only be used in one or two places, but really don't belong there.

/**
 * Parse a JSON file
 * @param {File} file
 * @returns {Promise}
 */
window.readJsonFile = function (file) {
    return new Promise((resolve, reject) => {
        let reader = new FileReader();
        reader.onload = function (event) {
            if (event.target.readyState !== 2){
                reject(Error('Unable to read file!'));
            }
            let json = {};
            try {
                json = JSON.parse(event.target.result);
            } catch (e) {
                reject(Error('The selected file did not contain parsable JSON data!'));
            }
            resolve(json);
        };
        reader.readAsText(file);
    });
};