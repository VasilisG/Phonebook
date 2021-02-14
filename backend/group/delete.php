<?php

include_once(__DIR__ . '/../db.php');

$groupId = $_POST['group_id'];
if(empty($groupId)){
    echo json_encode(['status' => 'error', 'message' => 'Empty group ID.']);
}
else {
    if($connection){

        $relationStatement = mysqli_prepare($connection, 'DELETE FROM contact_group_relation WHERE group_id=?');
        mysqli_stmt_bind_param($relationStatement, 'i', $groupId);
        $relationResult = mysqli_stmt_execute($relationStatement);

        if(!$relationResult){
            echo json_encode(['status' => 'error', 'message' => 'Could not delete group from database.']);
            mysqli_stmt_close($relationStatement);
            return;
        }

        $statement = mysqli_prepare($connection, 'DELETE FROM contact_group WHERE id=?');
        mysqli_stmt_bind_param($statement, 'i', $groupId);
        $result = mysqli_stmt_execute($statement);

        if($result){
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
                echo json_encode(['status' => 'success', 'message' => 'Group deleted.', 'groups' => $groups]);
            }
        }
        else echo json_encode(['status' => 'error', 'message' => 'Could not delete group from database.']);

        mysqli_stmt_close($relationStatement);
        mysqli_stmt_close($statement);
        mysqli_close($connection);
    }
    else {
        echo json_encode(['status' => 'error', 'message' => 'Could not establish a connection with the database.']);
    }
}