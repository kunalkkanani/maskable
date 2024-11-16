<?php

namespace KunalKanani\Maskable;

use KunalKanani\Maskable\Rules\EmailMaskRule;

class MaskRule
{
    public static function email()
    {
        return EmailMaskRule::class;
    }
}

