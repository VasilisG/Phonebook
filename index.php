<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Phonebook</title>

        <link rel="stylesheet" href="./frontend/css/style.css"/>
        <link rel="stylesheet" href="./frontend/lib/materialize/css/materialize.min.css"/>
        <link rel="stylesheet" href="./frontend/lib/fontawesome/css/all.css"/>

        <script defer src="./frontend/lib/fontawesome/js/all.js"></script>
        <script src="./frontend/lib/jquery/jquery-3.5.1.min.js"></script>
        <script src="./frontend/lib/materialize/js/materialize.min.js"></script>

        <script src='./frontend/js/groups/getGroups.js'></script>
        <script src='./frontend/js/contacts/getContacts.js'></script>

        <script src="./frontend/js/contacts/addContactPopup.js"></script>
        <script src="./frontend/js/contacts/favouriteContact.js"></script>
        <script src="./frontend/js/contacts/deleteContactPopup.js"></script>
        <script src="./frontend/js/contacts/editContactPopup.js"></script>
        <script src="./frontend/js/contacts/searchContact.js"></script>
        <script src="./frontend/js/contacts/sortContacts.js"></script>

        <script src="./frontend/js/groups/addGroupPopup.js"></script>
        <script src="./frontend/js/groups/editGroupPopup.js"></script>
        <script src="./frontend/js/groups/deleteGroupPopup.js"></script>
        <script src="./frontend/js/groups/searchGroup.js"></script>
        <script src="./frontend/js/groups/refreshGroups.js"></script>
        <script src="./frontend/js/groups/getGroupContacts.js"></script>
    </head>
    <body>
        <div class="page">
            <div class='header-container z-depth-3'>
                <div class='header-inner-container'>
                    <h1 class='title'>Phonebook</h1>
                    <p class='motto'>Well...you know, like a normal phonebook, but digital.</p>
                </div>
            </div>
            <div class="main">
                <div class="main-content">

                    <div class="groups-container">
                        <div class="main-content-title-container valign-wrapper left-align">
                            <i class="fa fas fa-users"></i>
                            <h3 class="main-content-title">Groups</h3>
                        </div>
                        <hr class="title-separator"/>
                        <div class="group-items">
                            <div class="group-item standard selected-group-item" data-id="1"><span class="group-name">All</span></div>
                            <div class="group-item standard" data-id="5"><span class="group-name">Favourites</span></div>
                            <!-- <div class="group-item">
                                <span class="group-name">Custom group</span>
                                <div class="actions">
                                    <span class="edit"><i class="fa fas fa-edit"></i></span>
                                    <span class="delete"><i class="fa fas fa-trash"></i></span>
                                </div>
                            </div> -->
                        </div>
                        <hr class="title-separator"/>
                        <div class="group-actions">
                            <button class="group-action add-group"><span><i class="fas fa-plus"></i>Add group</span></button>
                            <form id="search-group-form" class="group-action search-group">
                                <input type="text" placeholder="Search groups..." id="group-search"/>
                                <button type="submit" class="action-search-button"><i class="fas fa-search"></i></button>
                            </form>
                            <button class="group-action refresh-groups"><span><i class="fas fa-sync"></i>Refresh groups</span></button>
                        </div>
                    </div>

                    <div class="contacts-container">
                        <div class="main-content-title-container valign-wrapper left-align">
                            <div class="main-container-inner-title-container">
                                <i class="fa fas fa-address-book"></i>
                                <h3 class="main-content-title">Contacts</h3>
                            </div>
                            <form id="search-contact-form" class="group-action search-contacts">
                                <input type="text" placeholder="Search contacts..." id="contact-search"/>
                                <button type="submit" class="action-search-button"><i class="fas fa-search"></i></button>
                            </form>
                        </div>
                        <hr class="title-separator"/>
                        <div class="contact-items">

                            <!-- <div class="contact-item card">
                                <div class="full-name">
                                    <span class="first-name">John</span>
                                    <span class="last-name">Doe</span>
                                </div>
                                <div class="phone-numbers">
                                    <div class="telephone-number">
                                        <i class="fas fa-phone"></i>
                                        <a class="number" href='tel:1234567890'>1234567890</a>
                                    </div>
                                    <div class="mobile-number">
                                        <i class="fas fa-mobile-alt"></i>
                                        <a class="number" href='tel:1234567890'>1234567890</a>
                                    </div>
                                </div>
                                <div class="email-container">
                                    <i class="fas fa-at"></i>
                                    <a class="email" href="mailto:test@example.com">test@example.com</a>
                                </div>
                                <div class="contact-actions">
                                    <span class="edit"><i class="fa fas fa-edit"></i></span>
                                    <span class="add-to-favourites is-favourite"><i class="fas fa-star"></i></span>
                                    <span class="delete"><i class="fa fas fa-trash"></i></span>
                                </div>
                            </div> -->

                        </div>
                        <div class="contact-user-actions">

                            <div class="group-action sort-contacts">
                                <label for="sort-contacts">Sort:</label>
                                <select name="sort-contacts" class="sort-contact-attributes">
                                    <option value="first_name">First name</option>
                                    <option value="last_name">Last name</option>
                                    <option value="telephone_number">Telephone number</option>
                                    <option value="mobile_number">Mobile number</option>
                                    <option value="email">Email</option>
                                </select>
                                <span class="sort-order" order="asc"><i class="fas fa-arrow-up"></i></span>
                            </div>
                            <button class="group-action add-contact"><span><i class="fas fa-plus"></i>Add contact</span></button>

                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="background-container">
            <div class="background-image-layer"></div>
        </div>

        <div class="overlay"></div>

        <div class="add-group-popup-container">
            <div class="popup-inner-container">
                <input class="group-name-input add-group-name-input" placeholder="Enter name of group..." type="text"/>
                <div class="add-group-actions">
                    <span class="invalid-error-message"></span>
                    <button class="cancel cancel-add-group-button">Cancel</button>
                    <button class="add add-group-button">Add</button>
                </div>
            </div>
        </div>

        <div class="edit-group-popup-container">
            <div class="popup-inner-container">
                <input class="group-name-input edit-group-name-input" placeholder="Enter name of group..." type="text"/>
                <div class="edit-group-actions">
                    <span class="invalid-error-message"></span>
                    <button class="cancel cancel-edit-group-button">Cancel</button>
                    <button class="edit edit-group-button">Edit</button>
                </div>
            </div>
        </div>

        <div class="delete-group-popup-container">
            <span class="confirm-message">Are you sure you want to delete this group?</span>
            <div class="delete-group-actions">
                <button class="cancel cancel-delete-group-button">Cancel</button>
                <button class="delete delete-group-button">Delete</button>
            </div>
        </div>
        
        <div class="add-contact-popup-container">
            <div class="popup-inner-container">
                <div class="full-name-container">
                    <input class="contact-input first-name-input" placeholder="Enter contact first name..." type="text"/>
                    <input class="contact-input last-name-input" placeholder="Enter contact last name..." type="text"/>
                </div>
                <input class="contact-input phone-number-input" placeholder="Enter contact phone number..." type="text"/>
                <input class="contact-input mobile-number-input" placeholder="Enter mobile number..." type="text"/>
                <input class="contact-input email-input" placeholder="Enter contact email..." type="text"/>
                <div class="contact-group-options-container">
                    <div class="contact-group-options-title">
                        <p>Choose to which groups your contact should be added.</p>
                    </div>
                    <div class="contact-group-options">
                    </div>
                </div>
            </div>
            <div class="add-contact-actions">
                <button class="cancel cancel-add-contact-button">Cancel</button>
                <button class="add add-contact-button">Add</button>
            </div>
        </div>

        <div class="edit-contact-popup-container">
            <div class="popup-inner-container">
                <div class="full-name-container">
                    <input class="contact-input first-name-input" placeholder="Enter contact first name..." type="text"/>
                    <input class="contact-input last-name-input" placeholder="Enter contact last name..." type="text"/>
                </div>
                <input class="contact-input phone-number-input" placeholder="Enter contact phone number..." type="text"/>
                <input class="contact-input mobile-number-input" placeholder="Enter mobile number..." type="text"/>
                <input class="contact-input email-input" placeholder="Enter contact email..." type="text"/>
                <div class="contact-group-options-container">
                    <div class="contact-group-options-title">
                        <p>Choose to which groups your contact should be added / removed.</p>
                    </div>
                    <div class="contact-group-options">
                    </div>
                </div>
            </div>
            <div class="edit-contact-actions">
                <button class="cancel cancel-edit-contact-button">Cancel</button>
                <button class="edit edit-contact-button">Edit</button>
            </div>
        </div>

        <div class="delete-contact-popup-container">
            <span class="confirm-message">Are you sure you want to delete this contact?</span>
            <div class="delete-contact-actions">
                <button class="cancel cancel-delete-contact-button">Cancel</button>
                <button class="delete delete-contact-button">Delete</button>
            </div>
        </div>

    </body>
</html>