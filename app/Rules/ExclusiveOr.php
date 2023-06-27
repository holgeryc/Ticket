<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ExclusiveOr implements Rule
{
    protected $firstField;
    protected $secondField;

    public function __constcodigo_oficinat($firstField, $secondField)
    {
        $this->firstField = $firstField;
        $this->secondField = $secondField;
    }

    public function passes($attribute, $value)
    {
        $firstValue = request($this->firstField);
        $secondValue = request($this->secondField);

        return ($firstValue && !$secondValue) || (!$firstValue && $secondValue);
    }

    public function message()
    {
        return 'Debe proporcionar uno de los campos '.$this->firstField.' o '.$this->secondField;
    }
}