<?php

function main(string $filepath): void
{
    $content = file_get_contents($filepath);
    $map = explode("\n", $content);
    $rowCount = array_shift($map);
    $rowLength = strlen($map[0]);
    if (strlen($map[$rowCount]) < $rowLength)
        array_pop($map);
    $square = [
        "row" => null,
        "col" => null,
        "size" => 0
    ];

    $row = 0;
    $remainingRows = $rowCount - $row;
    while ($row < $rowCount && $remainingRows > $square["size"]) {
        $col = 0;
        $streak = 0;
        while ($col < $rowLength && ($rowLength - $col + $streak) > $square["size"]) {
            ($map[$row][$col] === ".")
                ? $streak++
                : $streak = 0;

            if ($streak > $square["size"] && $remainingRows > $streak) {
                $obstacle = false;
                $yOffset = $row + 1;
                $yMax = $row + $streak;
                while ($yOffset < $yMax) {
                    $xOffset = $col - $streak + 1;
                    $xMax = $xOffset + $streak;
                    while ($xOffset < $xMax) {
                        if ($map[$yOffset][$xOffset] === "o") {
                            $obstacle = true;
                            $col = $xOffset;
                            $streak = 0;
                            break(2);
                        }
                        $xOffset++;
                    }
                    $yOffset++;
                }

                if (!$obstacle)
                    $square = ["row" => $row, "col" => $col - $streak + 1, "size" => $streak];
            }
            $col++;
        }
        $row++;
        $remainingRows = $rowCount - $row;
    }

    for ($row = $square["row"]; $row < $square["row"] + $square["size"]; $row++) {
        for ($col = $square["col"]; $col < $square["col"] + $square["size"]; $col++) {
            $map[$row][$col] = "x";
        }
    }

    foreach ($map as $value) {
        print $value . "\n";
    }
}

$filepath = $argv[1];
main($filepath);
