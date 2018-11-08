/******************** create table ********************/

/******************** alter table ********************/
alter table `project` modify `ts_ratio` text DEFAULT NULL COMMENT 'TS/决策口径占比';

/******************** insert ********************/

/******************** update ********************/
update `project` set `company_character` = '境外实体（非中国VIE）' where `company_character` = '非境外VIE';
