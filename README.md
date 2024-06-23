git clone https://github.com/Frank-128/vaxpro_backend.git 

composer install 

npm install 

php artisan migrate 

php artisan db:seed 

php artisan db:seed HealthWorkerSeeder

php artisan db:seed VaccinationSeeder

php artisan serve

php artisan schedule:work 

php artisan queue:listen 

php artisan reverb:start 
