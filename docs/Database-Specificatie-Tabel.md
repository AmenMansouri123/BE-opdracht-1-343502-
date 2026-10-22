# Database Specificatie Tabel - Magazijn Jamin

## Product

| Veldnaam | Datatype | Lengte | NULL | Sleutel | Omschrijving |
|---|---|---|---|---|---|
| Id | INT | - | Nee | PK | Uniek nummer van het product |
| Naam | VARCHAR | 100 | Nee | - | Naam van het product |
| Barcode | VARCHAR | 20 | Nee | - | Barcode van het product |
| IsActief | BIT | - | Nee | - | Geeft aan of het record actief is |
| Opmerking | VARCHAR | 250 | Ja | - | Eventuele opmerking |
| DatumAangemaakt | DATETIME | 6 | Nee | - | Datum waarop het record is aangemaakt |
| DatumGewijzigd | DATETIME | 6 | Nee | - | Datum waarop het record is gewijzigd |


## Allergeen

| Veldnaam | Datatype | Lengte | NULL | Sleutel | Omschrijving |
|---|---|---|---|---|---|
| Id | INT | - | Nee | PK | Uniek nummer van het allergeen |
| Naam | VARCHAR | 100 | Nee | - | Naam van het allergeen |
| Omschrijving | VARCHAR | 250 | Nee | - | Omschrijving van het allergeen |
| IsActief | BIT | - | Nee | - | Geeft aan of het record actief is |
| Opmerking | VARCHAR | 250 | Ja | - | Eventuele opmerking |
| DatumAangemaakt | DATETIME | 6 | Nee | - | Datum waarop het record is aangemaakt |
| DatumGewijzigd | DATETIME | 6 | Nee | - | Datum waarop het record is gewijzigd |


## Leverancier

| Veldnaam | Datatype | Lengte | NULL | Sleutel | Omschrijving |
|---|---|---|---|---|---|
| Id | INT | - | Nee | PK | Uniek nummer van de leverancier |
| Naam | VARCHAR | 100 | Nee | - | Naam van de leverancier |
| ContactPersoon | VARCHAR | 100 | Nee | - | Naam van de contactpersoon |
| LeverancierNummer | VARCHAR | 20 | Nee | - | Nummer van de leverancier |
| Mobiel | VARCHAR | 20 | Nee | - | Mobiele nummer van de contactpersoon |
| IsActief | BIT | - | Nee | - | Geeft aan of het record actief is |
| Opmerking | VARCHAR | 250 | Ja | - | Eventuele opmerking |
| DatumAangemaakt | DATETIME | 6 | Nee | - | Datum waarop het record is aangemaakt |
| DatumGewijzigd | DATETIME | 6 | Nee | - | Datum waarop het record is gewijzigd |


## Magazijn

| Veldnaam | Datatype | Lengte | NULL | Sleutel | Omschrijving |
|---|---|---|---|---|---|
| Id | INT | - | Nee | PK | Uniek nummer van het magazijnrecord |
| ProductId | INT | - | Nee | FK | Verwijst naar Product.Id |
| VerpakkingsEenheid | DECIMAL | 5,2 | Nee | - | Verpakkingseenheid van het product |
| AantalAanwezig | INT | - | Ja | - | Aantal producten dat aanwezig is |
| IsActief | BIT | - | Nee | - | Geeft aan of het record actief is |
| Opmerking | VARCHAR | 250 | Ja | - | Eventuele opmerking |
| DatumAangemaakt | DATETIME | 6 | Nee | - | Datum waarop het record is aangemaakt |
| DatumGewijzigd | DATETIME | 6 | Nee | - | Datum waarop het record is gewijzigd |


## ProductPerAllergeen

| Veldnaam | Datatype | Lengte | NULL | Sleutel | Omschrijving |
|---|---|---|---|---|---|
| Id | INT | - | Nee | PK | Uniek nummer van de koppeling |
| ProductId | INT | - | Nee | FK | Verwijst naar Product.Id |
| AllergeenId | INT | - | Nee | FK | Verwijst naar Allergeen.Id |
| IsActief | BIT | - | Nee | - | Geeft aan of het record actief is |
| Opmerking | VARCHAR | 250 | Ja | - | Eventuele opmerking |
| DatumAangemaakt | DATETIME | 6 | Nee | - | Datum waarop het record is aangemaakt |
| DatumGewijzigd | DATETIME | 6 | Nee | - | Datum waarop het record is gewijzigd |


## ProductPerLeverancier

| Veldnaam | Datatype | Lengte | NULL | Sleutel | Omschrijving |
|---|---|---|---|---|---|
| Id | INT | - | Nee | PK | Uniek nummer van de koppeling |
| LeverancierId | INT | - | Nee | FK | Verwijst naar Leverancier.Id |
| ProductId | INT | - | Nee | FK | Verwijst naar Product.Id |
| DatumLevering | DATE | - | Nee | - | Datum waarop het product is geleverd |
| Aantal | INT | - | Nee | - | Aantal geleverde producten |
| DatumEerstVolgendeLevering | DATE | - | Ja | - | Verwachte datum van de volgende levering |
| IsActief | BIT | - | Nee | - | Geeft aan of het record actief is |
| Opmerking | VARCHAR | 250 | Ja | - | Eventuele opmerking |
| DatumAangemaakt | DATETIME | 6 | Nee | - | Datum waarop het record is aangemaakt |
| DatumGewijzigd | DATETIME | 6 | Nee | - | Datum waarop het record is gewijzigd |


# Relaties

- Magazijn.ProductId → Product.Id
- ProductPerAllergeen.ProductId → Product.Id
- ProductPerAllergeen.AllergeenId → Allergeen.Id
- ProductPerLeverancier.ProductId → Product.Id
- ProductPerLeverancier.LeverancierId → Leverancier.Id
