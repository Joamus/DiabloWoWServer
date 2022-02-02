ALTER TABLE characters.characters
ADD paragon_strength int(10) unsigned NOT NULL DEFAULT '0',
ADD paragon_agility int(10) unsigned NOT NULL DEFAULT '0',
ADD paragon_stamina int(10) unsigned NOT NULL DEFAULT '0',
ADD paragon_intellect int(10) unsigned NOT NULL DEFAULT '0',
ADD paragon_spirit int(10) unsigned NOT NULL DEFAULT '0',
ADD paragon_lifesteal smallint(3) unsigned NOT NULL DEFAULT '0',
ADD paragon_level int(10) unsigned NOT NULL DEFAULT '1',
ADD paragon_xp int(10) unsigned NOT NULL DEFAULT '0',
ADD paragon_available_points int(10) unsigned NOT NULL DEFAULT '0',
ADD monster_level smallint(3) unsigned NOT NULL DEFAULT '1',
ADD paragon_spell_power int(10) unsigned NOT NULL DEFAULT '0';
ADD paragon_offense smallint(3) unsigned NOT NULL DEFAULT '0';
ADD paragon_defense smallint(3) unsigned NOT NULL DEFAULT '0';
ADD paragon_heal smallint(3) unsigned NOT NULL DEFAULT '0';


