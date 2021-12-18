-- Flagongut's Fossil
UPDATE `gameobject_template` SET `AIName`="SmartGameObjectAI" WHERE `entry`=9630;
DELETE FROM `smart_scripts` WHERE `entryorguid`=9630 AND `source_type`=1;
INSERT INTO `smart_scripts` (`entryorguid`, `source_type`, `id`, `link`, `event_type`, `event_phase_mask`, `event_chance`, `event_flags`, `event_param1`, `event_param2`, `event_param3`, `event_param4`, `action_type`, `action_param1`, `action_param2`, `action_param3`, `action_param4`, `action_param5`, `action_param6`, `target_type`, `target_param1`, `target_param2`, `target_param3`, `target_x`, `target_y`, `target_z`, `target_o`, `comment`) VALUES
(9630,1,0,0,70,0,100,1,2,0,0,0,1,0,0,0,0,0,0,19,1077,0,0,0,0,0,0,"Flagongut's Fossil - On Gameobject State Changed - Say Line 0 (Prospector Whelgar)");

DELETE FROM `creature_text` WHERE `CreatureID`=1077;
INSERT INTO `creature_text` (`CreatureID`, `GroupID`, `ID`, `Text`, `Type`, `Language`, `Probability`, `Emote`, `Duration`, `Sound`, `BroadcastTextId`, `TextRange`, `comment`) VALUES
(1077,0,0,"I figured Flagongut would send someone for that fossil eventually.",12,7,100,0,0,0,1235,0,"Prospector Whelgar");
