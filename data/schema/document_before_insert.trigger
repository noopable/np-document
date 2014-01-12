DELIMITER |
CREATE TRIGGER document_before_insert BEFORE INSERT ON document
FOR EACH ROW BEGIN
if NEW.document_id = 0 THEN
  SET NEW.document_id = (SELECT ifnull(MAX(document_id),0)+1 FROM document WHERE domain_id = NEW.domain_id);
END IF;
IF NEW.global_document_id = '' THEN
  SET NEW.global_document_id = (select concat(LPAD(HEX(NEW.domain_id), 6, '0'), '-', LPAD(HEX(NEW.document_id), 6, '0')));
END IF;
END;
|
DELIMITER ;
