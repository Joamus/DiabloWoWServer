ALTER table world.item_template
ADD `monster_level` smallint(2) unsigned DEFAULT '1',
ADD `inherit_from_item` mediumint(8) unsigned DEFAULT NULL,
MODIFY armor INT(10),
MODIFY stat_value1 INT(10),
MODIFY stat_value2 INT(10),
MODIFY stat_value3 INT(10),
MODIFY stat_value4 INT(10),
MODIFY stat_value5 INT(10),
MODIFY stat_value6 INT(10),
MODIFY stat_value7 INT(10),
MODIFY stat_value8 INT(10),
MODIFY stat_value9 INT(10),
MODIFY stat_value10 INT(10);