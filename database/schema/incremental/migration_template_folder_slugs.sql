-- Align templates.slug with filesystem folders (template1, template2, …).
-- Run after renaming template directories on disk.
UPDATE templates SET slug = CONCAT('template', id) WHERE id > 0;
