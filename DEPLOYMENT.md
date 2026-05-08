# Guide de déploiement

## Prérequis
- PHP 8.2+
- Composer
- Node.js 18+
- MySQL / MariaDB ou autre base compatible
- Serveur web Apache / Nginx avec PHP-FPM

## Configuration
1. Copier l’environnement :
   ```bash
   cp .env.example .env
   ```
2. Mettre à jour les variables dans `.env` :
   - `APP_NAME=GestionPharmacie`
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `APP_URL=https://votre-domaine.com`
   - `DB_CONNECTION=mysql`
   - `DB_HOST=127.0.0.1`
   - `DB_PORT=3306`
   - `DB_DATABASE=nom_de_la_base`
   - `DB_USERNAME=utilisateur`
   - `DB_PASSWORD=mot_de_passe`

## Installation
```bash
composer install --optimize-autoloader --no-dev
npm install
npm run build
php artisan key:generate
php artisan migrate --force
php artisan storage:link
```

## Mise en production
- Assurez-vous que `APP_DEBUG=false` dans `.env`
- Utilisez `php artisan config:cache`, `php artisan route:cache`, `php artisan view:cache`
- Donnez les droits en écriture sur :
  - `storage/`
  - `bootstrap/cache/`

## Hébergement recommandé
- Laravel Forge / Cloudways / RunCloud
- Serveur VPS avec Nginx + PHP-FPM
- Base de données MySQL ou MariaDB

## Points importants
- N’exposez jamais vos fichiers `.env`
- Redémarrez PHP-FPM après mise à jour de `.env`
- Vérifiez les logs dans `storage/logs/laravel.log`
