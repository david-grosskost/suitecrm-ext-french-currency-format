<?php

declare(strict_types=1);

namespace SuiteCrmExt\FrenchCurrencyFormat\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SuiteCrmExt\FrenchCurrencyFormat\SymbolAfter;

/**
 * The amounts of the legacy lists and dashlets: SuiteCRM puts the symbol in
 * front, the extension moves it after the number.
 */
final class SymbolAfterTest extends TestCase
{
    /** @return iterable<string, array{string, string}> */
    public static function amounts(): iterable
    {
        yield 'montant en euros' => ['€57 600,00', '57 600,00&nbsp;€'];
        yield 'espaces du gabarit gardés' => ["\n\n€57 600,00\n", "\n\n57 600,00&nbsp;€\n"];
        yield 'espace insécable après le symbole' => ['€&nbsp;1 234,50', '1 234,50&nbsp;€'];
        yield 'montant négatif' => ['€-57 600,00', '-57 600,00&nbsp;€'];
        yield 'autre devise, séparateurs anglais' => ['$1,234.50', '1,234.50&nbsp;$'];
        yield 'symbole en lettres' => ['CHF 12,00', '12,00&nbsp;CHF'];
        yield 'symbole déjà après' => ['57 600,00 €', '57 600,00 €'];
        yield 'sans symbole' => ['57 600,00', '57 600,00'];
        yield 'vide' => ['', ''];
        yield 'symbole seul' => ['€', '€'];
        yield 'texte inattendu' => ['<span>€12</span>', '<span>€12</span>'];
    }

    #[DataProvider('amounts')]
    public function testTheSymbolGoesAfterTheAmount(string $legacy, string $expected): void
    {
        self::assertSame($expected, SymbolAfter::move($legacy));
    }

    public function testTheOverrideIsTheOneSuiteCrmLooksFor(): void
    {
        // SugarFieldHandler: custom/include/SugarFields/Fields/Currency/SugarFieldCurrency.php,
        // class CustomSugarFieldCurrency.
        $file = (string) file_get_contents(__DIR__ . '/../legacy/include/SugarFields/Fields/Currency/SugarFieldCurrency.php');

        self::assertStringContainsString('class CustomSugarFieldCurrency extends SugarFieldCurrency', $file);
        self::assertStringContainsString("require_once getcwd() . '/include/SugarFields/Fields/Currency/SugarFieldCurrency.php';", $file);
        self::assertStringContainsString('SymbolAfter::move(', $file);
    }
}
