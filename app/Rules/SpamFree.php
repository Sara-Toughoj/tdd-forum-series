<?php

namespace App\Rules;

use App\Inspections\Spam;
use Illuminate\Contracts\Validation\Rule;

class SpamFree implements Rule
{

    protected $attribute;

    public function passes($attribute, $value)
    {
        $this->attribute = $attribute;

        try {
            return !resolve(Spam::class)->detect($value);
        } catch (\Exception $e) {
            return false;
        }
    }


    public function message()
    {
        return "The $this->attribute contains spam";
    }
}
