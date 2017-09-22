<?php

require_once(dirname(__FILE__) . '/simplehtmldom_1_5/simple_html_dom.php');


// Index Animalium

$basedir = '/Volumes/WD Elements 1TB/rdf-archive/ion/html';
$files1 = scandir($basedir);

//$files1 = array('1020');

$go = false;

foreach ($files1 as $directory)
{
	if ($directory == 1605)
	{
		$go = true;
	}

	echo "-- $directory\n";
	
	if ($go)
	{
	if (preg_match('/^\d+$/', $directory))
	{	
		//echo $directory . "\n";
		
		$files2 = scandir($basedir . '/' . $directory);
		
		//$files2 = array('4518757.html');

		foreach ($files2 as $filename)
		{
			//echo $filename . "\n";
			if (preg_match('/^(?<id>\d+)\.html$/', $filename, $m))
			{	
				$id = $m['id'];
				

				$html = file_get_contents($basedir . '/' . $directory . '/' . $filename);
				
				if ($html != '' && preg_match('/<html/', $html))
				{
				
					$dom = str_get_html($html);

					$lis = $dom->find('li');
					foreach ($lis as $li)
					{
						//echo "-- " . $li->plaintext . "\n";
					
						if (preg_match('/^(?<text>.*)\s*\[Index Animalium Entry\]/', $li->plaintext, $m))
						{
							$obj = new stdclass;
							$obj->id = $id;

					
							$obj->citation = $m['text'];
							$obj->citation = preg_replace('/\.\s+$/u', '', $obj->citation);
					
							foreach ($li->find('a') as $a)
							{
								if (preg_match("/http:\/\/www.sil.si.edu\/digitalcollections\/indexanimalium\/volumes\/pagedisplaypage.cfm\?filename=(?<id>.*)/", $a->href, $m))
								{
									//print_r($m);
									$obj->indexanimalium = $m['id'];
								}

								if (preg_match("/http:\/\/biodiversitylibrary.org\/page\/(?<id>\d+)/", $a->href, $m))
								{
									//print_r($m);
									$obj->bhl = $m['id'];
								}
						
							}
						
						
							//print_r($obj);
					
							$keys = array();
							$values = array();
					
							foreach ($obj as $k => $v)
							{
								$keys[] = $k;
						
								switch ($k)
								{
									case 'citation':
									case 'indexanimalium':
										$values[] = '"' . addcslashes($v, '"') . '"';
										break;
								
									default:
										$values[] = $v;
										break;
								}
							}
							echo "REPLACE INTO names_indexanimalium_bhl(" . join(",", $keys) . ") VALUES (" . join(",", $values) . ");" . "\n";
						
						
						
						}
					
					
					}
				}

		
			}
		}
		
		
	}
	}
}	

?>
