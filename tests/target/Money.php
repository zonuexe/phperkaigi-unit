<?php

namespace 通貨;

class Money
{
    use MoneyCalculator;

    /** @var string */
    private $amount;

    /** @var Currency */
    private $currency;

    /** @var int */
    private $scale;

    public function __construct(string $amount, Currency $currency, int $scale)
    {
        $this->amount = $amount;
        $this->currency = $currency;
        $this->scale  = $scale;
    }

    public function add(Money $object)
    {
        return static::eval(['+', $this, $object]);
    }

    public function __toString()
    {
        return $this->amount;
    }
}
