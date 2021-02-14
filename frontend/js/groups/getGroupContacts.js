$(document).ready(function(){
    $(document).on('click', '.group-item', function(){
        $('.group-item').removeClass('selected-group-item');
        $(this).addClass('selected-group-item');
        groupId = $(this).attr('data-id');
        data = {'group_id' : groupId};
        $.post('/phonebook/backend/group/getContacts.php', data)
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
                                '<button class="add-to-favourites ' + favourite + '"><i class="fas fa-star"></i></button>' +
                                '<button class="delete"><i class="fa fas fa-trash"></i></button>' +
                            '</div>' +
                        '</div>'
                    );
                });
                M.toast({
                    html: message
                });
            }
            else{
                M.toast({
                    html: message
                });
            }
        });
    });
});