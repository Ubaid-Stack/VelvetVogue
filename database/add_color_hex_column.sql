-- Add color_hex column to product_variants table
-- Run this SQL in phpMyAdmin or MySQL Workbench
-- This will safely add the column only if it doesn't exist

ALTER TABLE `product_variants` 
ADD COLUMN IF NOT EXISTS `color_hex` VARCHAR(7) NULL DEFAULT '#000000' AFTER `color`;

-- Update existing records to have a default color_hex
UPDATE `product_variants` SET `color_hex` = '#000000' WHERE `color_hex` IS NULL OR `color_hex` = '';
