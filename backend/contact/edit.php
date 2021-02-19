<?php

include_once(__DIR__ . '/../db.php');

$contactId = $_POST['contact_id'];
$firstName = $_POST['first_name'];
$lastName = $_POST['last_name'];
$telephoneNumber = $_POST['phone_number'];
$mobileNumber = $_POST['mobile_number'];
$email = $_POST['email'];
$contactGroupsString = $_POST['groups'];

if(empty($firstName) && empty($lastName)){
    echo json_encode(['status' => 'error', 'message' => 'First or last name must be filled.']);
    return;
}
else {
    if(empty($telephoneNumber) && empty($mobileNumber)){
        echo json_encode(['status' => 'error', 'message' => 'Phone or mobile number must be filled.']);
        return;
    }
    else{
        if(strlen($telephoneNumber) != 10 || !ctype_digit($telephoneNumber) || strlen($mobileNumber) != 10 || !ctype_digit($mobileNumber)){
            echo json_encode(['status' => 'error', 'message' => 'Phone numbers must contain 10 digits.']);
            return;
        }
    }
}
if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    echo json_encode(['status' => 'error', 'message' => 'Invalid email address.']);
    return;
}

if($connection){

    $groups = [];
    $currentGroups = [];
    $groupsToAdd = [];
    $groupsToDelete = [];

    $selectQuery = 'SELECT id FROM contact_group WHERE id<>1 AND id<>5=2 ORDER BY name ASC';
    $selectResult = mysqli_query($connection, $selectQuery);
    if(!$selectResult){
        echo json_encode(['status' => 'error', 'message' => 'Could not fetch groups from database.']);
        return;
    }
    else {
        $results = mysqli_fetch_all($selectResult);
        foreach($results as $result){
            $groups[] = $result[0];
        }
    }

    $contactGroups = explode('|', $contactGroupsString);
    if($contactGroups == ''){
        $contactGroups = [];
    }
 
    $selectCurrentGroupsQuery = 'SELECT group_id FROM contact_group_relation WHERE contact_id=' . $contactId;
    $selectCurrentGroupsResult = mysqli_query($connection, $selectCurrentGroupsQuery);
    if(!$selectCurrentGroupsResult){
        echo json_encode(['status' => 'error', 'message' => 'Could not fetch groups from database.']);
        return;
    }
    else {
        $results = mysqli_fetch_all($selectCurrentGroupsResult);
        foreach($results as $result){
            if($result[0] == '1' || $result[0] == '5') continue;
            $currentGroups[] = $result[0];
        }

        $addGroupStatement = mysqli_prepare($connection, 'INSERT INTO contact_group_relation (contact_id, group_id) VALUES (?,?)');
        $deleteGroupStatement = mysqli_prepare($connection, 'DELETE FROM contact_group_relation WHERE contact_id=? AND group_id=?');

        foreach($contactGroups as $groupId){
            if(!in_array($groupId, $currentGroups)){
                mysqli_stmt_bind_param($addGroupStatement, 'ss', $contactId, $groupId);
                mysqli_stmt_execute($addGroupStatement);
            }
        }

        foreach($currentGroups as $groupId){
            if(!in_array($groupId, $contactGroups)){
                mysqli_stmt_bind_param($deleteGroupStatement, 'ss', $contactId, $groupId);
                mysqli_stmt_execute($deleteGroupStatement);
            }
        }
    }

    $updateStatement = mysqli_prepare($connection, 'UPDATE contacts SET first_name=?, last_name=?, telephone_number=?, mobile_number=?, email=? WHERE id=?');
    mysqli_stmt_bind_param($updateStatement, 'ssssss', $firstName, $lastName, $telephoneNumber, $mobileNumber, $email, $contactId);
    $updateResult = mysqli_stmt_execute($updateStatement);
    if(!$updateResult){
        echo json_encode(['status' => 'error', 'message' => 'Could not update contact in database.']);
        return;
    }
    else echo json_encode(
        ['status' => 'success', 
        'message' => 'Contact updated.', 
        'new_data' => [
            'contact_id' => $contactId,
            'first_name' => $firstName, 
            'last_name' => $lastName,
            'phone_number' => $telephoneNumber,
            'mobile_number' => $mobileNumber,
            'email' => $email
        ]
    ]);
}

else {
    echo json_encode(['status' => 'error', 'message' => 'Could not connect to database.']);
}