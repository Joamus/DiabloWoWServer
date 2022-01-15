<?php

$monster_level = 1;
$stat_value = 2;
$stat_element = array("factor" => 1)


$stat_value = ($stat_value * $stat_element["factor"]) * ($monster_level - 1) * get_quality_multiplier($stat_element["Quality"]);;
}
if ($stat_id == 45) {
    $stat_value = $stat_value * 1.3;
} else {
    $stat_value = $stat_value * 1.1;
}


?>