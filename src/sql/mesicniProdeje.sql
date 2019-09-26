select
    sup.name, osl.name,
    # p.id_product,
    EXTRACT(YEAR_MONTH FROM o.date_add) as obdobi
    #date_trunc('day',o.date_add)         as obdobi,
    # o.date_add as obdobi,
    #sum(od.total_price_tax_incl) as trzba_sum,
    # od.total_price_tax_incl as trzba_sum
    #,(select pl.name as pname from ps_product_lang pl where p.id_product = pl.id_product and pl.id_lang = 2)
        ,sum(od.product_quantity)     as mnozstvi_sum
    #,od.product_quantity     as mnozstvi_sum
    #,pss.quantity
from ps_order_detail od
         join ps_orders o on o.id_order = od.id_order
         join ps_order_state_lang osl on osl.id_order_state = o.current_state and osl.id_lang=2
         left join ps_customer c on c.id_customer = o.id_customer
         join ps_product p on p.id_product = od.product_id
         join ps_product_lang pl on p.id_product = pl.id_product
         left join ps_product_supplier ps on p.id_product = ps.id_product
         left join ps_supplier sup on ps.id_supplier = sup.id_supplier
         left join ps_stock_available pss on pss.id_product = p.id_product
where 1 = 1
  # and p.id_product=343
  and pl.name like '%mungo%'
  # and (osl.name = 'Dodáno' or osl.name like '%Dodavatele%' or osl.name like '%Čeká se na platbu šekem%')
  and pl.id_lang = 2
  # and lastname not like '%stnanec%'
  # and lastname not like '%Soukromé%'
  # and lastname not like '%-dobrovolník%'
group by
    EXTRACT(YEAR_MONTH FROM o.date_add)
#p.id_product
#         , obdobi
order by
    #sup.name, osl.name
    #, quantity
    #, trzba_sum DESC
    #, p.id_product
    #,
    #o.date_add
    obdobi
