ALTER table world.item_template
ADD `monster_level` smallint(2) unsigned DEFAULT '1',
ADD `inherit_from_item` mediumint(8) unsigned DEFAULT NULL;