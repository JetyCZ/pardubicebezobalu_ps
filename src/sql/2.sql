CREATE TABLE ps_jety_supplier_cron
(
  id int PRIMARY KEY NOT NULL,
  id_supplier int DEFAULT 0 NOT NULL,
  cronstr VARCHAR(255) NOT NULL
);# MySQL returned an empty result set (i.e. zero rows).


CREATE INDEX id_supplier ON ps_jety_supplier_cron(id_supplier)
;# MySQL returned an empty result set (i.e. zero rows).




INSERT INTO ps_jety_supplier_cron (id,id_supplier,cronstr) VALUES (0,2,'0 12 * * 6');

