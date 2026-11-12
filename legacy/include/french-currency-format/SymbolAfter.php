<?php

declare(strict_types=1);

namespace SuiteCrmExt\FrenchCurrencyFormat;

/**
 * Moves the currency symbol after the amount in an amount formatted by the
 * legacy layer of SuiteCRM, which always puts it in front (format_place_symbol
 * in modules/Currencies/Currency.php): "€57 600,00" → "57 600,00 €". The
 * separators stay those of the user. Anything else (no symbol, unexpected
 * text) is returned as it is.
 */
final class SymbolAfter
{
    // Leading whitespace of the template, the symbol (€, $, CHF…), the space
    // after it if any, the amount with its sign, trailing whitespace.
    private const string PATTERN = '/^(\s*)([^\d\s&<>+-][^\d\s&<>+-]*)(?:&nbsp;|\x{00A0}|\x{202F}|\s)?'
        . '(-?\d(?:[\d.,\s\x{00A0}\x{202F}]*\d)?)(\s*)$/u';

    public static function move(string $formatted): string
    {
        if (preg_match(self::PATTERN, $formatted, $m) !== 1) {
            return $formatted;
        }

        return $m[1] . $m[3] . '&nbsp;' . $m[2] . $m[4];
    }
}
