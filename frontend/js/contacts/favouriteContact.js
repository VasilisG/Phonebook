$(document).ready(function(){
    $(document).on('click', '.add-to-favourites', function(){
        self = $(this);
        contactId = self.closest('.contact-item').attr('data-id');
        isFavourite = self.closest('.add-to-favourites').hasClass('in-favourite');
        action = isFavourite ? 'remove' : 'add';
        data = {'id' : contactId, 'action' : action};
        $.post('/phonebook/backend/contact/favouriteContact.php',data)
            .done(function(data){
                result = JSON.parse(data);
                status = result['status'];
                message = result['message'];
                if(status == 'success'){
                    self.toggleClass('in-favourite');
                    M.toast({
                        html: message
                    });
                }
            });
    });
});