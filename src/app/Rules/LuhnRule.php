<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class LuhnRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        if ($value !== (string) (int) $value) {
            return false;
        }
        $newValue = (int) $value;
        $sum = 0;
        $i = 1;
        while ($newValue !== 0) {
            $step = ($i % 2 === 1) ? $newValue % 10 : ($newValue % 10) * 2;
            $sum += ($step > 9) ? $step - 9 : $step;
            $newValue = (int) ($newValue / 10);
            $i++;
        }

        return ($sum % 10 === 0);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute не верный.';
    }
}
