select cl.name,
       s.name,
       (select pl.name as pname from ps_product_lang pl where p.id_product = pl.id_product and pl.id_lang = 2),
       p.id_product,
       IF(p.id_tax_rules_group = '2', 15, 21) as DPH,
       ROUND(p.price*(IF(p.id_tax_rules_group = '2', 15, 21)/100+1),3) as ProdejsDPH,
       sum(od.total_price_tax_incl) as trzba_sum,
       pss.quantity
from ps_order_detail od
       join ps_orders o on o.id_order = od.id_order
       join ps_order_state_lang osl on osl.id_order_state = o.current_state
       left join ps_customer c on c.id_customer = o.id_customer
       join ps_product p on p.id_product = od.product_id
       join ps_product_lang pl on p.id_product = pl.id_product
       left join ps_product_supplier ps on p.id_product = ps.id_product
       left join ps_supplier s on s.id_supplier = ps.id_supplier
       left join ps_stock_available pss on pss.id_product=p.id_product
       left join ps_category_product cp on cp.id_product=p.id_product
       left join ps_category_lang cl on cp.id_category = cl.id_category and cl.id_lang=2
where 1=1
  #and p.id_product=422
  and cl.name<>'Všechno zboží' and cl.name<>'BIO'
  and (osl.name = 'Dodáno' or osl.name like '%Dodavatele%')
  and pl.id_lang = 2
  and lastname not like '%stnanec%'
  and lastname not like '%Soukromé%'
  and lastname not like '%-dobrovolník%'
group by p.id_product, year(o.date_add)
order by cl.name, s.name, pl.name