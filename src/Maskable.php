<?php

namespace KunalKanani\Maskable;

use KunalKanani\Maskable\Rules\MaskRuleInterface;

trait Maskable
{
    protected $returnRawAttributes = false;

    public function unmasked()
    {
        $this->returnRawAttributes = true;
        return $this;
    }

    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if ($this->returnRawAttributes) {
            return $value;
        }

        $maskable = $this->getMaskableAttributes();
        if (isset($maskable[$key])) {
            return $this->applyMaskRule($maskable[$key], $value);
        }

        return $value;
    }

    public function toArray()
    {
        $array = parent::toArray();

        foreach ($this->getMaskableAttributes() as $attribute => $rule) {
            if (array_key_exists($attribute, $array)) {
                $array[$attribute] = $this->applyMaskRule($rule, $array[$attribute]);
            }
        }

        return $array;
    }

    protected function getMaskableAttributes()
    {
        return property_exists($this, 'maskable') && is_array($this->maskable)
            ? $this->maskable
            : [];
    }

    protected function applyMaskRule($rule, string $value): string
    {
        if (is_string($rule) && class_exists($rule)) {
            $ruleInstance = new $rule();
            if ($ruleInstance instanceof MaskRuleInterface) {
                return $ruleInstance->apply($value);
            }
        }

        throw new \InvalidArgumentException("Invalid masking $rule provided for value: $value");
    }
}
