Getting Started


Run composer install

Run npm install && npm run build

Duplicate the .env.example file and rename it to .env

Run php artisan migrate

Create the database.sqlite in database directory (if not already created)

Run php artisan migrate

Run php artisan db:seed (Admin user will be created)

Run php artisan key:generate

Run composer run dev

Once you have started the development server, your application will be accessible in your web browser at http://localhost:8000


Admin user credentials:


name: Admin

email:admin@email.com

password:12345678



Technologies used:

Laravel 12

Inertia

Vue3

SQLITE

