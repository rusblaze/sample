<?php
/**
 * User: aivanov
 * Date: 16.08.16
 * Time: 13:27
 */
namespace Ai\CoreDomain\User;

class UserId
{
    private $value;

    public function __construct($value)
    {
        $this->value = (string) $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getValue();
    }
}