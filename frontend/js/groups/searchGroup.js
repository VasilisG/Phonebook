$(document).ready(function(){
    $(document).on('submit', '#search-group-form', function(){
        var searchValue = $('#group-search').val();
        if(searchValue.length > 0){
            data = {'search_value' : searchValue};
            $.get('/phonebook/backend/group/search.php', data)
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
        }
        return false;
    });
});