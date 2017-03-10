# BonusMalus (BM)

All the plus and minuses, buffs and debuffs applied to many different things.
These are the heart of the creator.  Every time you see +10 to some skill, or aptitude, that's where it's coming from.

All these items make use of BonusMalus:
* Ai
* Backgrounds
* Factions
* Traits (Both Ego, and Morph)
* Morphs
* Gear, and Soft Gear
* Psi Sleights
* Even other BonusMalus

If a BM has `$bonusMalusType == EPBonusMalus::$MULTIPLE` (aka 'MUL') it has children that need to be processed.
    * All available children are in `bonusMalusTypes`
    * Not all children are always processed.  The number is set by `$multi_occurence`
    * Only children with `$selected == True` are processed.

There are only 6 of these, but it makes up about half of the user display code.

`$targetForChoice` handles the type of choice the user has to make:
    * SAC,  $ON_SKILL_ACTIVE        (0  BM)         User chooses an Active skill.
    * SWP,  $ON_SKILL_WITH_PREFIX   (13 BM)         User chooses any skill with a given prefix. (determined by `$$typeTarget`)
    * SKN,  $ON_SKILL_KNOWLEDGE     (1  BM)         User chooses a Knowledge skill.
    * SAK, $ON_SKILL_ACTIVE_AND_KNOWLEDGE (3  BM)   User chooses any skill.
    * OR,  $ON_REPUTATION           (1  BM)         User chooses a reputation.
    * OA,  $ON_APTITUDE             (7  BM)         User chooses an aptitude.
    * MUL, EPBonusMalus::$MULTIPLE  (6  BM) User chooses from a multiple of options.  THIS IS ALWAYS SET WITH $bonusMalusType == 'MUL'!
    * Blank (All other BMs) The user does not make a decision)
