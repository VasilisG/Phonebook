$(document).ready(function(){

    var overlay = $('.overlay');
    var deleteGroupPopup = $('.delete-group-popup-container');
    var deleteGroupActive = false;
    var $parent = null;
    var $groupId = null;

    $(document).on('click', '.group-item .actions .delete', function(){
        $parent = $(this).closest('.group-item');
        $groupId = $parent.attr('data-id');
        overlay.addClass('overlay-active');
        deleteGroupPopup.addClass('add-container-active');
        deleteGroupActive = true;
    });

    $(document).on('click', '.cancel-delete-group-button', function(){
        overlay.removeClass('overlay-active');
        deleteGroupPopup.removeClass('add-container-active');
        deleteGroupActive = false;
    });

    $(document).on('click', '.delete-group-button', function(){
        data = {'group_id' : $groupId};
        $.post('/phonebook/backend/group/delete.php', data)
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
        deleteGroupPopup.removeClass('add-container-active');
        deleteGroupActive = false;
    });

    overlay.on('click', function(){
        if(deleteGroupActive){
            overlay.removeClass('overlay-active');
            deleteGroupPopup.removeClass('add-container-active');
            deleteGroupActive = false;
        }
    });
});