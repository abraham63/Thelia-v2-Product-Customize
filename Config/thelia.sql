
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- order_comment
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `PersoProd`;

CREATE TABLE `PersoProd`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `item_id` INTEGER NOT NULL,
    `comment` TEXT,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `PersoProdCmd`;

CREATE TABLE `PersoProdCmd`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `order_prod_id` INTEGER NOT NULL,
    `comment` TEXT,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;


# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
