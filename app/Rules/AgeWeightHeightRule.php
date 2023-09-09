<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AgeWeightHeightRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        // child
        $age = request('age');
        if ($age >= 4 && $age < 10 )
        {
            // weight
            if ($attribute == 'weight')
            {
                if ($value > 30)
                {
                    $fail('The :attribute unbelievable');
                }
            }
            // height
            if ($attribute == 'height')
            {
                if ($value > 150)
                {
                    $fail('The :attribute unbelievable');
                }
            }
        }

        // youth
        if ($age >= 10  && $age <= 43 )
        {
            // weight
            if ($attribute == 'weight')
            {
                if ($value < 31)
                {
                    $fail('The :attribute unbelievable');
                }
            }
            // height
            if ($attribute == 'height')
            {
                if ($value < 100)
                {
                    $fail('The :attribute unbelievable');
                }
            }
        }
    }


}
