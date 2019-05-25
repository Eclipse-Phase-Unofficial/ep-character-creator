<template>
    <section class="points">
        <div id='RZ'>RZ <span id="rez_remain" class="rest"></span>
            <span class="btnhelp slowTransition icon-question" :title="description"></span>
        </div>
        <div id='CP'>CP <span id="creation_remain" class="rest"></span>
            <span class="btnhelp slowTransition icon-question" :title="description"></span>
        </div>
        <div id='AP'>AP <span id="aptitude_remain" class="rest"></span></div>
        <div id='ASR'>ASR <span id="asr_remain" class="rest"></span></div>
        <div id='KSR'>KSR <span id="ksr_remain" class="rest"></span></div>
        <div id='RP'>RP <span id="reputation_remain" class="rest"></span></div>
        <div id='CR'>CR <span id="credit_remain" class="rest"></span></div>
    </section>
</template>

<script>
    export default {
        name: "PointsTracker",
        data: function () {return {
            'creation': 0,
            'aptitude': 0,
            'reputation': 0,
            'minimumActiveSkill': 0,
            'minimumKnowledgeSkill': 0,
            'rez': 0,
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
        }}
    }
</script>

<style scoped>

</style>