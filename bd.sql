#CREATE DATABASE db_enerclic;
use db_enerclic;
drop table d_meter;
create table d_meter(
id int primary key auto_increment,
date_time datetime not null,
power1 float,
power2 float,
energy float
);
#SELECT power1, power2, GREATEST(power1, power2) AS max_power FROM d_meter;
#INSERT INTO d_meter (date_time, power1, power2, energy) values (current_timestamp(), 10, 10, null);
select * from d_meter;
#SELECT * FROM d_meter WHERE DATE(date_time) = '2023-07-31';
#truncate d_meter;