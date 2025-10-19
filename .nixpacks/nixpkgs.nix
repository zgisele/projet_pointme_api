{ pkgs }:

pkgs.mkShell {
  buildInputs = [
    pkgs.php
    pkgs.phpPackages.composer
    pkgs.phpPackages.fpm        # <- indispensable pour PHP-FPM
    pkgs.phpPackages.pdo_mysql  # <- pour MySQL
    pkgs.nodejs_18
    pkgs.npm
    pkgs.nginx
  ];
}
