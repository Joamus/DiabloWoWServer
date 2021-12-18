--
SET @cguid:= 116581;
DELETE FROM `creature` WHERE `guid` BETWEEN @CGUID+0 AND @CGUID+18;
INSERT INTO `creature` (`guid`, `id`, `map`, `zoneId`, `areaId`, `spawnMask`, `phaseMask`, `modelid`, `equipment_id`, `position_x`, `position_y`, `position_z`, `orientation`, `spawntimesecs`, `spawndist`, `currentwaypoint`, `curhealth`, `curmana`, `MovementType`, `npcflag`, `unit_flags`, `dynamicflags`, `VerifiedBuild`) VALUES
(@CGUID+0, 25611, 571, 0, 0, 1, 1, 0, 0,2964.623, 6524.048, 73.47649, 3.186528, 300, 0, 0, 1, 0, 0, 0, 0, 0, -1),
(@CGUID+1, 25611, 571, 0, 0, 1, 1, 0, 0, 2971.917, 6561.28, 65.08951, 3.186528, 300, 0, 0, 1, 0, 0, 0, 0, 0, -1),
(@CGUID+2, 25611, 571, 0, 0, 1, 1, 0, 0, 2971.521, 6446.971, 82.25601, 3.186528, 300, 0, 0, 1, 0, 0, 0, 0, 0, -1),
(@CGUID+3, 25610, 571, 0, 0, 1, 1, 0, 0, 3070.57, 6302.462, 94.70068, 2.042035, 300, 0, 0, 1, 0, 0, 0, 0, 0, -1),
(@CGUID+4, 25610, 571, 0, 0, 1, 1, 0, 0, 3077.694, 6305.497, 94.37986, 1.675516, 300, 0, 0, 1, 0, 0, 0, 0, 0, -1),
(@CGUID+5, 25610, 571, 0, 0, 1, 1, 0, 0, 3074.518, 6302.187, 94.54217, 0.08726646, 300, 0, 0, 1, 0, 0, 0, 0, 0, -1),
(@CGUID+6, 25610, 571, 0, 0, 1, 1, 0, 0, 3074.507, 6306.154, 94.45872, 1.518436, 300, 0, 0, 1, 0, 0, 0, 0, 0, -1),
(@CGUID+7, 25610, 571, 0, 0, 1, 1, 0, 0, 3071.743, 6298.868, 94.89539, 3.682645, 300, 0, 0, 1, 0, 0, 0, 0, 0, -1),
(@CGUID+8, 25600, 571, 0, 0, 1, 1, 0, 0, 3013.527, 6276.389, 104.556, 3.682645, 300, 10, 0, 1, 0, 1, 0, 0, 0, -1),
(@CGUID+9, 25600, 571, 0, 0, 1, 1, 0, 0, 2820.047, 6629.763, 44.670, 3.682645, 300, 10, 0, 1, 0, 1, 0, 0, 0, -1),
(@CGUID+10, 25600, 571, 0, 0, 1, 1, 0, 0, 2878.242, 6583.210, 56.780, 3.682645, 300, 10, 0, 1, 0, 1, 0, 0, 0, -1),
(@CGUID+11, 25600, 571, 0, 0, 1, 1, 0, 0, 3150.755, 6488.983, 82.269, 3.682645, 300, 10, 0, 1, 0, 1, 0, 0, 0, -1),
(@CGUID+12, 25609, 571, 0, 0, 1, 1, 0, 0, 3122.198, 6442.486, 84.7479, 3.630285, 300, 0, 0, 1, 0, 0, 0, 0, 0, -1),
(@CGUID+13, 25610, 571, 0, 0, 1, 1, 0, 0, 3119.349, 6440.627, 84.82687, 0.4712389, 300, 0, 0, 1, 0, 0, 0, 768, 0, -1),
(@CGUID+14, 25655, 571, 0, 0, 1, 1, 0, 0, 3070.300, 6311.441, 94.478, 5.172306, 300, 0, 0, 1, 0, 0, 0, 0, 0, -1),
(@CGUID+16, 25600, 571, 0, 0, 1, 1, 0, 0, 2950.717, 6351.385, 102.713, 3.682645, 300, 10, 0, 1, 0, 1, 0, 0, 0, -1),
(@CGUID+17, 25600, 571, 0, 0, 1, 1, 0, 0, 2847.656, 6481.361, 79.132, 3.682645, 300, 5, 0, 1, 0, 1, 0, 0, 0, -1),
(@CGUID+18, 25439, 571, 0, 0, 1, 1, 0, 0, 2892.715, 6385.247, 93.70346, 1.518436, 300, 0, 0, 1, 0, 0, 0, 0, 0, -1);

SET @oguid= 62;
DELETE FROM `gameobject` WHERE `guid` = @OGUID;
INSERT INTO `gameobject` (`guid`, `id`, `map`, `zoneId`, `areaId`, `spawnMask`, `phaseMask`, `position_x`, `position_y`, `position_z`, `orientation`, `rotation0`, `rotation1`, `rotation2`, `rotation3`, `spawntimesecs`, `animprogress`, `state`, `VerifiedBuild`) VALUES
(@OGUID, 187861, 571, 0, 0, 1, 1, 3119.332, 6440.662, 84.74261, 0.4712385, 0, 0, 0.2334452, 0.97237, 120, 255, 1, -1);

DELETE FROM `creature_addon` WHERE `guid` BETWEEN @CGUID+0 AND @CGUID+18;
INSERT INTO `creature_addon` (`guid`, `path_id`, `mount`, `bytes1`, `bytes2`, `emote`, `auras`) VALUES
(@CGUID+0, 0, 0, 0, 1, 0, ''),
(@CGUID+1, 0, 0, 0, 1, 0, ''),
(@CGUID+2, 0, 0, 0, 1, 0, ''),
(@CGUID+3, 0, 0, 0, 1, 0, '45801'),
(@CGUID+4, 0, 0, 0, 1, 0, '45801'),
(@CGUID+5, 0, 0, 0, 1, 0, '45801'),
(@CGUID+6, 0, 0, 0, 1, 0, '45801'),
(@CGUID+7, 0, 0, 0, 1, 0, '45801'),
(@CGUID+8, 0, 0, 0, 1, 0, ''), 
(@CGUID+9,  0, 0, 0, 1, 0, ''), 
(@CGUID+10, 0, 0, 0, 1, 0, ''), 
(@CGUID+11, 0, 0, 0, 1, 0, ''),
(@CGUID+12, 0, 0, 0, 1, 69, ''),
(@CGUID+13, 0, 0, 0, 1, 0, ''), 
(@CGUID+14, 0, 0, 0, 1, 0, ''),
(@CGUID+16, 0, 0, 0, 1, 0, ''),
(@CGUID+17, 0, 0, 0, 1, 0, ''),
(@CGUID+18, 0, 0, 0, 257, 0, '');
