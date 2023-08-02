CREATE DATABASE db_enerclic;
use db_enerclic;
create table db_enerclic(
id_enerclic int primary key,
date_time datetime,
power int,
energy varchar(50)
);
select * from db_enerclic;