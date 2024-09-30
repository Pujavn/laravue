cd your-repo-name
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
npm run dev

For production, you can compile the front-end assets by running:
npm run build
