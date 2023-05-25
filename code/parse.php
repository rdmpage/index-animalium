<?php

// Parse broken CSV

$headings = array();

$row_count = 0;

$mode = 0;

if ($mode == 0)
{
	$filename = "../smithsonian/2006.01.06.BibliographicData.csv";
	
	$header = array('bib_id', 'x', 'sil', 'citation', 'flag1', 'flag2');
	
	echo join("\t", $header), "\n";
}
else
{
	$filename = "../smithsonian/2006.01.06.TaxonomicData.csv";
	
	$header = array('record_id', 'sil', 'bib_id', 'text', 'flag');
	
	echo join("\t", $header), "\n";
}

$file_handle = fopen($filename, "r");
while (!feof($file_handle)) 
{
	$line = trim(fgets($file_handle));
	
	// echo $line . "\n";
	
	if ($mode == 0)
	{

		// 521,xxii,SIL34_01_01_0028,
		if (preg_match('/(\d+),([^,]+),(SIL[^,]+),(.*),([^,]*),(.*)$/', $line, $m))
		{
			$m[4] = html_entity_decode($m[4], ENT_QUOTES | ENT_HTML5, 'UTF-8');
	
			//print_r($m);
			
			$row = array();
			for ($i = 1; $i <= 6; $i++)
			{
				$row[] = $m[$i];
			}
			echo join("\t", $row) . "\n";
		}
		else
		{
			//echo "Not parsed\n";
			//echo $line . "\n";
		}
	}
	else
	{
		// 1,SIL34_01_01_0063,lvii,References to "Cuvier, Le&#x000E7;ons Anat. Comp.," refer to <em>table</em> not <em>page</em>.,
		if (preg_match('/(\d+),(SIL[^,]+),([^,]*),(.*),(.*)$/i', $line, $m))
		{
			$m[4] = html_entity_decode($m[4], ENT_QUOTES | ENT_HTML5, 'UTF-8');
	
			//print_r($m);
			
			$m[2] = strtoupper($m[2]);
			
			$row = array();
			for ($i = 1; $i <= 5; $i++)
			{
				$row[] = $m[$i];
			}
			echo join("\t", $row) . "\n";
			
		}
		else
		{
			//echo "Not parsed\n";
			//echo $line . "\n";
			//exit();
		}
		
	}

	$row_count++;	
	
	if ($row_count > 5)
	{
		//exit();
	}
	
}	

?>
