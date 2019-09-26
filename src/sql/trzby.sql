select
    date(o.date_add) as obdobi,
    sum(o.total_paid_tax_incl) as trzba_sum,
    1
from ps_orders o
     join ps_order_state_lang osl on osl.id_order_state = o.current_state and osl.id_lang=2
where 1 = 1
  and osl.id_lang=2
  and (osl.name = 'Dodáno' or osl.name like '%Splaceno%' )
group by obdobi
order by obdobi desc
