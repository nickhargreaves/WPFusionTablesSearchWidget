<?php
    //api key
    $api_key = $_GET['api_key'];

    //name
    $search = $_GET['q'];

    //column
    $column = $_GET['column'];

    //table
    $table = $_GET['table'];

    $url = "https://www.googleapis.com/fusiontables/v1/query?";

    $sql = "SELECT * FROM ".$table." WHERE ".$column." LIKE '%".$search."%'";

    $options = array("sql"=>$sql, "key"=>$api_key, "sensor"=>"false");

    $url .= http_build_query($options,'','&');

    $page = file_get_contents($url);

    $data = json_decode($page, TRUE);

    //if displaying single mode, get displayable columns as array

    if(isset($_GET['mode'])){
        $display_columns = $_GET['display_columns'];
        $display_columns = explode(",", $display_columns);
    }


    if(array_key_exists(('rows'), $data)){
        $columns = $data['columns'];
        $column_id = array_search($column, $columns);

        $rows = $data['rows'];

        foreach($rows as $row){

            if(isset($_GET['mode'])){
                $result = "<p>";
                //use requested display columns
                foreach($display_columns as $dc){
                    //get column index from name
                    $dc = trim($dc);
                    $dc_id = array_search($dc, $columns);
                    //add to row
                    $result .= "<strong>".$dc."</strong>: ".$row[$dc_id]."<br />";
                }
                $result .= "</p>";

            }else{
                $result = $row[$column_id]."\r\n";
            }
        }

        if(count($rows)<1){

            $result = "No result found. Check for spelling mistakes.";

        }

    }else{
        $result = "No result found. Check for spelling mistakes.";
    }

    print $result;
?>