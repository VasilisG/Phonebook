<?php

include_once(__DIR__ . '/../db.php');

$firstName = $_POST['first_name'];
$lastName = $_POST['last_name'];
$phoneNumber = $_POST['phone_number'];
$mobileNumber = $_POST['mobile_number'];
$email = $_POST['email'];
$groups = $_POST['groups'];

if(empty($firstName) && empty($lastName)){
    echo json_encode(['status' => 'error', 'message' => 'First or last name must be filled.']);
    return;
}
else {
    if(empty($phoneNumber) && empty($mobileNumber)){
        echo json_encode(['status' => 'error', 'message' => 'Phone or mobile number must be filled.']);
        return;
    }
    else{
        if(strlen($phoneNumber) != 10 || !ctype_digit($phoneNumber) || strlen($mobileNumber) != 10 || !ctype_digit($mobileNumber)){
            echo json_encode(['status' => 'error', 'message' => 'Phone numbers must contain 10 digits.']);
            return;
        }
    }
}
if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    echo json_encode(['status' => 'error', 'message' => 'Invalid email address.']);
    return;
}
else {
    $currentDate = date('Y-m-d');
    if($connection){
        $contactStatement = mysqli_prepare($connection, 'INSERT INTO contacts (first_name, last_name, telephone_number, mobile_number, email, date_added) VALUES (?,?,?,?,?,?)');
        mysqli_stmt_bind_param($contactStatement, 'ssssss', $firstName, $lastName, $phoneNumber, $mobileNumber, $email, $currentDate);
        $contactResult = mysqli_stmt_execute($contactStatement);
        if($contactResult){
            $newContactId = mysqli_insert_id($connection);
            $groupIds = explode('|', $groups);
            array_unshift($groupIds, 1);
            $groupStatement = mysqli_prepare($connection, 'INSERT INTO contact_group_relation (contact_id, group_id) VALUES (?,?)');
            foreach($groupIds as $groupId){
                mysqli_stmt_bind_param($groupStatement, 'ss', $newContactId, $groupId);
                $groupResult = mysqli_stmt_execute($groupStatement);
            }
            mysqli_stmt_close($groupStatement);

            $favouritesQuery = 'SELECT contact_id FROM contact_group_relation WHERE group_id=5';
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
                echo json_encode(['status' => 'success', 'message' => 'Contact added.', 'contacts' => $contacts]);
            }
        }
        else{
            echo json_encode(['status' => 'error', 'message' => 'Could not add contact to database: ' . mysqli_error($connection)]);
        }
        mysqli_stmt_close($contactStatement);
        mysqli_close($connection);
    }
    else{
        echo json_encode(['status' => 'error', 'message' => 'Could not connect to database.']);
    }
}