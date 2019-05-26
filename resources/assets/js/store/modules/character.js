import urls from "../../urls";

export default {
    namespaced: true,
    state: {
        'playerName': '',
        'realAge': 0,
        'birthGender': '',
        'note': '',
        'currentMorphUid': '',
    },
    mutations: {
        setCharacter(state, payload) {
            state.playerName = payload.playerName;
            state.realAge = payload.realAge;
            state.birthGender = payload.birthGender;    //TODO:  Add some handling for 'M', 'm', 'F', 'f' to Male/Female
            state.note = payload.note;
            state.currentMorphUid = payload.currentMorphUid;
        },
    },
    actions: {
        geCharacter (context) {
            axios.get(urls.character)
                .then(response => {
                    let data = response.data;
                    context.commit('setCharacter', data);
                })
                .catch(error => {
                    console.log('Error Getting Character');
                    console.log(error)
                });
        }
    },
    getters: {}
}