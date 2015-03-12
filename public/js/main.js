$(document).ready(function() {
    // Initialize event handlers

    // Overwrite name change form submission
    $('#NameChangeForm').submit(function(event) {
        event.preventDefault();

        $('SaveNewName').val('<i class="fa fa-circle-o-notch fa-spin"></i>');
        $('SaveNewName').prop('disabled', true);

        $.ajax({
            url         : '/felhasznalo/uj-nev',
            type        :'POST',
            data        : {
                _token  : $('[name=_token]').val(),
                NewName : $('#NewName').val()
            },
            dataType    : 'json',
            error       : function() {
                $('#NameChangeError').html('A szerver nem elérhető');
            },
            success     : function(data) {
                if (!data.success) {
                    $('#NameChangeError').html(data.message);
                } else {
                    $('#NameChangeError').hide();
                    $('#UserFullName').html(data.newName);
                    $('#NameChange').modal('hide');
                }
            },
            complete    : function() {
                $('SaveNewName').val('Mentés');
                $('SaveNewName').prop('disabled', false);
            }
        });
    });

    // Name change button
    $('#SaveNewName').click(function() {
        $('#NameChangeForm').submit();
    });

    // Prompt
    $(function() {
        $('.confirm').click(function() {
            return window.confirm("Biztos vagy benne?");
        });
    });
});