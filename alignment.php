<?php 

/******
 * Author: Ethan Gruber
 * Date: 23 Oct. 2015
 * Function: a PHP script to merge TGN matches from Ryan Baumann's Pleiades-TGN script into Nomisma
 */

//generate arrays
$np = generate_json('nomisma-pleiades.csv');
$pt = generate_json('pleiades-tgn.csv');

$count = 0;
foreach ($np as $row){
	$pleiades = $row['match'];
	$tgn = '';
	foreach ($pt as $pt_row){
		if ($pt_row['pleiades'] == $pleiades){
			$np[$count]['tgn'] = $pt_row['tgn'];
			break;
		}
	}	
	$count++;
}

$file = fopen('nomisma-pleiades-tgn.csv', 'w');
fputcsv($file, array('nomisma','pleiades','tgn'));
foreach ($np as $row) {
	fputcsv($file, $row);
}

fclose($file);

/**** FUNCTIONS ****/
function generate_json($doc){
	$keys = array();
	$array = array();
	$csv = csvToArray($doc, ',');
	// Set number of elements (minus 1 because we shift off the first row)
	$count = count($csv) - 1;
	//Use first row for names
	$labels = array_shift($csv);
	foreach ($labels as $label) {
		$keys[] = $label;
	}
	// Bring it all together
	for ($j = 0; $j < $count; $j++) {
		$d = array_combine($keys, $csv[$j]);
		$array[$j] = $d;
	}
	return $array;
}
// Function to convert CSV into associative array
function csvToArray($file, $delimiter) {
	if (($handle = fopen($file, 'r')) !== FALSE) {
		$i = 0;
		while (($lineArray = fgetcsv($handle, 4000, $delimiter, '"')) !== FALSE) {
			for ($j = 0; $j < count($lineArray); $j++) {
				$arr[$i][$j] = $lineArray[$j];
			}
			$i++;
		}
		fclose($handle);
	}
	return $arr;
}
?>