<?php

include_once(__DIR__ . '/../db.php');

$contactId = $_POST['id'];
$action = $_POST['action'];
$groupId = '2';

if($connection){
    if($action == 'add'){
        $addStatement = mysqli_prepare($connection, 'INSERT INTO contact_group_relation (contact_id, group_id) VALUES (?,?)');
        mysqli_stmt_bind_param($addStatement, 'ss', $contactId, $groupId);
        $addResult = mysqli_stmt_execute($addStatement);
        if(!$addResult){
            echo json_encode(['status' => 'error', 'message' => 'Could not add contact to favourites.']);
        }
        else {
            echo json_encode(['status' => 'success', 'message' => 'Contact added to favourites.']);
        }
    }
    else{
        $removeStatement = mysqli_prepare($connection, 'DELETE FROM contact_group_relation WHERE contact_id=? AND group_id=?');
        mysqli_stmt_bind_param($removeStatement, 'ss', $contactId, $groupId);
        $removeResult = mysqli_stmt_execute($removeStatement);
        if(!$removeResult){
            echo json_encode(['status' => 'error', 'message' => 'Could not remove contact from favourites.']);
        }
        else {
            echo json_encode(['status' => 'success', 'message' => 'Contact removed from favourites.']);
        }
    }
}
else{
    echo json_encode(['status' => 'error', 'message' => 'Could not connect to database.']);
}