$(document).ready(function(){

    var overlay = $('.overlay');

    var invalidErrorMessage = $('.edit-group-popup-container .add-group-actions .invalid-error-message');

    var editGroupPopup = $('.edit-group-popup-container');
    var editGroupInput = $('.edit-group-name-input');
    var editGroupActive = false;
    var groupId = null;
    var groupName = null;

    $(document).on('click', '.group-item .actions .edit', function(){
        groupId = $(this).closest('.group-item').attr('data-id');
        groupName = $(this).closest('.group-item').children('.group-name').text();
        overlay.addClass('overlay-active');
        editGroupPopup.addClass('add-container-active');
        editGroupInput.focus();
        editGroupInput.val(groupName);
        editGroupActive = true;
    });

    $(document).on('click', '.cancel-edit-group-button', function(){
        overlay.removeClass('overlay-active');
        editGroupPopup.removeClass('add-container-active');
        editGroupActive = false;
    });

    $(document).on('click', '.edit-group-button', function(){
        groupName = editGroupInput.val();
        if(groupName.length <= 0){
            invalidErrorMessage.text('Group name is empty.');
        }
        else {
            data = {'id' : groupId, 'name' : groupName};
            $.post('/phonebook/backend/group/edit.php', data)
            .done(function(data){
                result = JSON.parse(data);
                status = result['status'];
                message = result['message'];
                if(status == 'success'){
                    $('.group-item').not('.standard').remove();
                    groups = result['groups'];
                    $.each(groups, function(index, value){
                        $('.group-items').append(
                            '<div class="group-item" data-id="' + value['id'] + '">' +
                            '<span class="group-name">' + value['name']  + '</span>' +
                            '<div class="actions">' +
                            '<span class="edit"><i class="fa fas fa-edit"></i></span>' +
                            '<span class="delete"><i class="fa fas fa-trash"></i></span>' +
                            '</div>' +
                            '</div>'
                        );
                    });
                }
                else{
                    M.toast({
                        html: message
                    });
                }
            });
            overlay.removeClass('overlay-active');
            editGroupPopup.removeClass('add-container-active');
            editGroupActive = false;
        }
    });

    overlay.on('click', function(){
        if(editGroupActive){
            overlay.removeClass('overlay-active');
            editGroupPopup.removeClass('add-container-active');
            editGroupActive = false;
        }
    });
});