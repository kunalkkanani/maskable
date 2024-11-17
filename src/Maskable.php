<?php

namespace KunalKanani\Maskable;

use KunalKanani\Maskable\Rules\MaskRuleInterface;

/**
 * Provides functionality to mask attributes of an Eloquent model based on specified rules.
 * This trait can be used to selectively mask certain attributes when converting a model
 * to an array or JSON, useful for hiding or altering sensitive data before it reaches the client.
 */
trait Maskable
{
    /**
     * Controls whether attributes should be returned in their raw (unmasked) form.
     *
     * @var bool
     */
    protected $returnRawAttributes = false;

    /**
     * Retrieve the maskable attributes defined on the model.
     *
     * If the model has a 'maskable' property and it's an array, it returns it;
     * otherwise, it returns an empty array.
     *
     * @return array
     */
    protected function getMaskableAttributes(): array
    {
        return property_exists($this, 'maskable') && is_array($this->maskable)
            ? $this->maskable
            : [];
    }

    /**
     * Determines if masking should be applied to this model instance based on custom logic.
     * Override this method in your model if you need conditional masking.
     *
     * @return bool
     */
    protected function shouldMask(): bool
    {
        return true;
    }

    /**
     * Disables masking of the attributes, causing attributes to be returned in their original (unmasked) form.
     *
     * @return $this Enables fluent interface by returning the model instance.
     */
    public function unmasked()
    {
        $this->returnRawAttributes = true;
        return $this;
    }

    /**
     * Retrieves an attribute from the model. If masking is enabled and the attribute is maskable,
     * the masked value is returned instead of the original value.
     *
     * @param string $key
     * @return mixed
     */
    public function getAttribute($key): mixed
    {
        $value = parent::getAttribute($key);

        if ($this->returnRawAttributes || !$this->shouldMask()) {
            return $value;
        }

        $maskable = $this->getMaskableAttributes();
        if (isset($maskable[$key])) {
            return $this->applyMaskRule($maskable[$key], $value);
        }

        return $value;
    }

    /**
     * Converts the model instance to an array. If masking is enabled, attributes defined in the
     * 'maskable' array are masked according to their corresponding rules before the array is returned.
     *
     * @return array
     */
    public function toArray(): array
    {
        $array = parent::toArray();

        if (!$this->shouldMask()) {
            return $array;
        }

        foreach ($this->getMaskableAttributes() as $attribute => $rule) {
            if (array_key_exists($attribute, $array)) {
                $array[$attribute] = $this->applyMaskRule($rule, $array[$attribute]);
            }
        }

        return $array;
    }

    /**
     * Applies the specified masking rule to the given value.
     *
     * @param mixed $rule The rule that should be applied (class name of a masking rule).
     * @param string $value Original attribute value that needs to be masked.
     * @return string Masked value.
     * @throws InvalidArgumentException
     */
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
