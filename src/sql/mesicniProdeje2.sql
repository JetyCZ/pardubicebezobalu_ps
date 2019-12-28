select `p`.`id_product`                                                          AS `id_product`,
       sum(`od`.`total_price_tax_incl`)                                          AS `trzba_sum`,
       count(`od`.`id_order`)                                                    AS `order_count`,
       (select `pl`.`name` AS `pname`
        from `ps_product_lang` `pl`
        where ((`p`.`id_product` = `pl`.`id_product`) and (`pl`.`id_lang` = 2))) AS `Name_exp_3`,
       sum(`od`.`product_quantity`)                                              AS `mnozstvi_sum`,
       (sum(`od`.`product_quantity`) /
        timestampdiff(MONTH, '2018-02-28', now()))                               AS `mnozstviMesicKg`,
       `pss`.`quantity`                                                          AS `quantity`
from (((((((`ps_order_detail` `od` join `ps_orders` `o` on ((`o`.`id_order` = `od`.`id_order`))) join `ps_order_state_lang` `osl` on ((`osl`.`id_order_state` = `o`.`current_state`))) left join `ps_customer` `c` on ((`c`.`id_customer` = `o`.`id_customer`))) join `ps_product` `p` on ((`p`.`id_product` = `od`.`product_id`))) join `ps_product_lang` `pl` on ((`p`.`id_product` = `pl`.`id_product`))) left join `ps_product_supplier` `ps` on ((`p`.`id_product` = `ps`.`id_product`)))
         left join `ps_stock_available` `pss`
                   on ((`pss`.`id_product` = `p`.`id_product`)))
where (((`osl`.`name` = 'Dodáno') or (`osl`.`name` like '%Dodavatele%')) and
       (`pl`.`id_lang` = 2) and
       (not ((`c`.`lastname` like '%stnanec%'))) and
       (not ((`c`.`lastname` like '%Soukromé%'))) and
       (not ((`c`.`lastname` like '%-dobrovolník%'))))
group by `p`.`id_product`
order by sum(`od`.`total_price_tax_incl`) desc, `p`.`id_product`