import urls from "../../urls";

export default {
    namespaced: true,
    state: {
        //These are the points displayed in the upper right corner
        'rezPointsRemaining': 'N/A',
        'creationPointsRemaining': 'N/A',
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
        },
        clearAll(state, payload) {
            state.rezPointsRemaining = 'N/A';
            state.creationPointsRemaining = 'N/A';
            state.aptitudePointsRemaining = 0;
            state.minimumActiveSkill = 0;
            state.minimumKnowledgeSkill = 0;
            state.reputationPointsRemaining = 0;
            state.credits = 0;
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
                        resolve(context);
                    })
                    .catch(error => {
                        if(error.response.status === 401) {
                            console.log('Creator does not exist');
                            context.commit('clearAll');
                            // TODO:  This relies on the Global "app"
                            // This is here because it triggers even if the route did not change
                            app.$router.push({name: 'welcome'});
                            resolve(context);
                            return;
                        }
                        console.log('Error Getting Creator');
                        console.log(error);
                        reject(error);
                    });
            });
        }
    },
    getters: {
        creatorExists: state => {
            return !(state.rezPointsRemaining === 'N/A' && state.creationPointsRemaining === 'N/A');
        }
    },
}