<?php

namespace App\CustomLibs\ValidatorLib\Traits;

use App\CustomLibs\ValidatorLib\Validator;
use Exception;

trait ValidationRulesHandler
{

    protected array $allRules = [];
    protected bool $setDefaultRules = true;

    /////////////////////////////////////////////////////
    //NOTE :
    // If You Want To change Data Array , And Your Validation Rules Needs Some values from That Data Array
    // Every Rules determination methods must be called after data array Setting method
    /////////////////////////////////////////////////////

    /**
     * @param array $ValidationRules
     * @return Validator|ValidationRulesHandler
     * @throws  Exception
     * This Method is used to set Request Key Validations array
     * It is useful to set it by this method when you want to set it later (not in constructor)
     */
    public function OnlyRules(array $ValidationRules): self
    {
        $newRules = [];
        $this->AllRules();
        foreach ($ValidationRules as $rule) {
            if (!array_key_exists($rule, $this->allRules))
            {
                throw new Exception("The Given Rule '" . $rule . "' Is Not Defined In The Given Request Form Class !");
            }
            $newRules[$rule] = $this->allRules[$rule];
        }
        $this->allRules = $newRules;
        return $this;
    }

    /**
     * @param array $NonDesiredValidationRules
     * @return Validator|ValidationRulesHandler
     */
    public function ExceptRules(array $NonDesiredValidationRules): self
    {
        $this->AllRules();
        foreach ($NonDesiredValidationRules as $rule)
        {
            if(array_key_exists($rule , $this->allRules)){unset($this->allRules[$rule]) ;}
        }
        return $this;
    }

    /**
     * @return Validator|ValidationRulesHandler
     */
    public function AllRules() : self
    {
        $this->allRules = $this->requestFormOb->rules($this->data);
        $this->setDefaultRules = false;
        return $this;
    }

    /**
     * @return Validator|ValidationRulesHandler
     */
    public function UnsetRulesAndCancel() : self
    {
        $this->allRules = [];
        $this->setDefaultRules = false;
        return $this;
    }
}
