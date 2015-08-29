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

    $sql = "SELECT * FROM ".$table." WHERE ".$column." LIKE IGNORING CASE '%".$search."%'";

    $options = array("sql"=>$sql, "key"=>$api_key, "sensor"=>"false");

    $url .= http_build_query($options,'','&');

    $page = file_get_contents($url);

    $data = json_decode($page, TRUE);

    if(array_key_exists(('rows'), $data)){
        $rows = $data['rows'];

        foreach($rows as $row){
            $result = $row['1']."\r\n";

        }

        if(count($rows)<1){

            $result = "No result found. Check for spelling mistakes.";

        }

    }else{
        $result = "No result found. Check for spelling mistakes.";
    }

    print $result;
?>