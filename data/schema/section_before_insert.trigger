DELIMITER |
CREATE TRIGGER section_before_insert BEFORE INSERT ON section
FOR EACH ROW BEGIN
    SET NEW.section_rev = 
        (
        SELECT ifnull(MAX(section_rev),0)+1 FROM section 
        WHERE domain_id = NEW.domain_id AND document_id = NEW.document_id 
        AND section_name = NEW.section_name
        );
    IF NEW.global_document_id is null THEN
      SET NEW.global_document_id = 
        (
        SELECT global_document_id FROM document 
        WHERE domain_id = NEW.domain_id and document_id = NEW.document_id
        );
    END IF;
    IF NEW.global_section_id = '' THEN
        SET NEW.global_section_id = 
        (
        SELECT CONCAT(NEW.global_document_id,
            '-', 
            ifnull(NEW.section_name,'notset'),
            '.',
            NEW.section_rev)
        ); 
    END IF;
END;
|
DELIMITER ;
