<?php
// load FreeBPX bootstrap environment, requires FreePBX 2.9 or higher
if (!@include_once(getenv('FREEPBX_CONF') ? getenv('FREEPBX_CONF') : '/etc/freepbx.conf')) {
include_once('/etc/asterisk/freepbx.conf');
}
 
// set FreePBX globals
global $db;  // FreePBX asterisk database connector
global $amp_conf;  // array with Asterisk configuration
global $astman;  // AMI
 
$sql = "SELECT name,extension FROM users ORDER BY extension";
$results = $db->getAll($sql, DB_FETCHMODE_ORDERED);  // 2D array of all FreePBX users
$numrows = count($results);
$endoflist = False;

// XML Output Below
header ("content-type: text/xml");

echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";
echo "<YealinkIPPhoneDirectory Beep=\"yes\" timeout=\"30\" LockIn=\"no\">\n";
echo "	<Title wrap=\"yes\">PBX Directory: Select A User</Title>\n";
	
if ($numrows >=32) {
	// set up variables for dealing with >32 entries
	$page = $_GET["page"];
	if (empty($page)) {
	        $page = 0;      // set first page by default
	}
	$count = $page * 32 ;
	for ($row=$count; $row <= $count+32; $row++) {
	    if (is_null($results[$row][0])) {
            	$endoflist = True;
            }
            else {
	            $endoflist = False;
		    	echo "	<MenuItem>\n";
		    	echo "		<Prompt>" . $results[$row][0] . "</Prompt>\n";
		    	echo "		<URI>" . $results[$row][1] . "</URI>\n";
		    	echo "	</MenuItem>\n";
            }
	}

}    

echo "</YealinkIPPhoneDirectory>";
       
//END
?>
