<?php

function displaySquares($map, $square, $testSquarePoints, $color): void {
    system('clear');

    if ($square["size"]) {
        for ($row = $square["row"]; $row < $square["row"] + $square["size"]; $row++) {
            for ($col = $square["col"]; $col < $square["col"] + $square["size"]; $col++) {
                $map[$row][$col] = "x";
            }
        }
    }

    foreach ($map as $row => $value) {
        $map[$row] = str_split($value);
    }

    foreach ($testSquarePoints as $point) {
        $map[$point["row"]][$point["col"]] = $color . $map[$point["row"]][$point["col"]] . "\e[0m";
    }

    foreach ($map as $row) {
        echo implode($row) . "\n";
    }
}