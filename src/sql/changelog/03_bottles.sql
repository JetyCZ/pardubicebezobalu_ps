CREATE TABLE ps_jety_bottled_products
(
    id_product int NOT NULL,
    id_product_bottle int NOT NULL
)
;
ALTER TABLE ps_jety_bottled_products
    ADD CONSTRAINT fk_ps_jety_bottled_products_product
        FOREIGN KEY (id_product)
            REFERENCES ps_product(id_product) ON DELETE CASCADE
;
ALTER TABLE ps_jety_bottled_products
    ADD CONSTRAINT fk_ps_jety_bottled_products_bottle
        FOREIGN KEY (id_product_bottle)
            REFERENCES ps_product(id_product) ON DELETE CASCADE
;
CREATE INDEX fk_ps_jety_bottled_products_bottle ON ps_jety_bottled_products(id_product_bottle)
;
CREATE INDEX fk_ps_jety_bottled_products_product ON ps_jety_bottled_products(id_product)
;
