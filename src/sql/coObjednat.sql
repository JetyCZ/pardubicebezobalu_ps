select s.name, pl.name, od.product_quantity as mnozstvi_objednavane, p.quantity as mnozstvi_nasklade, o.date_add, c.firstname, c.lastname, osl.name from ps_order_detail od
  join ps_orders o on o.id_order = od.id_order
  join ps_order_state_lang osl on osl.id_order_state = o.current_state
  join ps_customer c on c.id_customer = o.id_customer
  join ps_product p on p.id_product = od.product_id
  join ps_product_lang pl on p.id_product = pl.id_product
  join ps_product_supplier ps on p.id_product = ps.id_product
  join ps_supplier s on ps.id_supplier = s.id_supplier
where
  osl.id_lang = 2
  and (osl.name='Probíhá příprava' or osl.name like '%Dodavatele%')
  and pl.id_lang = 2
order by p.id_supplier, p.id_product