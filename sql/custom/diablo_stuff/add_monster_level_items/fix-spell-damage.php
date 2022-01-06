<?php

// Read spells.dbc.csv
$spell_dbc = array();

if (($handle = fopen("Spell-no-trail.dbc.csv", "r")) !== FALSE) {
    $row = 0;
    while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
        // var_dump($data);
        // exit;
        if ($row > 0) {
            $spell_dbc["" . $data[0]] = $data[136]; // 0 is ID, 136 is str
        } 
        $row++;
        
    }
    fclose($handle);
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = new mysqli("localhost", "trinity", "trinity", "world");

$items_stmt = $mysqli->query("SELECT * FROM world.item_template WHERE monster_level > 1 AND (spellid_1 <> 0 OR spellid_2 <> 0 OR spellid_3 <> 0 OR spellid_4 <> 0 OR spellid_5 <> 0)");
$queries = [];

// Filter all rows that contain proprety "str" with either "increase healing" or "increase spell dam" (lower case)
// Regex the strs number, so you know how much spell damage it gives
$stat_list = array(
    "SPELL_POWER" => array("id" => 45, "spell_name" => "increase spell dam", "factor" => 1,
        "fixed_spell_values" => array(
            33638 => 157,
            32706 => 0,
            12019 => 0,
            25113 => 36,
            21011 => 0,
            54288 => 0,
            21010 => 0,
            28876 => 0,
            22849 => 0,
            24197 => 0,
            28890 => 0
        )
    ),
    "ARMOR_PENETRATION" => array("id" => 44, "spell_name" => "armor penetration", "factor" => 0.14,
        "fixed_spell_values" => array(
            34106 => 85,
            37173 => 0,
            40934 => 40,
            42109 => 45,
            53793 => 43,
        )
    ),
    "HEAL_POWER" => array("id" => 45, "spell_name" => "increase healing", "factor" => 0.55,
        "fixed_spell_values" => array(
            32701 => 0
        )
    ),
    "MANA_PER_FIVE" => array("id" => 43, "spell_name" => "increased mana regen", "factor" => 1.3,
        "fixed_spell_values" => array(
            28014 => 14,
            32705 => 0,
            33780 => 20,
            35828 => 26,
            35829 => 35,
            35836 => 20,
            36411 => 38,
            39546 => 8,
            39547 => 8,
            39879 => 9,
            39882 => 6,
            40231 => 23,
            40343 => 23,
            41562 => 10,
            41591 => 10,
            41659 => 5,
            41675 => 15,
            41770 => 5,
            41775 => 4,
            41776 => 5,
            42060 => 29,
            42082 => 20,
            42100 => 25,
            42115 => 24,
            45216 => 100,
            47905 => 19,
            48228 => 31,
            49176 => 33,
            49442 => 39,
            52804 => 45,
            53354 => 28,
            54082 => 39,
            25116 => 12,
            21636 => 15,
            21628 => 9,
            39905 => 20,
            40183 => 26,
            48014 => 25,
            48226 => 26,
            18378 => 10,
            18379 => 8,
            20959 => 13,
            21359 => 1,
            21360 => 3,
            21361 => 4,
            21362 => 5,
            21363 => 6,
            21364 => 9,
            21365 => 11,
            21366 => 14,
            21618 => 5,
            21619 => 5,
            21620 => 6,
            21621 => 1,
            21622 => 1,
            21623 => 3,
            21624 => 3,
            21625 => 4,
            21626 => 8,
            21627 => 8,
            21628 => 9,
            21629 => 10,
            21630 => 10,
            21631 => 11,
            21632 => 13,
            21633 => 13,
            21634 => 14,
            21635 => 15,
            21637 => 15,
            21638 => 16,
            21639 => 16,
            21640 => 18,
            21641 => 18,
            21642 => 18,
            21643 => 19,
            21644 => 19,
            23212 => 20,
            23795 => 0,
            25114 => 0,
            25155 => 0,
            26647 => 0,
            27912 => 0
        )
    )
);

$query_as_string = "BEGIN;\r\n";
file_put_contents('test-query.sql', $query_as_string, FILE_APPEND);

while ($item = $items_stmt -> fetch_assoc()) {
    $monster_level = $item["monster_level"];
    $counter = 0;
    for ($spell_id_iterator = 1; $spell_id_iterator <= 5; $spell_id_iterator++) {
        $counter++;
        $spell_id = $item["spellid_" . $spell_id_iterator];
        if ($spell_id == 0) {
            continue;
        }
        if ($spell_id == -1) {
            // For some reason spell ids can be -1, seems to be a legacy thing.
            continue;
        }
        try {
            $spell_name_from_dbc = $spell_dbc[$spell_id];
        } catch (Error $error) {
            echo "Spell ID $spell_id could not be found!";
            continue;
        }
        $stat_value = 0;
        $stat_id;

        // Find the stat type that the spell benefits
        foreach($stat_list as $stat_element) {
            $stat_value_exists = array_key_exists($spell_id, $stat_element["fixed_spell_values"]);
            if ($stat_value_exists == true) {
                $stat_id = $stat_element["id"];
                $stat_value = $stat_element["fixed_spell_values"][$spell_id];
                $stat_value = $stat_value * ($monster_level - 1);
            } else if (strpos(strtolower($spell_name_from_dbc), $stat_element["spell_name"]) !== false) {
                $stat_value = preg_replace("/[^0-9]/", "", $spell_name_from_dbc);
                if (!is_numeric($stat_value)) {
                    var_dump($spell_name_from_dbc);
                    echo "$stat_value is not a number (spellID $spell_id)";
                    exit;
                } else {
                    $stat_value = intval($stat_value);
                }
                $stat_id = $stat_element["id"];
                $stat_value = ($stat_value * $stat_element["factor"]) * ($monster_level - 1);
            }
        }
        // The stats in the predefined list means with value 0 that they should be ignored.
        if ($stat_value > 0) {
            // Make if to check that the item does not already have the stat in one of its stat values - makes it safe to run this script twice.
            // It will mean that if an item already has +spell power as a stat, but also as a spell, we will skip it.
            $existing_stat_types = [ 
                $item["stat_type1"], $item["stat_type2"], $item["stat_type3"], 
                $item["stat_type4"], $item["stat_type5"], $item["stat_type6"], 
                $item["stat_type7"], $item["stat_type8"], $item["stat_type9"], 
                $item["stat_type10"], 
            ];
            // If item already has the stat as a stat (and not a spell), then just skip. Technically item could have it both as spell and stat, but this is good enough.
            // It will also make this script safe to run multiple times.
            if (in_array($stat_id, $existing_stat_types)) {
                continue;
            }
            
            $next_available_stat = get_next_stat_column_key($item);
            if ($next_available_stat == null) {
                echo "Something went wrong; the next available stat column was null for item " . $item["entry"];
                exit;
            }
            $item["stat_type" . $next_available_stat] = $stat_id;
            $item["stat_value" . $next_available_stat] = $stat_value;

            $next_available_stat_type_key = "stat_type" . $next_available_stat;
            $next_available_stat_value_key = "stat_value" . $next_available_stat;
            


            $query = "UPDATE world.item_template SET $next_available_stat_type_key = $stat_id, $next_available_stat_value_key = ceil($stat_value), StatsCount = StatsCount + 1 WHERE entry = ". $item["entry"] . ";\n";
            // echo $query;
            file_put_contents('test-query.sql', $query, FILE_APPEND);
        }
    }

}


file_put_contents('test-query.sql', "COMMIT;", FILE_APPEND);
echo "Done!";
// Comment for git fix


function get_value_from_name($type, $str) {
    global $stat_list;
    $start_string = $stat_list[$type]["spell_name"];
    $str = strtolower($str);

    
    // Now loop over every character after the beginning string, so e.g after "increase healing"
}

function get_next_stat_column_key($item) {
    // 1-indexing because the stat columns are called 1-5, so it's easier to use i as value without plussing.
    for ($i = 1; $i <= 10; $i++) {
        if ($item["stat_type" . $i] == 0) {
            return $i;
        }
    }
    return null;
}
