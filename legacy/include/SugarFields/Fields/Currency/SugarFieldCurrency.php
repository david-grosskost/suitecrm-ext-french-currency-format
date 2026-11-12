<?php

// french-currency-format: SuiteCRM loads this file instead of its own
// SugarFieldCurrency (custom/ first, SugarFieldHandler), then uses
// CustomSugarFieldCurrency: the amounts of the legacy lists and dashlets
// (home page) get the symbol after the number, as in the SuiteCRM 8 interface.
// Its path is set by SuiteCRM; the logic is in SymbolAfter, tested.

require_once getcwd() . '/include/SugarFields/Fields/Currency/SugarFieldCurrency.php';
require_once __DIR__ . '/../../../french-currency-format/SymbolAfter.php';

use SuiteCrmExt\FrenchCurrencyFormat\SymbolAfter;

class CustomSugarFieldCurrency extends SugarFieldCurrency
{
    /**
     * @param array<string, mixed> $parentFieldArray
     * @param array<string, mixed> $vardef
     * @param array<string, mixed> $displayParams
     * @param mixed $col
     */
    public function getListViewSmarty($parentFieldArray, $vardef, $displayParams, $col): string
    {
        return SymbolAfter::move(parent::getListViewSmarty($parentFieldArray, $vardef, $displayParams, $col));
    }
}
