import urls from "../../urls";

export default {
    namespaced: true,
    state: {
        //These are the points displayed in the upper right corner
        'rezPointsRemaining': 0,
        'creationPointsRemaining': 0,
        'aptitudePointsRemaining': 0,
        'minimumActiveSkill': 0,    //The number of points that **must** be spent on Active Skills
        'minimumKnowledgeSkill': 0, //The number of points that **must** be spent on Knowledge Skills
        'reputationPointsRemaining': 0,
        'credits': 0,
    },
    mutations: {
        //Update everything that the user must spend
        setRequired(state, payload) {
            state.rezPointsRemaining = payload.rezPointsRemaining;
            state.creationPointsRemaining = payload.creationPointsRemaining;
            state.aptitudePointsRemaining = payload.aptitudePointsRemaining;
            state.minimumActiveSkill = payload.minimumActiveSkill;
            state.minimumKnowledgeSkill = payload.minimumKnowledgeSkill;
            state.reputationPointsRemaining = payload.reputationPointsRemaining;
        },
        setCredits(state, credits) {
            state.credits = credits;
        }
    },
    actions: {
        getHighLevelCreatorInfo (context) {
            return new Promise((resolve, reject) => {
                axios.get(urls.creator)
                    .then(response => {
                        let data = response.data;
                        context.commit('setRequired', {
                            rezPointsRemaining: data.rez_remain,
                            creationPointsRemaining: data.creation_remain,
                            aptitudePointsRemaining: data.aptitude_remain,
                            minimumActiveSkill: data.asr_remain,
                            minimumKnowledgeSkill: data.ksr_remain,
                            reputationPointsRemaining: data.reputation_remain,
                        });
                        context.commit('setCredits', data.credits);
                        resolve(context)
                    })
                    .catch(error => {
                        console.log('Error Getting Creator');
                        console.log(error);
                        reject(error)
                    });
            });
        }
    },
    getters: {}
}