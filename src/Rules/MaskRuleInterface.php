<?php

namespace KunalKanani\Maskable\Rules;

/**
 * Defines a contract for masking rules to be applied to string values.
 */
interface MaskRuleInterface
{
    /**
     * Apply a masking rule to a given string value.
     *
     * @param string $value The original string to be masked.
     * @return string The masked string.
     */
    public function apply(string $value): string;
}