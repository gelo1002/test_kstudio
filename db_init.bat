echo 'running database initialization script'

echo ' ******** dumping any previous autoload configs *********** '
composer dumpautoload

echo ' ******** migrating all core users, roles, jobs & permissions tables ******** '
php artisan migrate

echo ' ******** creating passport clients and keys ... please wait ***** '
php artisan passport:install

echo ' ******** seeding all tables with initial data values ******** '
php artisan db:seed

echo ' ******** creating create the symbolic link ***** '
php artisan storage:link

echo ' ******** taking laravel out of maintenance mode ******** '
php artisan up