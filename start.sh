#!/bin/bash
set -e

# Démarre PHP-FPM en arrière-plan
php-fpm -D

# Démarre Caddy
caddy run --config /etc/caddy/Caddyfile --adapter caddyfile
