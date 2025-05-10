<?php

namespace App\Models;

class Price
{
    private string $id;
    private string $amount;
    private string $currencyLabel;
    private string $currencySymbol;

    public function __construct(
        string $id,
        string $amount,
        string $currencyLabel,
        string $currencySymbol
    ) {
        $this->id = $id;
        $this->amount = $amount;
        $this->currencyLabel = $currencyLabel;
        $this->currencySymbol = $currencySymbol;
    }

    // Getters
    public function getId(): string
    {
        return $this->id;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getCurrencyLabel(): string
    {
        return $this->currencyLabel;
    }

    public function getCurrencySymbol(): string
    {
        return $this->currencySymbol;
    }
}