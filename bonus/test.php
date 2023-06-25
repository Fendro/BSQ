<?php

function genMap($x, $y, $density): void
{
    $handle = fopen("map.txt", "w");
    $i = 0;
    $j = 0;
    fwrite($handle, $y . "\n");
    while ($i < $y) {
        $j = 0;
        while ($j < $x) {
            ((rand(0, $y) * 2) < $density)
                ? fwrite($handle, "o")
                : fwrite($handle, ".");
            $j++;
        }
        fwrite($handle, "\n");
        $i++;
    }
}

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
    $remainingRows = $rowCount;
    while ($row < $rowCount && $remainingRows > $square["size"]) {
        $col = 0;
        $streak = 0;
        while ($col < $rowLength && ($rowLength - $col + $streak) > $square["size"]) {
            ($map[$row][$col] === ".")
                ? $streak++
                : $streak = 0;

            if ($streak > $square["size"] && $remainingRows >= $streak) {
                $obstacle = false;
                $xOffset = $col;
                $xMin = $xOffset - $streak;
                while ($xOffset > $xMin) {
                    $yOffset = $row + 1;
                    $yMax = $row + $streak;
                    while ($yOffset < $yMax) {
                        if ($map[$yOffset][$xOffset] === "o") {
                            $obstacle = true;
                            $col = $xOffset;
                            $streak = 0;
                            break(2);
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

    foreach ($map as $value) {
        print $value . "\n";
    }
}

$filepath = $argv[1];
$tests = [
    1 => ["x" => 97, "y" => 21],
    2 => ["x" => 1, "y" => 1],
    3 => ["x" => 100, "y" => 1],
    4 => ["x" => 1, "y" => 100],
    5 => ["x" => 34, "y" => 137],
    6 => ["x" => 187, "y" => 187],
    7 => ["x" => 0, "y" => 0],
    8 => ["x" => 2000, "y" => 2000]
];
$densities = [0, 0.25, 0.50, 0.75, 1];

$timeTaken = 0;
$sampleSize = $tests[6];
$density = $densities[2] * $sampleSize["y"];

$runs = 100;
for ($i = 0; $i < $runs; $i++) {
    genMap($sampleSize["x"], $sampleSize["y"], $density);
    $startTime = microtime(true);
    main($filepath);
    $timeTaken += microtime(true) - $startTime;
}

print "--- --- --- --- --- --- --- --- --- ---" . PHP_EOL;
print "Script ended in : " . ($timeTaken / $runs) . "s on average." . PHP_EOL;
