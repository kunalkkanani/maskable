<?php

namespace KunalKanani\Maskable\Rules;

interface MaskRuleInterface
{
    public function apply(string $value): string;
}