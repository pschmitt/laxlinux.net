<?php
	// HERE ARE THE PASSWORDS !!
    $passwd_file = realpath('../../../.login/DB_credentials.php');
	require "$passwd_file";
	
	$db = mysqli_connect($db_host, $db_user, $db_password, $db_name);

	if (mysqli_connect_error()) {
		die('Connect Error (' . mysqli_connect_errno() . ') '. mysqli_connect_error());
	}

	$query = "SELECT * FROM $table";

	if (!$db->query($query)) {
		printf("Error: %s\n", $db->error);
	}
	
function SQLResultTable($query) {
    $Table = "";  //initialize table variable
    $Table.= "<table border='1' style=\"border-collapse: collapse;\">"; //Open HTML Table
   
    if(!$result = $db->query($query))
        $Table.= "<tr><td>MySQL ERROR: " . $db->error . "</td></tr>";
    /*else {
        //Header Row with Field Names
        $NumFields = mysql_num_fields($result);
        $Table.= "<tr style=\"background-color: #969696; color: #000000;\">";
        for ($i=0; $i < $NumFields; $i++)
            $Table.= "<th>" . mysql_field_name($result, $i) . "</th>";
        $Table.= "</tr>";
   
        //Loop thru results
        $RowCt = 0; //Row Counter
        while($Row = mysql_fetch_assoc($result))
        {
            //Alternate colors for rows
            if($RowCt++ % 2 == 0) $Style = "background-color: #FFFFFF;";
            else $Style = "background-color: #DDDDDD;";
           
            $Table.= "<tr style=\"$Style\">";
            //Loop thru each field
            foreach($Row as $field => $value)
                $Table.= "<td>$value</td>";
            $Table.= "</tr>";
        }
        $Table.= "<tr style=\"background-color: #969696; color: #000000;\"><td colspan='$NumFields'>Query Returned " . mysql_num_rows($result) . " records</td></tr>";
    }
    $Table.= "</table>";
    */

	echo "CONNARD";
    return $Table;

}
	$db->close();

//Call the function like this:
echo SQLResultTable($query);

?>
