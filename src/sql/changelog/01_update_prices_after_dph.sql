UPDATE `d175205_presta2`.`ps_product` SET price=price*1.21 where id_tax_rules_group=1;

UPDATE `d175205_presta2`.`ps_product` SET price=price*1.15 where id_tax_rules_group=2;

update ps_product_shop ps set price=(select price from ps_product p where p.id_product=ps.id_product);
-- Opravit platby s DPH do 29 března
