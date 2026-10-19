
-- User Story 2 - Nieuwe levering toevoegen

DROP PROCEDURE IF EXISTS VoegNieuweLeveringToe;

DELIMITER $$

CREATE PROCEDURE VoegNieuweLeveringToe(
    IN p_LeverancierId INT,
    IN p_ProductId INT,
    IN p_Aantal INT,
    IN p_DatumEerstVolgendeLevering DATE
)
BEGIN
    DECLARE v_IsActief BIT(1);
    DECLARE v_LeveringId INT;
    DECLARE v_MagazijnId INT;
    DECLARE v_Gevonden BOOLEAN DEFAULT TRUE;

    DECLARE CONTINUE HANDLER FOR NOT FOUND
        SET v_Gevonden = FALSE;

    START TRANSACTION;

    SELECT IsActief
    INTO v_IsActief
    FROM product
    WHERE Id = p_ProductId
    FOR UPDATE;

    IF NOT v_Gevonden THEN
        ROLLBACK;
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Product bestaat niet';
    END IF;

    SET v_Gevonden = TRUE;

    SELECT Id
    INTO v_LeveringId
    FROM productperleverancier
    WHERE LeverancierId = p_LeverancierId
      AND ProductId = p_ProductId
    ORDER BY Id DESC
    LIMIT 1
    FOR UPDATE;

    IF NOT v_Gevonden THEN
        ROLLBACK;
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Leverancier levert dit product niet';
    END IF;

    IF v_IsActief = b'0' THEN
        ROLLBACK;
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Product is niet actief';
    END IF;

    IF p_Aantal IS NULL OR p_Aantal <= 0
       OR p_DatumEerstVolgendeLevering IS NULL THEN
        ROLLBACK;
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Ongeldige leveringsgegevens';
    END IF;

    SET v_Gevonden = TRUE;

    SELECT Id
    INTO v_MagazijnId
    FROM magazijn
    WHERE ProductId = p_ProductId
    LIMIT 1
    FOR UPDATE;

    IF NOT v_Gevonden THEN
        ROLLBACK;
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Geen magazijnrecord gevonden';
    END IF;

    UPDATE magazijn
    SET AantalAanwezig = COALESCE(AantalAanwezig, 0) + p_Aantal,
        DatumGewijzigd = NOW(6)
    WHERE Id = v_MagazijnId;

    UPDATE productperleverancier
    SET DatumLevering = CURDATE(),
        DatumEerstVolgendeLevering = p_DatumEerstVolgendeLevering,
        DatumGewijzigd = NOW(6)
    WHERE Id = v_LeveringId;

    COMMIT;
END$$

DELIMITER ;
