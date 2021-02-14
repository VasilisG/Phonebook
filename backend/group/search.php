<?php

include_once(__DIR__ . '/../db.php');

$searchValue = $_GET['search_value'];
if(empty($searchValue)){
    echo json_encode(['status' => 'error', 'message' => 'No search value was specified.']);
    return;
}
else {
    if($connection){
        $value = '%' . $searchValue . '%';
        $statement = mysqli_prepare($connection, 'SELECT * FROM contact_group WHERE name LIKE ?');
        mysqli_stmt_bind_param($statement, 's', $value);
        mysqli_stmt_execute($statement);
        $statementResult = mysqli_stmt_get_result($statement);

        if(!$statementResult){
            echo json_encode(['status' => 'error', 'message' => 'Could not fetch groups from database.']);
        }
        else {
            $results = mysqli_fetch_all($statementResult);
            $groups = [];
            foreach($results as $result){
                $groups[] = ['id' => $result[0], 'name' => $result[1]];
            }
            echo json_encode(['status' => 'success', 'message' => 'Groups filtered.', 'groups' => $groups]);
        }
        mysqli_stmt_close($statement);
        mysqli_close($connection);
    }
    else {
        echo json_encode(['status' => 'error', 'message' => 'Could not establish a connection with the database.']);
    }
}