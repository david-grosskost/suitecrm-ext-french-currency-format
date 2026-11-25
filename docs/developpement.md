# Développement

Les commandes `make`, la CI et le format des extensions viennent de
[suitecrm-devkit](https://github.com/david-grosskost/suitecrm-devkit#documentation), à la
version de `composer.json`. Cette page ne décrit que ce qui est propre à l'extension.

## Modifier le module Angular

| Dossier | Contenu |
|---|---|
| `app/` | les sources (`app/src/extension.module.ts`) |
| `public/` | le module compilé, **versionné** |
| `config/extension.php` | sa déclaration à SuiteCRM (`remoteEntry`, `remoteName`) |

Après toute modification de `app/` :

```bash
make front@build   # compile public/ contre les sources de SuiteCRM (Docker, Node 22)
```

puis commiter `app/` **et** `public/` ensemble : la CI recompile le module
(`make front@check`) et refuse un `public/` qui ne correspond pas à `app/`. La première
compilation prépare les sources de SuiteCRM (quelques minutes), les suivantes prennent
moins d'une minute.

## Vérifier

```bash
make extension@validate && make quality@lint && make quality@stan && make test@unit
make front@check
make package@build && make package@scan
```

## Tester sur une instance locale

1. `make package@build`, puis sur l'instance locale de suitecrm-core, **Administration →
   Chargeur de module** : **Choisir un fichier** (`build/french-currency-format-<version>.zip`),
   **Télécharger**, **Installer**.
2. Recharger avec Ctrl+F5 ; la console du navigateur doit afficher
   `french-currency-format : chargé`.
3. Vérifier un montant sur une fiche Affaire, dans la liste des Affaires et dans un tableau
   de bord de l'accueil : « 67 300,00 € ».
4. Dans suitecrm-core : `make test@regression`.

## Publier une version

Pull request relue, puis merge (squash) : release-please ouvre la pull request
« chore(main): release x.y.z ». La merger crée le tag, la release et son paquet. Penser à la
`version` de `config/extension.php`, affichée par SuiteCRM. suitecrm-core la récupère avec
`make composer@update PACKAGES="david-grosskost/suitecrm-ext-french-currency-format"`.

Mettre à jour `README.md` et `docs/` dans la même pull request que le changement qu'ils
décrivent.
