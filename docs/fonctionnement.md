# Fonctionnement

SuiteCRM 8 affiche les montants par deux chemins différents : son interface Angular et ses
écrans historiques (legacy). L'extension agit sur chacun.

## Fichiers

| Fichier | Rôle |
|---|---|
| `app/src/extension.module.ts` | module Angular : enregistre une locale dont le format monétaire place le symbole après (`#,##0.00 ¤`), et la donne au `CurrencyFormatter` de SuiteCRM |
| `public/` | le module compilé, versionné (l'image n'a pas Node.js) |
| `config/extension.php` | déclare le module à SuiteCRM 8, qui le charge au démarrage de l'interface (`FrenchCurrencyFormat`) |
| `legacy/include/SugarFields/Fields/Currency/SugarFieldCurrency.php` | surcharge du type de champ « montant » des écrans historiques (classe `CustomSugarFieldCurrency`) |
| `legacy/include/french-currency-format/SymbolAfter.php` | la logique de la surcharge : déplace le symbole d'un montant déjà formaté, testée sans SuiteCRM |
| `tests/` | tests de `SymbolAfter` et de `config/extension.php` |

## Interface de SuiteCRM 8

SuiteCRM formate un montant avec la locale Angular, puis remplace lui-même les séparateurs
anglais (« , » pour les milliers, « . » pour les décimales) par ceux de l'utilisateur. Le
module garde donc les séparateurs anglais et ne change que la position du symbole : une
locale `en-suitecrm-symbol-after`, copie de la locale anglaise avec le format `#,##0.00 ¤`.

Au chargement, la console du navigateur affiche
`french-currency-format : chargé, symbole monétaire après le montant`.

## Écrans historiques

SuiteCRM cherche d'abord un type de champ dans `custom/include/SugarFields/Fields/` : il
utilise alors `CustomSugarFieldCurrency`, sous-classe du sien, dont l'affichage en liste
passe le montant par `SymbolAfter::move()` (« €57 600,00 » → « 57 600,00 € »). Un texte
inattendu (pas de symbole, autre format) est rendu tel quel.

## Données en base

Aucune.

## Installation et désinstallation

Pas de script : le Chargeur de module copie les fichiers et lance la réparation rapide. À la
désinstallation, les fichiers sont retirés et SuiteCRM reprend son propre type « montant ».

## Points d'attention

- **Une seule extension** peut surcharger un type de champ : son chemin est imposé par
  SuiteCRM et ne porte pas le slug. Le core refuse une seconde extension qui surchargerait
  `Currency`.
- SuiteCRM 8.10.2 crée le module Angular mais **n'appelle pas sa méthode `init()`** : tout
  se fait dans le constructeur.
- Le module est compilé contre SuiteCRM 8.10.2 : à une nouvelle version de SuiteCRM, le
  recompiler (`make front@build`) et vérifier les montants.
