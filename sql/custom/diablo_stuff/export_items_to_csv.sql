SELECT entry, class, subclass, SoundOverrideSubclass, Material, displayId, InventoryType, Sheath FROM 
world.item_template WHERE monster_level > 1
INTO OUTFILE 'C:\\ProgramData\\MySQL\\MySQL Server 5.7\\Uploads\\item.csv'
FIELDS TERMINATED BY ','
LINES TERMINATED BY ',\r\n';
