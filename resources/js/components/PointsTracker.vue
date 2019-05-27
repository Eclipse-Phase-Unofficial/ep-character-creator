<template>
    <section class="points">
        <div v-if="creationPointsRemaining === 'N/A'" id='RZ'>RZ <span id="rez_remain" class="rest">{{rezPointsRemaining}}</span>
            <span class="btnhelp slowTransition icon-question" :title="description"></span>
        </div>
        <div v-else>
            <div id='CP'>CP <span id="creation_remain" class="rest">{{creationPointsRemaining}}</span>
                <span class="btnhelp slowTransition icon-question" :title="description"></span>
            </div>
            <div id='AP'>AP <span id="aptitude_remain" class="rest">{{aptitudePointsRemaining}}</span></div>
            <div id='ASR'>ASR <span id="asr_remain" class="rest">{{minimumActiveSkill}}</span></div>
            <div id='KSR'>KSR <span id="ksr_remain" class="rest">{{minimumKnowledgeSkill}}</span></div>
        </div>
        <div id='RP'>RP <span id="reputation_remain" class="rest">{{reputationPointsRemaining}}</span></div>
        <div id='CR'>CR <span id="credit_remain" class="rest">{{credits}}</span></div>
    </section>
</template>

<script>
    import {mapState} from 'vuex'

    export default {
        name: "PointsTracker",
        data: function () {return {
            'description': `
                Values used for the creation of your character.<br>
                CP = Creation Points<br>
                AP = Aptitude Points<br>
                RP = Reputation Points<br>
                CR = Credit<br>
                ASR = Minimum Active Skill Points Remaining<br>
                KSR = Minimum Knowledge Skill Points Remaining<br>
                RZ = Rez Points
                `.replace(/(\r\n|\n|\r)/gm,'')
        }},
        computed: mapState('highLevel', [
            'rezPointsRemaining',
            'creationPointsRemaining',
            'aptitudePointsRemaining',
            'minimumActiveSkill',
            'minimumKnowledgeSkill',
            'reputationPointsRemaining',
            'credits',
        ])
    }
</script>

<style scoped>

</style>