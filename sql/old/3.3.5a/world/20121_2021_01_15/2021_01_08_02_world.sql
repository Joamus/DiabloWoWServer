--
UPDATE `creature` SET `unit_flags` = `unit_flags` &~ 0x00000010 WHERE `guid` IN (40451,40453,40454,40455,40456,40457,40458,40459,40460,40461,40466);
