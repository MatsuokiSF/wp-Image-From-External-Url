jQuery(function($) {
    var isAdding = false;

    function clearFields() {
        $('#imagefromexternalurl-urls').val('');
        $('#imagefromexternalurl-hidden').hide();
        $('#imagefromexternalurl-error').text('');
        $('#imagefromexternalurl-width').val('');
        $('#imagefromexternalurl-height').val('');
        $('#imagefromexternalurl-mime-type').val('');
    }

    $('body').on('click', '#imagefromexternalurl-clear', function(e) {
        clearFields();
    });

    $('body').on('click', '#imagefromexternalurl-show', function(e) {
        $('#imagefromexternalurl-media-new-panel').show();
        e.preventDefault();
    });

    $('body').on('click', '#imagefromexternalurl-in-upload-ui #imagefromexternalurl-add', function(e) {
        if (isAdding) {
            return;
        }
        isAdding = true;
        $('#imagefromexternalurl-in-upload-ui #imagefromexternalurl-add').prop('disabled', true);

        var postData = {
            'urls': $('#imagefromexternalurl-urls').val(),
            'width': $('#imagefromexternalurl-width').val(),
            'height': $('#imagefromexternalurl-height').val(),
            'mime-type': $('#imagefromexternalurl-mime-type').val()
        };

        wp.media.post('add_external_media_without_import', postData)
            .done(function(response) {
                var frame = wp.media.frame || wp.media.library;
                if (frame) {
                    frame.content.mode('browse');
                    var library = frame.state().get('library') || frame.library;
                    response.attachments.forEach(function(elem) {
                        var attachment = wp.media.model.Attachment.create(elem);
                        attachment.fetch();
                        library.add(attachment ? [attachment] : []);
                        if (wp.media.frame._state !== 'library') {
                            var selection = frame.state().get('selection');
                            if (selection) {
                                selection.add(attachment);
                            }
                        }
                    });
                }

                if (response.error) {
                    $('#imagefromexternalurl-error').text(response.error);
                    $('#imagefromexternalurl-width').val(response.width);
                    $('#imagefromexternalurl-height').val(response.height);
                    $('#imagefromexternalurl-mime-type').val(response['mime-type']);
                    $('#imagefromexternalurl-hidden').show();
                } else {
                    clearFields();
                    $('#imagefromexternalurl-hidden').hide();
                }

                $('#imagefromexternalurl-urls').val(response.urls);
                $('#imagefromexternalurl-buttons-row .spinner').css('visibility', 'hidden');
                $('#imagefromexternalurl-in-upload-ui #imagefromexternalurl-add').prop('disabled', false);
                isAdding = false;
            })
            .fail(function() {
                $('#imagefromexternalurl-error').text('An unknown network error occurred');
                $('#imagefromexternalurl-buttons-row .spinner').css('visibility', 'hidden');
                $('#imagefromexternalurl-in-upload-ui #imagefromexternalurl-add').prop('disabled', false);
                isAdding = false;
            });

        e.preventDefault();
        $('#imagefromexternalurl-buttons-row .spinner').css('visibility', 'visible');
    });

    $('body').on('click', '#imagefromexternalurl-in-upload-ui #imagefromexternalurl-cancel', function(e) {
        clearFields();
        $('#imagefromexternalurl-media-new-panel').hide();
        $('#imagefromexternalurl-buttons-row .spinner').css('visibility', 'hidden');
        $('#imagefromexternalurl-in-upload-ui #imagefromexternalurl-add').prop('disabled', false);
        isAdding = false;
        e.preventDefault();
    });
});
