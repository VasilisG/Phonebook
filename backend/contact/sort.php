<?php

include_once(__DIR__ . '/../db.php');

$attributeValue = $_GET['attribute_value'];
$orderValue = $_GET['order'];
$groupId = $_GET['group_id'];

if(empty($attributeValue) || empty($orderValue) || empty($groupId)){
    echo json_encode(['status' => 'error', 'message' => 'Invalid attribute or order values.']);
}
else {
    if($connection){
        $favouritesQuery = 'SELECT contact_id FROM contact_group_relation WHERE group_id=2';
        $favouritesResult = mysqli_query($connection, $favouritesQuery);
        $favourites = mysqli_fetch_all($favouritesResult);
        $favContacts = [];
        foreach($favourites as $favourite){
            $favContacts[] = $favourite[0];
        }

        $contactsQuery = 'SELECT * FROM contacts WHERE id IN (SELECT contact_id FROM contact_group_relation WHERE group_id='. $groupId . ') ORDER BY ' . $attributeValue . ' ' . strtoupper($orderValue);
        
        $contactQueryResult = mysqli_query($connection, $contactsQuery);
        $contactResults = mysqli_fetch_all($contactQueryResult);
        if(!empty($contactResults)){
            $contacts = [];
            foreach($contactResults as $contactResult){
                $isFavourite = False;
                if(in_array($contactResult[0], $favContacts)){
                    $isFavourite = True;
                }
                $contacts[] = [
                    'id' => $contactResult[0],
                    'first_name' => $contactResult[1],
                    'last_name' => $contactResult[2],
                    'telephone_number' => $contactResult[3],
                    'mobile_number' => $contactResult[4],
                    'email' => $contactResult[5],
                    'favourite' => $isFavourite
                ];
            }
            echo json_encode(['status' => 'success', 'message' => 'Contacts sorted.', 'contacts' => $contacts]);
        }
        else {
            echo json_encode(['status' => 'success', 'message' => 'No contacts.']);
        }
        mysqli_close($connection);
    }
    else {
        echo json_encode(['status' => 'error', 'message' => 'Could not connect to database.']);
    }
}