<?php

include_once(__DIR__ . '/../db.php');

$groupName = $_POST['group_name'];
if(empty($groupName)){
    echo json_encode(['status' => 'error', 'message' => 'Empty group name.']);
}
else {
    if($connection){
        $name = $groupName;
        $statement = mysqli_prepare($connection, 'INSERT INTO contact_group (name) VALUES (?)');
        mysqli_stmt_bind_param($statement, 's', $name);
        $result = mysqli_stmt_execute($statement);

        if($result){
            $selectQuery = 'SELECT * FROM contact_group WHERE id<>1 AND id<>2 ORDER BY name ASC';
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
                echo json_encode(['status' => 'success', 'message' => 'Group added.', 'groups' => $groups]);
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