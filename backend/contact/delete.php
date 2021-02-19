<?php

include_once(__DIR__ . '/../db.php');

$contactId = $_POST['contact_id'];

if($connection){
    $removeContactGroupsQuery = 'DELETE FROM contact_group_relation WHERE contact_id=' . $contactId;
    $removeContactGroupsResult = mysqli_query($connection, $removeContactGroupsQuery);
    if(!$removeContactGroupsResult){
        echo json_encode(['status' => 'error', 'message' => 'Could not delete contact groups from database.']);
        return;
    }

    $deleteContactQuery = 'DELETE FROM contacts WHERE id=' . $contactId;
    $deleteContactResult = mysqli_query($connection, $deleteContactQuery);
    if(!$deleteContactResult){
        echo json_encode(['status' => 'error', 'message' => 'Could not delete contact from database.']);
        return;
    }

    $favouritesQuery = 'SELECT contact_id FROM contact_group_relation WHERE group_id=2';
    $favouritesResult = mysqli_query($connection, $favouritesQuery);
    $favourites = mysqli_fetch_all($favouritesResult);
    $favContacts = [];
    foreach($favourites as $favourite){
        $favContacts[] = $favourite[0];
    }
    
    $selectQuery = 'SELECT * FROM contacts';
    $selectResult = mysqli_query($connection, $selectQuery);
    if(!$selectResult){
        echo json_encode(['status' => 'error', 'message' => 'Could not fetch contacts from database.']);
    }
    else {
        $results = mysqli_fetch_all($selectResult);
        $contacts = [];

        if(count($results) == 0){
            echo json_encode(['status' => 'no-contacts']);
        }
        else {
            foreach($results as $result){
                $isFavourite = False;
                if(in_array($result[0], $favContacts)){
                    $isFavourite = True;
                }
                $contacts[] = ['id' => $result[0],
                               'first_name' => $result[1], 
                               'last_name' => $result[2], 
                               'telephone_number' => $result[3],
                               'mobile_number' => $result[4],
                               'email' => $result[5],
                               'favourite' => $isFavourite
                            ];
            }
            echo json_encode(['status' => 'success', 'message' => 'Contacts loaded.', 'contacts' => $contacts]);
        }
    }
    mysqli_close($connection);
}
else {
    echo json_encode(['status' => 'error', 'message' => 'Could not connect to database.']);
}