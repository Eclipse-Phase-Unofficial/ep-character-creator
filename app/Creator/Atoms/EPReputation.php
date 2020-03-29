<?php
declare(strict_types=1);

namespace App\Creator\Atoms;

use App\Models\Reputation;

/**
 * The players reputation with a single faction
 *
 * This is how much a particular factionion likes you and can be used like a currency.
 *
 * @author reinhardt
 */
class EPReputation extends EPAtom{

    /**
     * @var Reputation
     */
    protected $model;

    /**
     * @var int
     */
    public $value;

    /**
     * @var int
     */
    public $morphMod;

    /**
     * @var int
     */
    public $traitMod;
    /**
     * @var int
     */
    public $backgroundMod;
    /**
     * @var int
     */
    public $factionMod;
    /**
     * @var int
     */
    public $softgearMod;
    /**
     * @var int
     */
    public $psyMod;

    /**
     * @var int
     */
    public $maxValue;
    public $maxValueMorphMod;
    public $maxValueTraitMod;
    public $maxValueBackgroundMod;
    public $maxValueFactionMod;
    public $maxValueSoftgearMod;
    public $maxValuePsyMod;

    public $absoluteValueMorphMod;
    public $absoluteValueTraitMod;
    public $absoluteValueBackgroundMod;
    public $absoluteValueFactionMod;
    public $absoluteValueSoftgearMod;
    public $absoluteValuePsyMod;

    function getMaxValue(){
        return  $this->maxValue + $this->maxValueMorphMod + $this->maxValueTraitMod +
                $this->maxValueBackgroundMod + $this->maxValueFactionMod +
                $this->maxValueSoftgearMod + $this->maxValuePsyMod;
    }
    function getAbsoluteValue(){
        $max = $this->absoluteValueMorphMod;
        $max = min($max,$this->absoluteValueTraitMod);
        $max = min($max,$this->absoluteValueBackgroundMod);
        $max = min($max,$this->absoluteValueFactionMod);
        $max = min($max,$this->absoluteValueSoftgearMod);
        $max = min($max,$this->absoluteValuePsyMod);
        return $max;
    }
    function getSavePack(): array
    {
        $savePack = parent::getSavePack();

        $savePack['value'] =  $this->value;
        $savePack['morphMod'] =  $this->morphMod;
        $savePack['traitMod'] =  $this->traitMod;
        $savePack['backgroundMod'] =  $this->backgroundMod;
        $savePack['factionMod'] =  $this->factionMod;
        $savePack['softgearMod'] =  $this->softgearMod;
        $savePack['psyMod'] =  $this->psyMod;
        $savePack['maxValue'] =  $this->maxValue;

        return $savePack;
    }

    /**
     * @param array $an_array
     * @return EPReputation
     */
    public static function __set_state(array $an_array)
    {
        $object = new self(Reputation::whereName((string)$an_array['name']));
        parent::set_state_helper($object, $an_array);

        $object->value         = (int)$an_array['value'];
        $object->morphMod      = (int)$an_array['morphMod'];
        $object->traitMod      = (int)$an_array['traitMod'];
        $object->backgroundMod = (int)$an_array['backgroundMod'];
        $object->factionMod    = (int)$an_array['factionMod'];
        $object->softgearMod   = (int)$an_array['softgearMod'];
        $object->psyMod        = (int)$an_array['psyMod'];
        $object->maxValue      = (int)$an_array['maxValue'];

        return $object;
    }

    /**
     * EPReputation constructor.
     * @param Reputation $model
     */
    function __construct(Reputation $model)
    {
        parent::__construct("Unused", "");
        $this->model = $model;

        $this->value = 0;
        $this->morphMod = 0;
        $this->traitMod = 0;
        $this->backgroundMod = 0;
        $this->factionMod = 0;
        $this->softgearMod = 0;
        $this->psyMod = 0;
        $this->maxValue = config('epcc.RepMaxPoint');
    }
    function getValue(){
        return $this->value + $this->morphMod + $this->traitMod + $this->backgroundMod + $this->factionMod + $this->softgearMod + $this->psyMod;
    }

    public function getName(): string
    {
        return $this->model->name;
    }

    public function getDescription(): string
    {
        return $this->model->description;
    }
}
