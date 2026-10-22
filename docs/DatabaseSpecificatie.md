
# Database Specificatie - Jamin Magazijn

## Project
BE-opdracht 02 - Userstories realiseren en testen

## Database
Naam: laravel

## Tabellen

1. product
2. magazijn
3. leverancier
4. productperleverancier
5. allergeen
6. productperallergeen

## Relaties

- magazijn.ProductId -> product.Id
- productperleverancier.ProductId -> product.Id
- productperleverancier.LeverancierId -> leverancier.Id
- productperallergeen.ProductId -> product.Id
- productperallergeen.AllergeenId -> allergeen.Id

## Stored Procedures

1. GetLeveranciersOverzicht
   Toont alle leveranciers met het aantal verschillende producten.

2. GetGeleverdeProducten
   Toont de geleverde producten van een leverancier.

3. VoegNieuweLeveringToe
   Voegt een nieuwe levering toe en verhoogt de voorraad.

## User Stories

US1: Overzicht leveranciers

US2: Toevoegen producten



## 1. Product

| Kolom | Type | Null | Sleutel |
|---|---|---|---|
| Id | INT | Nee | PK |
| Naam | VARCHAR(100) | Nee | |
| Barcode | VARCHAR(20) | Nee | |
| IsActief | BIT(1) | Nee | |
| Opmerking | VARCHAR(250) | Ja | |
| DatumAangemaakt | DATETIME(6) | Nee | |
| DatumGewijzigd | DATETIME(6) | Nee | |
| Verpakkingseenheid | VARCHAR(100) | Ja | |
| Artikelomschrijving | VARCHAR(255) | Ja | |

## 2. Magazijn

| Kolom | Type | Null | Sleutel |
|---|---|---|---|
| Id | INT | Nee | PK |
| ProductId | INT | Nee | FK |
| VerpakkingsEenheid | DECIMAL(5,2) | Nee | |
| AantalAanwezig | INT | Ja | |
| IsActief | BIT(1) | Nee | |
| Opmerking | VARCHAR(250) | Ja | |
| DatumAangemaakt | DATETIME(6) | Nee | |
| DatumGewijzigd | DATETIME(6) | Nee | |

## 3. Leverancier

| Kolom | Type | Null | Sleutel |
|---|---|---|---|
| Id | INT | Nee | PK |
| Naam | VARCHAR(100) | Nee | |
| ContactPersoon | VARCHAR(100) | Nee | |
| LeverancierNummer | VARCHAR(20) | Nee | |
| Mobiel | VARCHAR(20) | Nee | |
| IsActief | BIT(1) | Nee | |
| Opmerking | VARCHAR(250) | Ja | |
| DatumAangemaakt | DATETIME(6) | Nee | |
| DatumGewijzigd | DATETIME(6) | Nee | |

## 4. ProductPerLeverancier

| Kolom | Type | Null | Sleutel |
|---|---|---|---|
| Id | INT | Nee | PK |
| LeverancierId | INT | Nee | FK |
| ProductId | INT | Nee | FK |
| DatumLevering | DATE | Nee | |
| Aantal | INT | Nee | |
| DatumEerstVolgendeLevering | DATE | Ja | |
| IsActief | BIT(1) | Nee | |
| Opmerking | VARCHAR(250) | Ja | |
| DatumAangemaakt | DATETIME(6) | Nee | |
| DatumGewijzigd | DATETIME(6) | Nee | |
