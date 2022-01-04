<?php

$info = file_get_contents('_info.ass');
$styles = file_get_contents('_styles.ass');

foreach(scandir('.') as $fn)
{
	if(!preg_match('/\\Hikaru no Go - (\d+).ass/i', $fn, $m)) continue;
	
	echo $fn.PHP_EOL;
	
	$episode = sprintf("%02d", (int)$m[1]);
	
	$orgass = file_get_contents($fn);

	$rows = explode("\n", $orgass);
	
	$output = false;
	
	$ass = $episode.'.ass';
	
	@unlink($ass);
	
	$fp = fopen($ass, "w");
	
	fputs($fp, chr(239).chr(187).chr(191));
	fputs($fp, str_replace('__EPISODE__', $episode, $info).PHP_EOL);
	fputs($fp, $styles.PHP_EOL);
	
	foreach($rows as $row)
	{
		$row = trim($row);
		
		if(empty($row)) continue;

		if($row == '[Events]')
		{
			$output = true;
		}
		
		if($output)
		{
			fputs($fp, $row.PHP_EOL);
		}
	}
	
	fclose($fp);
	
	file_get_contents($ass);
	
	$cmp = strcmp($orgass, $ass);
	
	if(strcmp($orgass, $ass) != 0)
	{
		echo 'replace'.PHP_EOL;
	
		unlink($fn);
	
		rename($ass, $fn);
	}
	else
	{
		unlink($ass);
	}
}

?>