# Events Booking System for Delapre Abbey (Toru Digital)
https://www.toru.digital/

## Emails for Login
admin@example.com
john.smith@example.com
# Password for all Accounts
password123

## Installation Commands Below
### powershell terminal
```
cd src
docker compose up -d --build
```
### Install PHP deps inside container
```
docker compose exec app composer install
```

### Laravel setup and images storage
```
docker compose exec app php artisan key:generate
```
```
docker compose exec app php artisan storage:link
```

## Import database 
### if database doesnt exist
```
docker exec -it laravel_db mysql -u root -proot -e "CREATE DATABASE laravel;"
```
### import the tables and the data
```
Get-Content backup.sql | docker exec -i laravel_db mysql -u root -proot laravel
```

### Install and run tailwind CSS
```
docker compose exec app npm install 
```
```
docker compose exec app npm run dev
```
### Migration
```
docker compose exec app php artisan migrate
```
### Seeding
```
docker compose exec app php artisan db:seed --class=EventSeeder
```
### **WARNING: fresh migration (full reset)**
```
docker compose exec app php artisan migrate:fresh
```
### Video Link for install just in case
https://youtu.be/2bgdMe4JzrM


### Project running slowly? Follow these commands in order:
**Plan cache routes and views:**

```
docker compose exec app php artisan config:cache
```

**Cache routes:**
```
docker compose exec app php artisan route:cache
```

**Cache views:**
```
docker compose exec app php artisan view:cache
```

**Restart docker:**
```
docker compose down
```
```
docker compose up -d
```

**Reinstall dependencies:**
```
docker compose exec app composer install
```

**Recache:**
```
docker compose exec app php artisan optimize
```

