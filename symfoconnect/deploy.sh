#!/bin/bash

echo "Installation des dépendances..."
composer install --no-dev --optimize-autoloader

echo "Exécution des migrations..."
php bin/console doctrine:migrations:migrate --no-interaction --env=prod

echo "Nettoyage et préchauffage du cache..."
php bin/console cache:clear --env=prod
php bin/console cache:warmup --env=prod

echo "Compilation des assets..."
php bin/console asset-map:compile --env=prod

echo "Déploiement terminé !"
