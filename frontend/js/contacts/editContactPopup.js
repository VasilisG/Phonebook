$(document).ready(function(){

    var overlay = $('.overlay');
    var editContactPopup = $('.edit-contact-popup-container');
    var addContactGroupOptions = $('.contact-group-options');
    var editContactActive = false;
    var editContactNameInput = editContactPopup.find('.edit-contact-name-input');
    var parent = null;
    var contactId = null;

    $(document).on('click', '.contact-actions .edit', function(){
        parent = $(this).closest('.contact-item');
        contactId = parent.attr('data-id');

        firstName = parent.find('.first-name').text();
        lastName = parent.find('.last-name').text();
        telephoneNumber = parent.find('.telephone-number .number').text();
        mobileNumber = parent.find('.mobile-number .number').text();
        email = parent.find('.email').text();

        editContactPopup.find('.first-name-input').val(firstName);
        editContactPopup.find('.last-name-input').val(lastName);
        editContactPopup.find('.phone-number-input').val(telephoneNumber);
        editContactPopup.find('.mobile-number-input').val(mobileNumber);
        editContactPopup.find('.email-input').val(email);

        $.get('/phonebook/backend/contact/getContactGroups.php', {'contact_id' : contactId}, function(data){
            result = JSON.parse(data);
            status = result['status'];
            message = result['message'];
            if(status == 'success'){
                addContactGroupOptions.empty();
                groups = result['groups'];
                $.each(groups, function(index, value){
                    checkedProp = value['checked'] ? 'checked' : '';
                    addContactGroupOptions.append(
                        '<input class="group-checkbox" type="checkbox" id="group-' + value['id'] + '" name="' + value['name'] + '" value="' + value['name'] + '" ' + checkedProp + '>' +
                        '<label class="group-checkbox-label" for="group-' + value['id'] + '">' + value['name'] + '</label></br>'
                    );
                });
                overlay.addClass('overlay-active');
                editContactPopup.addClass('add-container-active');
                editContactNameInput.focus();
                editContactActive = true;
            }
            else{
                M.toast({
                    html: message
                });
            }
        });
    });

    $(document).on('click', '.cancel-edit-contact-button', function(){
        overlay.removeClass('overlay-active');
        editContactPopup.removeClass('add-container-active');
        editContactActive = false;
    });

    $(document).on('click', '.edit-contact-button', function(){
        groups = [];
        $('.edit-contact-popup-container .group-checkbox').each(function(){
            if($(this).prop('checked') == true){
                groupId = $(this).attr('id').split('-')[1];
                groups.push(groupId);
                console.log('Group: ' + groupId + ' is selected.');
            }
        });
        groups = groups.join('|')

        firstName = editContactPopup.find('.first-name-input').val();
        lastName = editContactPopup.find('.last-name-input').val();
        telephoneNumber = editContactPopup.find('.phone-number-input').val();
        mobileNumber = editContactPopup.find('.mobile-number-input').val();
        email = editContactPopup.find('.email-input').val();

        newData = {
            'contact_id' : contactId,
            'first_name' : firstName,
            'last_name' : lastName,
            'phone_number' : telephoneNumber,
            'mobile_number' : mobileNumber,
            'email' : email,
            'groups' : groups
        };

        $.post('/phonebook/backend/contact/edit.php', newData, function(data){
            result = JSON.parse(data);
            status = result['status'];
            message = result['message'];
            newData = result['new_data']
            if(status == 'success'){
                parent.attr('data-id', newData['contact_id']);
                parent.find('.first-name').text(newData['first_name']);
                parent.find('.last-name').text(newData['last_name']);
                parent.find('.telephone-number .number').text(newData['phone_number']);
                parent.find('.mobile-number .number').text(newData['mobile_number']);
                parent.find('.email').text(newData['email']);
            }
            overlay.removeClass('overlay-active');
            editContactPopup.removeClass('add-container-active');
            editContactActive = false;

            M.toast({
                html: message
            });
        });
    });

});