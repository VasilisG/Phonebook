<?php

include_once(__DIR__ . '/../db.php');

if($connection){
    $selectQuery = 'SELECT * FROM contact_group WHERE id<>1 AND id<>5 ORDER BY name ASC';
    $selectResult = mysqli_query($connection, $selectQuery);
    if(!$selectResult){
        echo json_encode(['status' => 'error', 'message' => 'Could not fetch groups from database.']);
    }
    else {
        $results = mysqli_fetch_all($selectResult);
        $groups = [];
        foreach($results as $result){
            $groups[] = ['id' => $result[0], 'name' => $result[1]];
        }
        echo json_encode(['status' => 'success', 'message' => 'Groups loaded.', 'groups' => $groups]);
    }
}
else {
    echo json_encode(['status' => 'error', 'message' => 'Could not establish a connection to database.']);
}