$(document).ready(function(){
    $.get('/phonebook/backend/group/getGroups.php', function(data){
        result = JSON.parse(data);
        status = result['status'];
        message = result['message'];
        if(status == 'success'){
            groups = result['groups'];
            $('.group-item').not('.standard').remove();
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
        M.toast({
            html: message
        });
    });
});