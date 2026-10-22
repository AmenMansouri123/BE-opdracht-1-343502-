-- Stored Procedure: GetLeveranciersOverzicht
-- User Story 1: Overzicht leveranciers

DROP PROCEDURE IF EXISTS GetLeveranciersOverzicht;

DELIMITER $$

CREATE PROCEDURE GetLeveranciersOverzicht()
BEGIN
    SELECT
        l.Id,
        l.Naam,
        l.ContactPersoon,
        l.LeverancierNummer,
        l.Mobiel,
        COUNT(DISTINCT ppl.ProductId) AS AantalProducten
    FROM leverancier AS l
    LEFT JOIN productperleverancier AS ppl
        ON l.Id = ppl.LeverancierId
    GROUP BY
        l.Id,
        l.Naam,
        l.ContactPersoon,
        l.LeverancierNummer,
        l.Mobiel
    ORDER BY AantalProducten DESC;
END$$

DELIMITER ;
