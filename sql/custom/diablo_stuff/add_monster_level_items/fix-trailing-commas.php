<?php

$spell_dbc_file = file_get_contents("Spell.dbc.csv");

$spell_dbc_file = str_replace(",\r\n", "\n", $spell_dbc_file);

file_put_contents("Spell-no-trail.dbc.csv", $spell_dbc_file);