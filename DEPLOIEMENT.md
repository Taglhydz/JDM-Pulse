# Déploiement sur AlwaysData

JDM Pulse est hébergé sur l'offre gratuite d'[AlwaysData](https://www.alwaysdata.com) (100 Mo, le projet en utilise environ 45).

## Architecture

Un seul site PHP, sur une seule adresse (`https://<compte>.alwaysdata.net`) :

```
laravel-jdmpulse/public/     ← racine du site
├── index.php, .htaccess     ← Laravel : répond à /api/...
└── index.html, static/...   ← build React, copié au déploiement
```

- `/api/...` est traité par l'API Laravel.
- Toutes les autres URL (`/`, `/discover`...) renvoient l'`index.html` de React (`routes/web.php`), React Router affiche ensuite la bonne page.
- Le front appelle l'API en relatif (`/api/...`, voir `react-jdmpulse/.env.production`) : pas de CORS, pas de nom de compte dans le code.

Dans la suite, remplacer `<compte>` par le nom du compte AlwaysData.

## Première mise en ligne

### 1. Configurer AlwaysData (interface d'administration)

1. **Environment > PHP** : choisir PHP 8.4 (version utilisée par la ligne de commande en SSH).
2. **Databases > MySQL** : créer la base `<compte>_jdmpulse` et noter l'utilisateur et son mot de passe.
3. **Remote access > SSH** : vérifier que l'utilisateur SSH `<compte>` peut se connecter par mot de passe (ou ajouter une clé SSH).
4. **Web > Sites** : modifier le site existant `<compte>.alwaysdata.net` :
   - type : **PHP**, version 8.4 ;
   - répertoire racine : `/jdmpulse/laravel-jdmpulse/public/` (relatif au dossier personnel).

### 2. Installer le backend (en SSH)

```bash
ssh <compte>@ssh-<compte>.alwaysdata.net

git clone https://github.com/Taglhydz/JDM-Pulse.git jdmpulse
cd jdmpulse/laravel-jdmpulse
composer install --no-dev --optimize-autoloader

cp .env.production.example .env
nano .env        # remplir <compte>, DB_PASSWORD, ADMIN_PASSWORD (Ctrl+O pour enregistrer, Ctrl+X pour quitter)
php artisan key:generate

php artisan migrate --seed --force
php artisan optimize
```

- `--seed` crée le catalogue et le compte démo. En production, aucun mot de passe du seeder n'est connu : le superAdmin utilise `ADMIN_PASSWORD`, les autres comptes de test un mot de passe aléatoire.
- Si `composer` n'existe pas : `curl -sS https://getcomposer.org/installer | php` puis `php composer.phar install --no-dev --optimize-autoloader`.

### 3. Envoyer le front (depuis le PC, à la racine du dépôt)

```powershell
powershell -ExecutionPolicy Bypass -File scripts\deploy-front.ps1 -Compte <compte>
```

Le script builde React, l'envoie dans `public/` et nettoie les anciens fichiers. Le mot de passe SSH est demandé deux fois (envoi puis extraction).

### 4. Vérifier

| URL | Attendu |
|---|---|
| `https://<compte>.alwaysdata.net/` | Le catalogue, avec le bandeau visiteur |
| `https://<compte>.alwaysdata.net/api/cars/all` | Du JSON |
| Bouton « Essayer le compte démo » | Connexion, puis likes et collection disponibles |
| `https://<compte>.alwaysdata.net/up` | Page « Application up » de Laravel |

## Mises à jour

Après avoir mergé une release dans `main` et poussé sur GitHub :

```bash
# backend, en SSH
bash ~/jdmpulse/scripts/deploy-back.sh
```

```powershell
# front, depuis le PC (si le front a changé)
powershell -ExecutionPolicy Bypass -File scripts\deploy-front.ps1 -Compte <compte>
```

## Dépannage

| Symptôme | Piste |
|---|---|
| Erreur 500 | Lire `~/jdmpulse/laravel-jdmpulse/storage/logs/laravel.log` |
| Une modification du `.env` n'est pas prise en compte | `php artisan optimize` (la config est en cache) |
| « Front non déployé » | Lancer l'étape 3 |
| Erreur composer sur la version de PHP | `php -v` en SSH, puis régler **Environment > PHP** |
| Les données de démo sont en désordre | `php artisan db:seed --force` (remet le catalogue à zéro) |
