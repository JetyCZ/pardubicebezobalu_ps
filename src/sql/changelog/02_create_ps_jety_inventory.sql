drop table ps_jety_inventory;

CREATE TABLE ps_jety_inventory
(
    id MEDIUMINT NOT NULL AUTO_INCREMENT primary key,
    id_inventory int NOT NULL,
    id_product int(10) unsigned NOT NULL,
    date_updated timestamp NOT NULL,
    dmt date,
    quantity int NOT NULL
)
;


ALTER TABLE `ps_jety_inventory`
    ADD CONSTRAINT `fk_ps_jety_inventory_product` FOREIGN KEY (`id_product`) REFERENCES `ps_product` (`id_product`) on delete cascade;

