#!/bin/bash

mysql -uroot -paimei753951 aimeizhuyi -e "update stock set name=replace(replace(replace(name, '%', '％'),'&','＆'),'=','＝'),note=replace(replace(replace(note, '%', '％'),'&','＆'),'=','＝') where name like '%\%%' or note like '%\%%' or note like '%&%' or name like '%&%' or note like '%=%' or name like '%=%';"
