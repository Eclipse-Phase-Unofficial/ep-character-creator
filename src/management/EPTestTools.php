<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class EPTestTools{
    function showGeneralValues($cc){
        echo '</br><table border = 1>';
        if (isset($cc->character->ego->background)){
            echo '<tr><td bgcolor="cyan">Background</td><td>'.$cc->character->ego->background->name.'</td></tr>';
        }
        if (isset($cc->character->ego->faction)){
            echo '<tr><td bgcolor="cyan">Faction</td><td>'.$cc->character->ego->faction->name.'</td></tr>';
        }        
        echo '<tr><td bgcolor="cyan">Creation Points</td><td>'.$cc->getCreationPoint().'</td></tr>';
        echo '<tr><td bgcolor="cyan">Credits</td><td>'.$cc->getCredit().'</td></tr>';
        echo '<tr><td bgcolor="cyan">Aptitudes Points</td><td>'.$cc->aptitudePoints.'</td></tr>';
        echo '<tr><td bgcolor="cyan">Reputation Points</td><td>'.$cc->getReputationPoints().'</td></tr>';
        echo '</table></br>';         
    }
    function getAptByAbreviation($listApts,$abr){
        foreach ($listApts as $ap){
            if (strcmp($ap->abbreviation,$abr) == 0){
                return $ap;
            }
        }
        return null;
    }
    function showAptitudes($apts){        
        echo '</br><table border = 1>';
        echo '<tr bgcolor="cyan">';
        echo '<td>COG</td>
            <td>COO</td>
            <td>INT</td>
            <td>REF</td>
            <td>SAV</td>
            <td>SOM</td>
            <td>WIL</td>';
        echo '</tr><tr>';
        echo '<td>'.$this->getAptByAbreviation($apts,EPAptitude::$COGNITION)->value.'</td><td>'.$this->getAptByAbreviation($apts,EPAptitude::$COORDINATION)->value.'</td><td>'.$this->getAptByAbreviation($apts,EPAptitude::$INTUITION)->value.
                    '</td><td>'.$this->getAptByAbreviation($apts,EPAptitude::$REFLEXS)->value.'</td><td>'.$this->getAptByAbreviation($apts,EPAptitude::$SAVVY)->value.'</td><td>'.$this->getAptByAbreviation($apts,EPAptitude::$SOMATICS)->value.
                    '</td><td>'.$this->getAptByAbreviation($apts,EPAptitude::$WILLPOWER)->value;
        echo '</tr><tr>';
        echo '<td>'.$this->getAptByAbreviation($apts,EPAptitude::$COGNITION)->getValue().'</td><td>'.$this->getAptByAbreviation($apts,EPAptitude::$COORDINATION)->getValue().'</td><td>'.$this->getAptByAbreviation($apts,EPAptitude::$INTUITION)->getValue().
                    '</td><td>'.$this->getAptByAbreviation($apts,EPAptitude::$REFLEXS)->getValue().'</td><td>'.$this->getAptByAbreviation($apts,EPAptitude::$SAVVY)->getValue().'</td><td>'.$this->getAptByAbreviation($apts,EPAptitude::$SOMATICS)->getValue().
                    '</td><td>'.$this->getAptByAbreviation($apts,EPAptitude::$WILLPOWER)->getValue();
        echo '</tr><tr>';        
        echo '</tr>';
        echo '</table></br>';         
    }
    function showSkills($skills){
        echo '</br><table border = 1>';
        echo '<tr bgcolor="cyan">';
        echo '<td>Prefix</td>
            <td>Name</td>
            <td>Abbreviation</td>
            <td>Base value</td>
            <td>Linked Apt Value</td>
            <td>Morph Mod</td>
            <td>Trait Mod</td>
            <td>Background Mod</td>
            <td>Faction Mod</td>
            <td>Psy Mod</td>
            <td>Final Value</td>
            <td>Skill Type</td>
            <td>Groups</td>';
        foreach ($skills as $sk){
            echo '<tr>';
            if ($sk->defaultable == EPSkill::$DEFAULTABLE){
                $ast = '*';
            }else{
                $ast = '';
            } 
            echo '<td>'.$sk->prefix.'</td>
                <td>'.$sk->name.'</td>
                <td>'.$sk->linkedApt->abbreviation.'</td>
                <td>'.$sk->baseValue.'</td>
                <td>'.$sk->linkedApt->getValue().'</td>
                <td>'.$sk->morphMod.'</td>
                <td>'.$sk->traitMod.'</td>
                <td>'.$sk->backgroundMod.'</td>
                <td>'.$sk->factionMod.'</td>
                <td>'.$sk->psyMod.'</td>
                <td>'.$sk->getValue().'</td>
                <td>'.$sk->skillType.'</td>
                <td>'; 
            if (is_array($sk->groups)){
                $n = 0;
                foreach ($sk->groups as $g){
                    if ($n>0){
                        echo ', ';
                    }
                    echo $g;
                    $n++;
                }                
            }
            echo '</td></tr>';
        }
        echo '</table></br>';
    }
    function getStatsByAbreviation($listStats,$abr){
        foreach ($listStats as $st){
            if (strcmp($st->abbreviation,$abr) == 0){
                return $st;
            }
        }
        return null;
    }
    function showStats($stats){        
        echo '</br><table border = 1>';
        echo '<tr bgcolor="cyan">';
        echo '<td>Mox</td>
            <td>TT</td>
            <td>LUC</td>
            <td>IR</td>
            <td>WT</td>
            <td>DUR</td>
            <td>DR</td>
            <td>INIT</td>
            <td>SPEED</td>
            <td>DB</td>';
        echo '</tr><tr>';
        echo '<td>'.$this->getStatsByAbreviation($stats,'MOX')->value.'</td><td>'.$this->getStatsByAbreviation($stats,'TT')->value.'</td><td>'.$this->getStatsByAbreviation($stats,'LUC')->value.
                    '</td><td>'.$this->getStatsByAbreviation($stats,'IR')->value.'</td><td>'.$this->getStatsByAbreviation($stats,'WT')->value.'</td><td>'.$this->getStatsByAbreviation($stats,'DUR')->value.
                    '</td><td>'.$this->getStatsByAbreviation($stats,'DR')->value.'</td><td>'.$this->getStatsByAbreviation($stats,'INI')->value.'</td><td>'.
                    $this->getStatsByAbreviation($stats,'SPD')->value.'</td><td>'.$this->getStatsByAbreviation($stats,'DB')->value;
        echo '</tr>';
        echo '</table></br>';         
    }
    function getRepsByAbreviation($listReps,$abr){
        foreach ($listReps as $st){
            if (strcmp($st->name,$abr) == 0){
                return $st;
            }
        }
        return null;
    }
    function showReputations($reps){
        echo '</br><table border = 1>';
        echo '<tr bgcolor="cyan">';
        echo '<td>@ - Rep</td>
            <td>C - Rep</td>
            <td>E - Rep</td>
            <td>F - Rep</td>
            <td>G - Rep</td>
            <td>I - Rep</td>
            <td>R - Rep</td>';
        echo '</tr><tr>';
        echo '<td>'.$this->getRepsByAbreviation($reps,'@-Rep')->value.'</td><td>'.$this->getRepsByAbreviation($reps,'C-Rep')->value.'</td><td>'.$this->getRepsByAbreviation($reps,'E-Rep')->value.
                    '</td><td>'.$this->getRepsByAbreviation($reps,'F-Rep')->value.'</td><td>'.$this->getRepsByAbreviation($reps,'G-Rep')->value.'</td><td>'.$this->getRepsByAbreviation($reps,'I-Rep')->value.
                    '</td><td>'.$this->getRepsByAbreviation($reps,'R-Rep')->value;
        echo '</tr>';
        echo '</table></br>';         
    }
    function showBackgrounds($bkg){
        echo '</br><table border = 1>';
        echo '<tr bgcolor="cyan">';
        echo '<td>Name</td>
            <td>Type</td>';
        foreach ($bkg as $b){
            echo '<tr>';
            echo '<td>'.$b->name.'</td>
                <td>'.$b->backgroundType.'</td>';
            echo '</tr>';
        }
        echo '</table></br>';  
    }
    function showTraits($traits){
        if (is_array($traits)){
            echo '</br><table border = 1>';
            echo '<tr bgcolor="cyan">';
            echo '<td>Name</td>
                <td>Positive/Negative</td>
                <td>Ego/Morph</td>
                <td>CP Cost</td>
                <td>Bonus/Malus</td>';
            foreach ($traits as $t){
                echo '<tr>'; 
                echo '<td>'.$t->name.'</td>
                    <td>'.$t->traitPosNeg.'</td>
                    <td>'.$t->traitEgoMorph.'</td>
                    <td>'.$t->cpCost.'</td>
                    <td>'; 
                if (is_array($t->bonusMalus)){
                    $n = 0;
                    foreach ($t->bonusMalus as $bm){
                        if ($n>0){
                            echo ', ';
                        }
                        echo $bm->name;
                        $n++;
                    }                
                }
                echo '</td></tr>';
            }
            echo '</table></br>';            
        }
    }
    function showMotivations($motivs){
        echo '</br><table border = 1>';
        echo '<tr bgcolor="cyan">';
        echo '<td>Motivation</td>';
        foreach ($motivs as $m){
            echo '<tr>';
            echo '<td>'.$m.'</td>';
            echo '</tr>';
        }
        echo '</table></br>';  
    }
    function showMorphs($mophs){
        if (!is_array($mophs)){
            return;
        }
        echo '</br><table border = 1>';
        echo '<tr bgcolor="cyan">';
        echo '<td>Name</td>
            <td>Type</td>
            <td>Age</td>
            <td>Gender</td>
            <td>Max Apt</td>
            <td>Durability</td>
            <td>CP Cost</td>
            <td>Traits</td>
            <td>Gears</td>
            <td>BonusMalus</td>
            <td>Description</td>';
        foreach ($mophs as $m){
            echo '<tr>';
            echo '<td>'.$m->name.'</td>
                <td>'.$m->morphType.'</td>
                <td>'.$m->age.'</td>
                <td>'.$m->gender.'</td>
                <td>'.$m->maxApptitude.'</td>
                <td>'.$m->durability.'</td>
                <td>'.$m->cpCost.'</td>
                <td>'; 
            if (is_array($m->traits)){
                $n = 0;
                foreach ($m->traits as $t){
                    if ($n>0){
                        echo ', ';
                    }
                    echo $t->name;
                    $n++;
                }                
            }
            echo '</td><td>';
            if (is_array($m->gears)){
                $n = 0;
                foreach ($m->gears as $t){
                    if ($n>0){
                        echo ', ';
                    }
                    echo $t->name;
                    $n++;
                }                
            }
            echo '</td><td>';   
            if (is_array($m->bonusMalus)){
                $n = 0;
                foreach ($m->bonusMalus as $t){
                    if ($n>0){
                        echo ', ';
                    }
                    echo $t->name;
                    $n++;
                }                
            }
            echo '</td><td>'.$m->description.'</td></tr>';
        }
        echo '</table></br>';
    }
    function showGears($gears){
        echo '</br><table border = 1>';
        echo '<tr bgcolor="cyan">';
        echo '<td>Name</td>
            <td>Amor Kinetic</td>
            <td>Armor Energy</td>
            <td>Degat</td>
            <td>Armor Penetration</td>
            <td>CostType</td>
            <td>Bonus malus</td>
            <td>Description</td></tr>';
        foreach ($gears as $g){
            echo '<tr>';
            echo '<td>'.$g->name.'</td>
                  <td>'.$g->armorKinetic.'</td>
                  <td>'.$g->armorEnergy.'</td>
                  <td>'.$g->degat.'</td>
                  <td>'.$g->armorPenetration.'</td>
                  <td>'.$g->getCost().'</td>
                  <td>';
            if (is_array($g->bonusMalus)){
                $n = 0;
                foreach ($g->bonusMalus as $t){
                    if ($n>0){
                        echo ', ';
                    }
                    echo $t->name;
                    $n++;
                }                
            }
            echo '</td>
                  <td>'.$g->description.'</td></tr>';
        }
        echo '</table></br>';  
    }
    function showValidations($cc){
        $cc->checkValidation();
  
        if (is_array($cc->validation->items)){
            echo '</br><table border = 1>';
            echo '<tr bgcolor="cyan">';
            echo '<td>Item</td>
                <td>Completed</td>';
            foreach ($cc->validation->items as $key => $value){
                if ($value){
                    $val = 'OK';
                }else{
                    $val = 'NOT';
                }
                echo '<tr>'; 
                echo '<td>'.$key.'</td>
                    <td>'.$val.'</td></tr>';
            }
            echo '</table></br>';            
        }
    }
}
?>
