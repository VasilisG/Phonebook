<?php

include_once(__DIR__ . '/../db.php');

$contactId = $_GET['contact_id'];

if($connection){
    $groups = [];
    $contactGroups = [];

    $contactGroupsQuery = 'SELECT group_id FROM contact_group_relation WHERE contact_id='.$contactId;
    $contactGroupsResult = mysqli_query($connection, $contactGroupsQuery);
    if(!$contactGroupsResult){
        echo json_encode(['status' => 'error', 'message' => 'Could not fetch groups from database.']);
        return;
    }
    else{
        $contactResults = mysqli_fetch_all($contactGroupsResult);
        foreach($contactResults as $contactResult){
            $contactResults[] = $contactResult[0];
        }
    }

    $selectQuery = 'SELECT * FROM contact_group WHERE id<>1 AND id<>2 ORDER BY name ASC';
    $selectResult = mysqli_query($connection, $selectQuery);
    if(!$selectResult){
        echo json_encode(['status' => 'error', 'message' => 'Could not fetch groups from database.']);
        return;
    }
    else {
        $results = mysqli_fetch_all($selectResult);
        foreach($results as $result){
            $checked = in_array($result[0], $contactResults);
            $groups[] = ['id' => $result[0], 'name' => $result[1], 'checked' => $checked];
        }
        echo json_encode(['status' => 'success', 'message' => 'Groups loaded.', 'groups' => $groups]);
    }
}

else {
    echo json_encode(['status' => 'error', 'message' => 'Could not connect to database.']);
}