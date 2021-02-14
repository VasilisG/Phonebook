$(document).ready(function(){

    var overlay = $('.overlay');

    var addContactPopup = $('.add-contact-popup-container');
    var addContactInput = $('.contact-input.first-name-input');
    var addContactGroupOptions = $('.contact-group-options');
    var addContactActive = false;

    $('.group-action.add-contact').on('click', function(){

        firstName = $('.first-name-input').val('');
        lastName = $('.last-name-input').val('');
        phoneNumber = $('.phone-number-input').val('');
        mobileNumber = $('.mobile-number-input').val('');
        email = $('.email-input').val('');

        $.get('/phonebook/backend/group/getGroups.php', function(data){
            result = JSON.parse(data);
            status = result['status'];
            message = result['message'];
            if(status == 'success'){
                addContactGroupOptions.empty();
                groups = result['groups'];
                $.each(groups, function(index, value){
                    addContactGroupOptions.append(
                        '<input class="group-checkbox" type="checkbox" id="group-' + value['id'] + '" name="' + value['name'] + '" value="' + value['name'] + '">' +
                        '<label class="group-checkbox-label" for="group-' + value['id'] + '">' + value['name'] + '</label></br>'
                    );
                });
                overlay.addClass('overlay-active');
                addContactPopup.addClass('add-container-active');
                addContactInput.focus();
                addContactActive = true;
            }
            else{
                M.toast({
                    html: message
                });
            }
        });

    });

    $('.add-contact-popup-container .add-contact-button').on('click', function(){
        firstName = $('.first-name-input').val();
        lastName = $('.last-name-input').val();
        phoneNumber = $('.phone-number-input').val();
        mobileNumber = $('.mobile-number-input').val();
        email = $('.email-input').val();
        groups = [];
        $('.group-checkbox').each(function(){
            if($(this).prop('checked')){
                groupId = $(this).attr('id').split('-')[1];
                groups.push(groupId);
            }
        });
        groups = groups.join('|');

        attributeValue = $('.sort-contact-attributes').val();
        orderValue = $('.sort-order').attr('order');
        groupId = $('.selected-group-item').attr('data-id');
        
        data = {
            'first_name' : firstName,
            'last_name' : lastName,
            'phone_number' : phoneNumber,
            'mobile_number' : mobileNumber,
            'email' : email,
        };

        $.post('/phonebook/backend/contact/add.php', data)
        .done(function(data){
            result = JSON.parse(data);
            status = result['status'];
            message = result['message'];
            if(status == 'success'){
                contacts = result['contacts'];
                $('.contact-items').empty();
                $.each(contacts, function(index, value){
                    favourite =  value['favourite'] ? 'in-favourite' : '';
                    $('.contact-items').append(
                        '<div class="contact-item card" data-id="' + value['id'] + '">' +
                            '<div class="full-name">' +
                                '<span class="first-name">' + value['first_name'] + '</span>' +
                                '<span class="last-name">' + value['last_name'] + '</span>' +
                            '</div>' +
                            '<div class="phone-numbers">' +
                                '<div class="telephone-number">' +
                                    '<i class="fas fa-phone"></i>' +
                                    '<a class="number" href="tel:' + value['telephone_number'] + '">' + value['telephone_number'] + '</a>' +
                                '</div>' +
                                '<div class="mobile-number">' +
                                    '<i class="fas fa-mobile-alt"></i>' +
                                    '<a class="number" href="tel:' + value['mobile_number'] + '">' + value['mobile_number'] + '</a>' +
                                '</div></div>' +
                            '<div class="email-container">' +
                                '<i class="fas fa-at"></i>' +
                                '<a class="email" href="mailto:' + value['email'] + '">' + value['email'] + '</a>' +
                            '</div>' +
                            '<div class="contact-actions">' +
                                '<button class="edit"><i class="fa fas fa-edit"></i></button>' +
                                '<button class="add-to-favourites' + favourite + '"><i class="fas fa-star"></i></button>' +
                                '<button class="delete"><i class="fa fas fa-trash"></i></button>' +
                            '</div>' +
                        '</div>'
                    );
                });
                M.toast({
                    html: message
                });

                $('.group-item').removeClass('selected-group-item');
                $('.group-item[data-id="1"]').addClass('selected-group-item');

                overlay.removeClass('overlay-active');
                addContactPopup.removeClass('add-container-active');
                addContactActive = false;
            }
            else{
                M.toast({
                    html: message
                });
            }
        });
    });

    $('.cancel-add-contact-button').on('click', function(){
        overlay.removeClass('overlay-active');
        addContactPopup.removeClass('add-container-active');
        addContactActive = false;
    });

    overlay.on('click', function(){
        if(addContactActive){
            overlay.removeClass('overlay-active');
            addContactPopup.removeClass('add-container-active');
            addContactActive = false;
        }
    });

});