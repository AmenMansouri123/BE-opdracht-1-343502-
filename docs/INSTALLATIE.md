# Installatie - Magazijn Jamin

## Benodigdheden

Voor dit project zijn de volgende programma's nodig:

- PHP
- Composer
- Laravel
- MySQL
- WAMP
- Git

## Project starten

Open PowerShell in de projectmap.

Start de Laravel applicatie met:

C:\wamp64\bin\php\php8.5.6\php.exe artisan serve

De applicatie is daarna beschikbaar via:

http://127.0.0.1:8000

## Database

De applicatie gebruikt de database:

laravel

De databasebestanden staan in de map:

db

Hier staan onder andere:

- create_script.sql
- insert_data.sql
- laravel.sql

## Database importeren

De database kan via MySQL of phpMyAdmin worden geïmporteerd met het bestand:

db/laravel.sql
