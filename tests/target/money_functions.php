<?php

namespace 通貨;

function USD(string $amount)
{
    return new Money($amount, USD::getInstance(), 10);
}

function JPY(string $amount)
{
    return new Money($amount, JPY::getInstance(), 10);
}

