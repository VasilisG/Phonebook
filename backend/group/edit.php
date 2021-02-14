<?php

include_once(__DIR__ . '/../db.php');

$groupId = $_POST['id'];
$groupName = $_POST['name'];

if(empty($groupId)){
    echo json_encode(['status' => 'error', 'message' => 'No ID for specified group name exists.']);
    return;
}

if(empty($groupName)){
    echo json_encode(['status' => 'error', 'message' => 'Could not find group with specific name.']);
}
else {
    if($connection){
        $id = $groupId;
        $name = $groupName;
        $statement = mysqli_prepare($connection, 'UPDATE contact_group SET name=? WHERE id=?');
        mysqli_stmt_bind_param($statement, 'si', $name, $id);
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
                echo json_encode(['status' => 'success', 'message' => 'Group edited.', 'groups' => $groups]);
            }
        }
        else echo json_encode(['status' => 'error', 'message' => 'Could not insert group to database.']);

        mysqli_stmt_close($statement);
        mysqli_close($connection);
    }
    else {
        echo json_encode(['status' => 'error', 'message' => 'Could not establish a connection with the database.']);
    }
}