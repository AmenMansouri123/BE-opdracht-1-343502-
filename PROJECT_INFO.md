# Magazijn Jamin

Dit project is gemaakt voor Backend Opdracht 1.

## Project

In deze applicatie kan de gebruiker het magazijnoverzicht van Jamin bekijken.

De producten worden gesorteerd op barcode.

De applicatie bevat twee belangrijke onderdelen:

- Leveringsinformatie van producten
- Allergeneninformatie van producten

## User Story 1 - Leveringsinformatie

De gebruiker kan vanuit het productoverzicht op het vraagteken bij Leverantie Info klikken.

Daarna worden de gegevens van de leverancier en de leveringen van het gekozen product getoond.

Wanneer een product geen voorraad heeft, wordt een melding getoond met de datum van de eerstvolgende levering.

Na 4 seconden wordt de gebruiker teruggestuurd naar het productoverzicht.

## User Story 2 - Allergeneninformatie

De gebruiker kan vanuit het productoverzicht op het rode kruis bij Allergenen Info klikken.

Daarna worden de allergenen van het gekozen product getoond.

Wanneer het product geen allergenen bevat, wordt de volgende melding getoond:

"In dit product zitten geen stoffen die een allergische reactie kunnen veroorzaken"

Na 4 seconden wordt de gebruiker teruggestuurd naar het productoverzicht.

## Technieken

- Laravel
- PHP
- MySQL
- Blade
- MVC
- Git
- GitHub

## Database

De database bevat de volgende tabellen:

- Product
- Allergeen
- Leverancier
- Magazijn
- ProductPerAllergeen
- ProductPerLeverancier

De SQL-bestanden staan in de map `db`.

De Database Specificatie Tabel staat in de map `docs`.

## Applicatie starten

Start de Laravel-server met:

C:\wamp64\bin\php\php8.5.6\php.exe artisan serve

Open daarna:

http://127.0.0.1:8000

## Auteur

Amen Mansouri