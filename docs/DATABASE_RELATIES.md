# Database Relaties - Magazijn Jamin

Dit document beschrijft de relaties tussen de tabellen van de database.

## Relaties

### Product en Magazijn

`Magazijn.ProductId` verwijst naar `Product.Id`.

Een magazijnrecord hoort bij een product.

### Product en Allergeen

De tabellen Product en Allergeen zijn gekoppeld via de tabel ProductPerAllergeen.

`ProductPerAllergeen.ProductId` verwijst naar `Product.Id`.

`ProductPerAllergeen.AllergeenId` verwijst naar `Allergeen.Id`.

Hierdoor kan een product meerdere allergenen bevatten.

### Product en Leverancier

De tabellen Product en Leverancier zijn gekoppeld via de tabel ProductPerLeverancier.

`ProductPerLeverancier.ProductId` verwijst naar `Product.Id`.

`ProductPerLeverancier.LeverancierId` verwijst naar `Leverancier.Id`.

In ProductPerLeverancier worden ook de leveringsdatum, het aantal en de eerstvolgende leveringsdatum opgeslagen.

## Tabellen

De database bevat de volgende tabellen:

- Product
- Magazijn
- Allergeen
- ProductPerAllergeen
- Leverancier
- ProductPerLeverancier