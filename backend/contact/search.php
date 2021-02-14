<?php

include_once(__DIR__ . '/../db.php');

$searchValue = $_GET['search_value'];
if(empty($searchValue)){
    echo json_encode(['status' => 'error', 'message' => 'No search value was specified.']);
    return;
}
else{
    if($connection){

        $favouritesQuery = 'SELECT contact_id FROM contact_group_relation WHERE group_id=5';
        $favouritesResult = mysqli_query($connection, $favouritesQuery);
        $favourites = mysqli_fetch_all($favouritesResult);
        $favContacts = [];
        foreach($favourites as $favourite){
            $favContacts[] = $favourite[0];
        }

        $value = '%' . $searchValue . '%';
        $statement = mysqli_prepare($connection, 'SELECT * FROM contacts WHERE first_name LIKE ? OR last_name LIKE ? OR telephone_number LIKE ? OR mobile_number LIKE ? OR email LIKE ?');
        mysqli_stmt_bind_param($statement, 'sssss', $value, $value, $value, $value, $value);
        mysqli_stmt_execute($statement);
        $statementResult = mysqli_stmt_get_result($statement);

        if(!$statementResult){
            echo json_encode(['status' => 'error', 'message' => 'Could not fetch contacts from database.']);
        }
        else {
            $results = mysqli_fetch_all($statementResult);
            $contacts = [];
            foreach($results as $result){
                $isFavourite = False;
                if(in_array($result[0], $favContacts)){
                    $isFavourite = True;
                }
                $contacts[] = [
                    'id' => $result[0],
                    'first_name' => $result[1],
                    'last_name' => $result[2],
                    'telephone_number' => $result[3],
                    'mobile_number' => $result[4],
                    'email' => $result[5],
                    'favourite' => $isFavourite
                ];
            }
            echo json_encode(['status' => 'success', 'message' => 'Contacts filtered.', 'contacts' => $contacts]);
        }
        mysqli_stmt_close($statement);
        mysqli_close($connection);
    }
    else {
        echo json_encode(['status' => 'error', 'message' => 'Could not connect to database.']);
    }
}