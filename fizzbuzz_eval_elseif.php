<?php
/**
 * Created by PhpStorm.
 * User: scottconrad
 * Date: 12/11/14
 * Time: 4:44 PM
 */
//gc_disable();
$start = microtime(true);

	$counter = 0;
do {
	$i = 1;
	while($i <= 100){
		if($i < 3){
			++$i;
			//print $i;
			continue;
		}
		if($i % 3 == 0 && $i % 5 !== 0){
			//echo "Click \n";
		}elseif($i % 5 == 0 && $i % 3 !== 0){
			//echo "Booth \n";
		}elseif($i % 5 == 0 && $i % 3 == 0){
			//echo "ClickBooth \n";
		}else{
			//echo $i."\n";
		}
		++$i;
	}

	$counter ++;
}while($counter !== 10000000);

$end = microtime(true);

$total = $end - $start;

print "\n Took {$total} to run on fizzbuzz_eval_elseif.php: ";


// 15.956247091293 to run



