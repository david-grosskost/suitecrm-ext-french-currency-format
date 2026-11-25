# Format monétaire français (`french-currency-format`)

Les montants s'affichent au format français, **le symbole après le nombre** :
« 67 300,00 € » au lieu de « €67 300,00 », dans toute l'interface de SuiteCRM 8 (fiches,
listes, édition) comme dans les écrans historiques (tableaux de bord de l'accueil). Les
séparateurs restent ceux réglés dans SuiteCRM : espace pour les milliers, virgule pour les
décimales.

| Fiche | |
|---|---|
| Version de SuiteCRM | 8.10 (`>=8.10 <9`) |
| Types | `ui` |
| Dépend de | — |
| Installée d'office | non |

## Installer

Sur une instance, par **Administration → Chargeur de module** : l'extension y figure dans
la liste des paquets disponibles, cliquer sur **Installer**, puis recharger l'interface
(Ctrl+F5). Depuis le terminal, dans suitecrm-core :

```bash
make extensions@install SLUGS="french-currency-format"
```

La désinstallation se fait au même endroit.

## Documentation

| Page | Contenu |
|---|---|
| [Utilisation](docs/utilisation.md) | où le format change, ce qui n'est pas concerné |
| [Fonctionnement](docs/fonctionnement.md) | les deux couches : module Angular et surcharge du type « montant » |
| [Développement](docs/developpement.md) | modifier le module Angular, vérifier, tester en local |

Les commandes, la CI et le format des extensions sont décrits dans la documentation de
[suitecrm-devkit](https://github.com/david-grosskost/suitecrm-devkit#documentation).
