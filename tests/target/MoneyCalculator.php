<?php

namespace 通貨;

trait MoneyCalculator
{
    public static function eval(array $expr)
    {
        $operator = array_shift($expr);
        $operands = [];

        foreach ($expr as $obj) {
            $operands[] = is_array($obj) ? self::eval($obj) : $obj;
        }

        return self::eval1($operator, ...$operands);
    }

    private static function eval1(string $operator, Money ...$operands): Money
    {
        self::assertOperands(...$operands);

        $amount = null;

        switch ($operator) {
        case '+':
            $amount = self::bcadd($operands[0]->scale, ...$operands);
            break;
        case '-':
            $amount = self::bcsub($operands[0]->scale, ...$operands);
            break;
        case '*':
            $amount = self::bcmul($operands[0]->scale, ...$operands);
            break;
        }

        return new static($amount, $operands[0]->currency, $operands[0]->scale);
    }

    private static function bcadd(int $scale, string ...$operands)
    {
        switch (count($operands)) {
        case 0:
            throw new \MethodCallException();
        case 1:
            return $operands[0];
        default:
            $v1 = array_shift($operands);
            $v2 = array_shift($operands);

            array_unshift($operands, bcadd($v1, $v2, $scale));

            return self::bcadd($scale, ...$operands);
        }
    }

    private static function bcsub(int $scale, string ...$operands)
    {
        switch (count($operands)) {
        case 0:
            throw new \MethodCallException();
        case 1:
            return bcmul($operands[0], -1, $scale);
        default:
            $v1 = array_shift($operands);
            $v2 = array_shift($operands);

            array_unshift($operands, bcsub($v1, $v2, $scale));

            return self::bcsub($scale, ...$operands);
        }
    }

    private static function assertOperands(Money ...$operands): void
    {
        $currencies = [];
        foreach ($operands as $obj) {
            $currencies[] = get_class($obj->currency);
        }
        if (count(array_unique($currencies)) !== 1) {
            throw new Currency\DifferenceException(var_export(array_map(null, $operands, $currencies), true));
        }

        $scales = [];
        foreach ($operands as $obj) {
            $scales[] = $obj->scale;
        }
        if (count(array_unique($scales)) !== 1) {
            throw new Currency\DifferenceException(var_export(array_map(null, $operands, $scales), true));
        }
    }
}
