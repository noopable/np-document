DELIMITER |
CREATE TRIGGER document_before_insert BEFORE INSERT ON document
FOR EACH ROW BEGIN
    SET @document_id = NULL;
    SET @global_document_id = NULL;
    if NEW.document_id = 0 THEN
      SET NEW.document_id = 
        (
            SELECT ifnull(MAX(document_id),0)+1 
            FROM document WHERE domain_id = NEW.domain_id
        );
    SET @document_id = NEW.document_id;
    END IF;
    IF NEW.global_document_id = '' THEN
      SET NEW.global_document_id = 
        (
            SELECT CONCAT(
                LPAD(HEX(NEW.domain_id), 6, '0'),
                '-', 
                LPAD(HEX(NEW.document_id), 6, '0')
            )
        );
      SET @global_document_id = NEW.global_document_id;
    END IF;
END;
|
DELIMITER ;
