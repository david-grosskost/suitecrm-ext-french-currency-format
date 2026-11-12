# Format monétaire français

Extension SuiteCRM 8 : **les montants s'affichent avec le symbole après le nombre**,
« 67 300,00 € » au lieu de « €67 300,00 ». Les séparateurs restent ceux réglés dans
SuiteCRM (espace pour les milliers, virgule pour les décimales).

Deux parties, une par couche de SuiteCRM 8 :

- **l'interface de SuiteCRM 8** (fiches, édition, listes) : un **module Angular** que
  SuiteCRM charge au démarrage, sans recompiler son front ;
- **l'ancienne interface** affichée dans SuiteCRM 8 (dashlets de l'accueil, listes
  legacy) : une surcharge du type de champ « montant »
  (`legacy/include/SugarFields/Fields/Currency/SugarFieldCurrency.php`, classe
  `CustomSugarFieldCurrency`) que SuiteCRM utilise à la place du sien. Une seule
  extension peut surcharger ce type.

Non concernés : les rapports, PDF et modèles d'e-mails, qui formatent les montants
eux-mêmes.

## Activer

Dans le `.env.local` de l'instance, ajouter `french-currency-format` à `EXTENSIONS`, puis
redémarrer. Recharger l'interface (Ctrl+F5) : la console du navigateur affiche
`french-currency-format : chargé`.

## Modifier le module

| Dossier | Contenu |
|---|---|
| `app/` | les sources du module Angular (`app/src/extension.module.ts`) |
| `public/` | le module construit, **commité** : suitecrm-core l'installe tel quel |
| `config/extension.php` | la déclaration du module à SuiteCRM (`remoteEntry`, `remoteName`) |

Après toute modification de `app/` :

```bash
make front@build   # construit public/ contre les sources de SuiteCRM (Docker, Node 22)
```

puis commiter `app/` **et** `public/` ensemble : la CI reconstruit le module
(`make front@check`) et refuse un `public/` qui ne correspond pas à `app/`. La première
construction télécharge les sources de SuiteCRM et leurs dépendances (quelques minutes) ;
les suivantes prennent moins d'une minute.

Bon à savoir : SuiteCRM crée le module mais n'appelle pas sa méthode `init()` ; ce qu'il
doit faire au chargement se fait dans son constructeur.

## Vérifier

```bash
make extension@validate && make quality@lint && make quality@stan && make test@unit && make front@check
```
