SELECT sum(total_paid_tax_incl), t.name,date(o.date_add)
FROM `ps_jety_timesheet` t
         join ps_orders o on o.date_add > t.date_start and o.date_add<t.date_end
         join ps_order_state_lang osl on osl.id_order_state = o.current_state and osl.id_lang=2
WHERE
    (osl.name = 'Dodáno' )
group by t.name,
         date(o.date_add)
order by t.name, date(o.date_add)


