<?php

$MAX_MONSTER_LEVEL = 20;

// Some items use an on-equip 
// $spell_damage_spells_map = array("")



mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = new mysqli("localhost", "trinity", "trinity", "world");

// Might be faster with two connections? One for deleting, one for prepared statements
$mysqli_for_deletes = new mysqli("localhost", "trinity", "trinity", "world");

// Drop items with monster lvl above 1 - it means this has been run before, so we just want to remake the items and not have duplicates
$date = gmdate('Y-m-d h:i:s \G\M\T');

$mysqli->query("CREATE TABLE IF NOT EXISTS world.item_template_backup_$date  LIKE world.item_template; INSERT INTO world.item_template_backup_$date SELECT * FROM world.item_template;");
// $mysqli->query("DELETE FROM world.item_template WHERE monster_level > 1 AND inherit_from_item IS NOT NULL;");

// Fetch items
$items_stmt = $mysqli->query("SELECT * FROM world.item_template WHERE class in (2,4) AND monster_level = 1 AND inherit_from_item IS NULL AND InventoryType <> 0 AND Quality > 1 AND entry NOT IN (38484, 40481) ");


$next_item_id_stmt = $mysqli->query("SELECT MAX(ENTRY) from world.item_template");
$next_item_id = intval($next_item_id_stmt->fetch_row()[0]);


// For every item, we are gonna make different monster level versions of it
$prepare_string = "INSERT INTO world.item_template ({column_names}) VALUES ({column_values})";
$bind_string = "";


if ($items_stmt) {
    // First monster level items we make are 2, normal items are 1
    $counter = 0;
    while ($row = $items_stmt -> fetch_assoc()) {
        $existing_items = $mysqli->query("SELECT entry, inherit_from_item, monster_level FROM world.item_template WHERE monster_level > 1 AND inherit_from_item = " . $row['entry']);
        $existing_items->fetch_all(MYSQLI_ASSOC);
        if ($counter == 0) {
            $column_value_question_marks = "";
            $column_name_question_marks = "";

            $i = 0;
            foreach($row as $key => $val) {
                if ($i == 0) {
                    $column_value_question_marks = $column_value_question_marks . "?";
                    $column_name_question_marks = $column_name_question_marks . $key;
                } else {
                    $column_value_question_marks = $column_value_question_marks .  ", ?";
                    $column_name_question_marks = $column_name_question_marks . ", $key";

                }
                if (is_numeric($val)) {
                    $bind_string = $bind_string . "i";
                } else {
                    $bind_string = $bind_string . "s";
                }
                $i++;
            }

            $prepare_string = preg_replace("/{column_names}/", $column_name_question_marks, $prepare_string);
            $prepare_string = preg_replace("/{column_values}/", $column_value_question_marks, $prepare_string);
            $prepared_statment = $mysqli->prepare($prepare_string);
        }
        // First item we want to make is monster_level 2, so we start at 2;
        for ($i = 2; $i <= $MAX_MONSTER_LEVEL; $i++) {
            $existing_entry = 0;
            foreach($existing_items as $existing_item) {
                if ($existing_item['monster_level'] == $i) {
                    $existing_entry = $existing_item['entry'];
                    break;
                }
            }
            $next_item_id++;
            
            if ($existing_entry > 0) {
                $mysqli_for_deletes->query("DELETE FROM world.item_template WHERE entry = $existing_entry");
            }
            makeItem($existing_entry > 0 ? $existing_entry : $next_item_id, $row, $i);
        }
        $existing_items -> free_result();
        $counter++;
    }
    $items_stmt -> free_result();
    $mysqli->close();
    $mysqli_for_deletes->close();
    echo "Done";
}



function makeItem($entry, $row, $monster_level) {
    global $mysqli;
    global $prepared_statment;
    global $bind_string;


    $new_item = array(
        "entry" => (int) $entry, //entry
        "name" => $row["name"] . " [MLVL $monster_level]",
        "BuyPrice" => ((int) $row["BuyPrice"]) * getGoldMultiplier($monster_level), 
        "SellPrice" => ((int) $row["SellPrice"]) * getGoldMultiplier($monster_level), 
        "stat_value1" => ((int) $row["stat_value1"]) * getStatMultiplier($monster_level, $row["stat_type1"]),
        "stat_value2" => ((int) $row["stat_value2"]) * getStatMultiplier($monster_level, $row["stat_type2"]),
        "stat_value3" => ((int) $row["stat_value3"]) * getStatMultiplier($monster_level, $row["stat_type3"]), 
        "stat_value4" => ((int) $row["stat_value4"]) * getStatMultiplier($monster_level, $row["stat_type4"]),
        "stat_value5" => ((int) $row["stat_value5"]) * getStatMultiplier($monster_level, $row["stat_type5"]),
        "stat_value6" => ((int) $row["stat_value6"]) * getStatMultiplier($monster_level, $row["stat_type6"]),
        "stat_value7" => ((int) $row["stat_value7"]) * getStatMultiplier($monster_level, $row["stat_type7"]),
        "stat_value8" => ((int) $row["stat_value8"]) * getStatMultiplier($monster_level, $row["stat_type8"]),
        "stat_value9" => ((int) $row["stat_value9"]) * getStatMultiplier($monster_level, $row["stat_type9"]),
        "stat_value10" => ((int) $row["stat_value10"]) * getStatMultiplier($monster_level, $row["stat_type10"]),
        "dmg_min1" => ((int) $row["dmg_min1"]) * getWepDamageMultiplier($monster_level),
        "dmg_max1" => ((int) $row["dmg_max1"]) * getWepDamageMultiplier($monster_level),
        "dmg_min2" => ((int) $row["dmg_min2"]) * getWepDamageMultiplier($monster_level),
        "dmg_max2" => ((int) $row["dmg_max2"]) * getWepDamageMultiplier($monster_level),
        "armor" => ((int) $row["armor"]) * getArmorBlockMultiplier($monster_level),
        "block" => ((int) $row["block"]) * getArmorBlockMultiplier($monster_level),
        "inherit_from_item" => (int) $row["entry"],
        "monster_level" => (int) $monster_level
    );

    if($new_item["stat_value1"] > 32000) {
        echo $new_item["inherit_from_item"];
    }

    $values = [];

    foreach($row as $key => $val) {
        if (array_key_exists($key, $new_item)) {
            array_push($values, $new_item[$key]);
        } else {
            array_push($values, $val);
        }
    }

    $prepared_statment->bind_param($bind_string, ...$values);
    $prepared_statment->execute();
}


function getWepDamageMultiplier($monster_level) {
    return $monster_level * 1;
}

function getStatMultiplier($monster_level, $stat_type_id) {
    if ($stat_type_id == 44) {
        return 1;
    } else if ($stat_type_id == 45) {
        return $monster_level * 1.5;
    }
    return $monster_level * 1;
}

function getGoldMultiplier($monster_level) {
    // To avoid items becoming too valuable I guess
    return (int) ceil(1 + (($monster_level-1) * 0.8));
}

function getArmorBlockMultiplier($monster_level) {
    // return (int) ceil(1 + (($monster_level-1) * 0.5));
    return $monster_level * 1;
}

?>