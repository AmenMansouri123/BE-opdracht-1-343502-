-- Stored Procedure: GetGeleverdeProducten
-- User Story 1 - Geleverde producten

DROP PROCEDURE IF EXISTS GetGeleverdeProducten;

DELIMITER $$

CREATE PROCEDURE GetGeleverdeProducten(
    IN p_LeverancierId INT
)
BEGIN
    SELECT
        p.Id,
        p.Naam,
        m.VerpakkingsEenheid,
        m.AantalAanwezig,
        MAX(ppl.DatumLevering) AS LaatsteLevering
    FROM productperleverancier AS ppl
    INNER JOIN product AS p
        ON ppl.ProductId = p.Id
    LEFT JOIN magazijn AS m
        ON p.Id = m.ProductId
    WHERE ppl.LeverancierId = p_LeverancierId
    GROUP BY
        p.Id,
        p.Naam,
        m.VerpakkingsEenheid,
        m.AantalAanwezig
    ORDER BY m.AantalAanwezig DESC;
END$$

DELIMITER ;
