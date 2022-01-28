<?php

$MAX_MONSTER_LEVEL = 20;

// Some items use an on-equip 
// $spell_damage_spells_map = array("")



mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = new mysqli("localhost", "trinity", "trinity", "world");

// Might be faster with two connections? One for deleting, one for prepared statements
// $mysqli_for_deletes = new mysqli("localhost", "trinity", "trinity", "world");

// Drop items with monster lvl above 1 - it means this has been run before, so we just want to remake the items and not have duplicates
$date = gmdate('Y_m_d');

// echo "CREATE TABLE IF NOT EXISTS world.item_template_backup_$date LIKE world.item_template; INSERT INTO world.item_template_backup_$date SELECT * FROM world.item_template;";
// exit;

$mysqli->query("CREATE TABLE IF NOT EXISTS world.item_template_backup_$date LIKE world.item_template;");
// $mysqli->query("INSERT INTO world.item_template_backup_$date SELECT * FROM world.item_template;");
// $mysqli->query("DELETE FROM world.item_template WHERE monster_level > 1 AND inherit_from_item IS NOT NULL;");

// Fetch items
$items_stmt = $mysqli->query("SELECT * FROM world.item_template WHERE class in (2,4) AND monster_level = 1 AND inherit_from_item IS NULL AND InventoryType <> 0 AND Quality > 1 AND entry NOT IN (38484, 40481) ");


$next_item_id_stmt = $mysqli->query("SELECT MAX(ENTRY) from world.item_template");
$next_item_id = intval($next_item_id_stmt->fetch_row()[0]);


// For every item, we are gonna make different monster level versions of it
$prepare_string = "INSERT INTO world.item_template ({column_names}) VALUES ({column_values})";
$bind_string = "";

$column_value_question_marks = "";


if ($items_stmt) {
    // First monster level items we make are 2, normal items are 1
    $counter = 0;
    while ($row = $items_stmt -> fetch_assoc()) {
        $insert_statements = "";
        $delete_statements = "";
        $existing_items = $mysqli->query("SELECT entry, inherit_from_item, monster_level FROM world.item_template WHERE monster_level > 1 AND inherit_from_item = " . $row['entry']);
        $existing_items->fetch_all(MYSQLI_ASSOC);
        if ($counter == 0) {
            $column_value_question_marks = "";
            $column_name_question_marks = "";

            $i = 0;
            foreach($row as $key => $val) {
                if ($i == 0) {
                    // $column_value_question_marks = "?";
                    $column_name_question_marks = $key;
                } else {
                    // $column_value_question_marks = $column_value_question_marks .  ", ?";
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
            // $prepare_string = preg_replace("/{column_values}/", $column_value_question_marks, $prepare_string);
            // $prepared_statment = $mysqli->prepare($prepare_string);
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
                $delete_statements = $delete_statements . "DELETE FROM world.item_template WHERE entry = $existing_entry;\n";
                // file_put_contents('delete-mlvl-items.sql', "DELETE FROM world.item_template WHERE entry = $existing_entry;", FILE_APPEND);

                // $mysqli_for_deletes->query("DELETE FROM world.item_template WHERE entry = $existing_entry");
            }
            $insert_statements = $insert_statements . makeItem($existing_entry > 0 ? $existing_entry : $next_item_id, $row, $i);
        }
        $existing_items -> free_result();
        file_put_contents('delete-mlvl-items.sql', $delete_statements, FILE_APPEND);
        file_put_contents('create-mlvl-items.sql', $insert_statements, FILE_APPEND);

        $counter++;
    }
    $items_stmt -> free_result();
    $mysqli->close();
    // $mysqli_for_deletes->close();
    echo "Done";
}



function makeItem($entry, $row, $monster_level) {
    global $mysqli;
    global $prepared_statment;
    global $bind_string;
    global $prepare_string;
    global $column_value_question_marks;

    $new_item = array(
        "entry" => (int) $entry, //entry
        "name" => $row["name"] . " [MLVL $monster_level]",
        "BuyPrice" => ((int) $row["BuyPrice"]) * getGoldMultiplier($monster_level) * getQualityMultiplier($row["Quality"]), 
        "SellPrice" => ((int) $row["SellPrice"]) * getGoldMultiplier($monster_level) * getQualityMultiplier($row["Quality"]), 
        "stat_value1" => ((int) $row["stat_value1"]) * getStatMultiplier($monster_level, $row["stat_type1"]) * getQualityMultiplier($row["Quality"]),
        "stat_value2" => ((int) $row["stat_value2"]) * getStatMultiplier($monster_level, $row["stat_type2"]) * getQualityMultiplier($row["Quality"]),
        "stat_value3" => ((int) $row["stat_value3"]) * getStatMultiplier($monster_level, $row["stat_type3"]) * getQualityMultiplier($row["Quality"]), 
        "stat_value4" => ((int) $row["stat_value4"]) * getStatMultiplier($monster_level, $row["stat_type4"]) * getQualityMultiplier($row["Quality"]),
        "stat_value5" => ((int) $row["stat_value5"]) * getStatMultiplier($monster_level, $row["stat_type5"]) * getQualityMultiplier($row["Quality"]),
        "stat_value6" => ((int) $row["stat_value6"]) * getStatMultiplier($monster_level, $row["stat_type6"]) * getQualityMultiplier($row["Quality"]),
        "stat_value7" => ((int) $row["stat_value7"]) * getStatMultiplier($monster_level, $row["stat_type7"]) * getQualityMultiplier($row["Quality"]),
        "stat_value8" => ((int) $row["stat_value8"]) * getStatMultiplier($monster_level, $row["stat_type8"]) * getQualityMultiplier($row["Quality"]),
        "stat_value9" => ((int) $row["stat_value9"]) * getStatMultiplier($monster_level, $row["stat_type9"]) * getQualityMultiplier($row["Quality"]),
        "stat_value10" => ((int) $row["stat_value10"]) * getStatMultiplier($monster_level, $row["stat_type10"]) * getQualityMultiplier($row["Quality"]),
        "dmg_min1" => ((int) $row["dmg_min1"]) * getWepDamageMultiplier($monster_level) * getQualityMultiplier($row["Quality"]),
        "dmg_max1" => ((int) $row["dmg_max1"]) * getWepDamageMultiplier($monster_level) * getQualityMultiplier($row["Quality"]),
        "dmg_min2" => ((int) $row["dmg_min2"]) * getWepDamageMultiplier($monster_level) * getQualityMultiplier($row["Quality"]),
        "dmg_max2" => ((int) $row["dmg_max2"]) * getWepDamageMultiplier($monster_level) * getQualityMultiplier($row["Quality"]),
        "armor" => ((int) $row["armor"]) * getArmorBlockMultiplier($monster_level) * getQualityMultiplier($row["Quality"]),
        "block" => ((int) $row["block"]) * getArmorBlockMultiplier($monster_level) * getQualityMultiplier($row["Quality"]),
        "inherit_from_item" => (int) $row["entry"],
        "monster_level" => (int) $monster_level
    );

    $values = [];

    foreach($row as $key => $val) {
        if (array_key_exists($key, $new_item)) {
            array_push($values, $new_item[$key]);
        } else {
            array_push($values, $val);
        }
    }

    
    $values_string = implode(',', $values);
    
    $insert = preg_replace("/{column_values}/", $values_string, $prepare_string);

    return $insert . ";\n";
    
    
    // file_put_contents('create-mlvl-items.sql', $insert . ";", FILE_APPEND);
    // $prepared_statment->bind_param($bind_string, ...$values);
    // $prepared_statment->execute();
}


function getWepDamageMultiplier($monster_level) {
    return $monster_level * 1.3;
}

function getStatMultiplier($monster_level, $stat_type_id) {
    if ($stat_type_id == 45) {
        return $monster_level * 2;
    }
    return $monster_level * 1.3;
}

function getGoldMultiplier($monster_level) {
    // To avoid items becoming too valuable I guess
    return (int) ceil(1 + (($monster_level-1) * 0.8));
}

function getArmorBlockMultiplier($monster_level) {
    // return (int) ceil(1 + (($monster_level-1) * 0.5));
    return $monster_level * 1.3;
}

function getQualityMultiplier($quality) {
    if ($quality < 4) {
        return 1;
    } else if ($quality == 4 || $quality == 5) {
        return 1.5;
    } else {
        return 1;
    }
}

?>