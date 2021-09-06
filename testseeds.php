<?php

require 'vendor/autoload.php';
use jc21\CliTable;

// Returns maximum in array
function getMax($array)
{
   $n = count($array);
   $max = $array[0]["ttl"];
   for ($i = 0; $i < $n; $i++)
       if ($max < $array[$i]["ttl"])
           $max = $array[$i]["ttl"];
    return $max;
}

// Returns maximum in array
function getMin($array)
{
   $n = count($array);
   $min = $array[0]["ttl"];
   for ($i = 0; $i < $n; $i++)
       if ($min > $array[$i]["ttl"])
           $min = $array[$i]["ttl"];
    return $min;
}

$filename="seeds-DGB.txt";
if (!empty($argv[1])) {
  $filename = $argv[1];
}
$seedsnames = file($filename);
$dnsserver  = array("208.67.222.222");
$nodes = array();


if (!empty($argv[2])) {
  $dnsserver = array($argv[2]);
}
$authdns=array();
$results=array();

$data=array();
if(is_array($seedsnames)){ 
	foreach($seedsnames as $n => $entry){
		printf("\rQuerying DNS info %s/%s",$n+1,sizeof($seedsnames));
		$row=array();
		$entry = rtrim($entry);
		$row['hostname'] = $entry;
		$answer = dns_get_record($entry,DNS_NS,$dnsserver);
		$row['nsrecords'] = sizeof($answer);
		
		$authdns=array();
		foreach($answer as $key => $host){
			array_push($authdns,$host["target"]);
		}
		if(sizeof($authdns)==0) $authdns=array($dnsserver);

		$answer = dns_get_record($entry,DNS_A,$authdns);
		$row['arecords'] = sizeof($answer);
		if(is_array($answer)){
			array_push($results,$answer);
			$hosts=array();
			foreach($answer as $key => $host){
				array_push($hosts,$host["ip"]);
				array_push($nodes,$host["ip"]);
			}
			$row['uarecords'] = sizeof(array_unique($hosts));
			$row['minttl'] = getMin($answer);
			$row['maxttl'] = getMax($answer);
			$row['hash'] = md5(serialize(implode("",$hosts)));
			asort($hosts);
			$row['hash2'] = md5(serialize(implode("",$hosts)));
		}
		array_push($data,$row);
        }
echo "\r";
$table = new CliTable;
$table->setTableColor('blue');
$table->setHeaderColor('cyan');
$table->addField('Hostname', 'hostname',    false,                               'yellow');
$table->addField('NS records', 'nsrecords',    false,                               'white');
$table->addField('A records', 'arecords',    false,                               'white');
$table->addField('Unique', 'uarecords',    false,                               'white');
$table->addField('Min TTL', 'minttl',    false,                               'white');
$table->addField('Max TTL', 'maxttl',    false,                               'white');
$table->addField('IP list hash', 'hash',    false,                               'white');
$table->addField('IP sorted list hash', 'hash2',    false,                               'white');
$table->injectData($data);
$table->display();
printf("Number of unique nodes: %s\r\n",sizeof(array_unique(($nodes))));
}
?>
