<?php

namespace KunalKanani\Maskable\Rules;

use Illuminate\Support\Str;
use KunalKanani\Maskable\Rules\MaskRuleInterface;

class EmailMaskRule implements MaskRuleInterface
{
    public function apply(string $value): string
    {
        [$user, $domain] = explode('@', $value);
        return Str::mask($user, config('maskable.mask_character'), 1) . '@' . $domain;
    }
}