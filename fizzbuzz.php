<?php
/**
 * Created by PhpStorm.
 * User: scottconrad
 * Date: 12/11/14
 * Time: 4:39 PM
 */
$start = microtime(true);
$counter = 0;
do {
	//print ".";
	$i = 1;
	while($i <= 100){
		if($i < 3){
			++$i;
			//print $i;
			continue;

		}
		$three = $i % 3 == 0;
		$five = $i % 5 == 0;
		if ($three && !$five){
			//echo "Click \n";
		}

		if ($five && !$three) {
			//echo "Booth \n";

		}
		if ($five && $three){
			//echo "ClickBooth \n";
		}
		if (!$five && !$three) {
			//echo $i . "\n";
		}
		++$i;
	}

	$counter ++;
}while($counter !== 10000000);

$end = microtime(true);

$total = $end - $start;

print "\n Took {$total} to run on fizzbuzz.php: ";


// 15.956247091293 to run



