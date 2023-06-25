<?php

require "displaySquares.php";

function main(string $filepath): void
{
    $content = file_get_contents($filepath);
    $map = explode("\n", $content);
    $rowCount = array_shift($map);
    $rowLength = strlen($map[0]);
    array_pop($map);
    $square = [
        "row" => null,
        "col" => null,
        "size" => 0
    ];

    $row = 0;
    $remainingRows = $rowCount - $row;
    $red = "\e[1;31m";
    $green = "\e[1;32m";
    while ($row < $rowCount && $remainingRows > $square["size"]) {
        $col = 0;
        $streak = 0;
        $streakCoords = [];
        while ($col < $rowLength && ($rowLength - $col + $streak) > $square["size"]) {
            if ($map[$row][$col] === ".") {
                $streak++;
                $streakCoords[] = ["row" => $row, "col" => $col];
                displaySquares($map, $square, $streakCoords, $green);
            } else {
                $streak = 0;
                displaySquares($map, $square, $streakCoords, $red);
                $streakCoords = [];
            }

            if ($streak > $square["size"] && $remainingRows >= $streak) {
                $obstacle = false;
                $xOffset = $col;
                $xMin = $xOffset - $streak;
                while ($xOffset > $xMin) {
                    $yOffset = $row + 1;
                    $yMax = $row + $streak;
                    while ($yOffset < $yMax) {
                        usleep(50000);
                        if ($map[$yOffset][$xOffset] === "o") {
                            $obstacle = true;
                            $col = $xOffset;
                            $streak = 0;
                            displaySquares($map, $square, $streakCoords, $red);
                            $streakCoords = [];
                            usleep(100000);
                            break(2);
                        } else {
                            $streakCoords[] = ["row" => $yOffset, "col" => $xOffset];
                            displaySquares($map, $square, $streakCoords, $green);
                        }
                        $yOffset++;
                    }
                    $xOffset--;
                }

                if (!$obstacle) {
                    $square = ["row" => $row, "col" => $col - $streak + 1, "size" => $streak];
                }
            }
            $col++;
        }
        $row++;
        $remainingRows = $rowCount - $row;
    }


    if ($square["size"]) {
        for ($row = $square["row"]; $row < $square["row"] + $square["size"]; $row++) {
            for ($col = $square["col"]; $col < $square["col"] + $square["size"]; $col++) {
                $map[$row][$col] = "x";
            }
        }
    }

    system("clear");
    foreach ($map as $value) {
        print $value . "\n";
    }
}

$filepath = $argv[1];
main($filepath);