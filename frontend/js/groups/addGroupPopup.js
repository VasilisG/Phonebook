$(document).ready(function(){

    var overlay = $('.overlay');

    var addGroup = $('.group-action.add-group');
    var cancelAddGroup = $('.cancel-add-group-button');
    var addGroupButton = $('.add-group-button');

    var addGroupPopup = $('.add-group-popup-container');
    var addGroupInput = $('.add-group-name-input');
    var addGroupActive = false;

    var invalidErrorMessage = $('.add-group-actions .invalid-error-message');

    addGroup.on('click', function(){
        overlay.addClass('overlay-active');
        addGroupPopup.addClass('add-container-active');
        invalidErrorMessage.text('');
        addGroupInput.val('');
        addGroupInput.focus();
        addGroupActive = true;
    });

    cancelAddGroup.on('click', function(){
        overlay.removeClass('overlay-active');
        addGroupPopup.removeClass('add-container-active');
        addGroupActive = false;
    });

    addGroupButton.on('click', function(){
        if(addGroupInput.val().length <= 0){
            invalidErrorMessage.text('Group name is empty.');
        }
        else{
            data = {'group_name' : addGroupInput.val()};
            $.post('/phonebook/backend/group/add.php',data)
             .done(function(data){
                result = JSON.parse(data);
                status = result['status'];
                if(status == 'success'){
                    M.toast({
                        html: result['message']
                    });
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
                else {
                    M.toast({
                        html: result['message']
                    });
                }
            });
            overlay.removeClass('overlay-active');
            addGroupPopup.removeClass('add-container-active');
            addGroupActive = false;
        }
    });

    overlay.on('click', function(){
        if(addGroupActive){
            overlay.removeClass('overlay-active');
            addGroupPopup.removeClass('add-container-active');
            addGroupActive = false;
        }
    });

});