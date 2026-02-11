# Useful URLs
http://localhost:8000/
http://localhost:8000/register
http://localhost:8000/login
http://localhost:8000/auctions
http://localhost:8000/items
# Emails for Login
admin@example.com
john.smith@example.com
# Password for all Accounts
password123

# Installation Commands Below
# powershell terminal
# From project root
docker compose up -d --build

# Install PHP deps inside container
docker compose exec app composer install

# Laravel setup and images storage
docker compose exec app php artisan key:generate
docker compose exec app php artisan storage:link

# Import database 
# if database doesnt exist
docker exec -it laravel_db mysql -u root -proot -e "CREATE DATABASE laravel;"
# import the tables and the data
Get-Content backup.sql | docker exec -i laravel_db mysql -u root -proot laravel

# Install and run tailwind CSS
docker compose exec app npm install 
docker compose exec app npm run dev

# Video Link for install just incase
https://youtu.be/2bgdMe4JzrM