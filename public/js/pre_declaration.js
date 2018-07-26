jQuery(document).ready(function() {
    var ImageCrop = {
        init: function(photoPaneId, previewPaneId) {
            // Create variables (in this scope) to hold the API and image size
            var jcrop_api,
                boundx,
                boundy,
                // Grab some information about the preview pane
                $preview = $(previewPaneId),
                $pcnt = $(previewPaneId + ' .preview-container'),
                $pimg = $(previewPaneId + ' .preview-container img'),

                xsize = $pcnt.width(),
                ysize = $pcnt.height();

            console.log('init', [xsize, ysize]);

            $(photoPaneId).Jcrop({
                onChange: updatePreview,
                onSelect: updatePreview,
                aspectRatio: xsize / ysize,
            }, function() {
                // Use the API to get the real image size
                var bounds = this.getBounds();
                boundx = bounds[0];
                boundy = bounds[1];
                // Store the API in the jcrop_api variable
                jcrop_api = this;
                // Move the preview into the jcrop container for css positioning
                $preview.appendTo(jcrop_api.ui.holder);
            });

            function updatePreview(c) {
                if (parseInt(c.w) > 0) {
                    var rx = xsize / c.w;
                    var ry = ysize / c.h;
                    $pimg.css({
                        width: Math.round(rx * boundx) + 'px',
                        height: Math.round(ry * boundy) + 'px',
                        marginLeft: '-' + Math.round(rx * c.x) + 'px',
                        marginTop: '-' + Math.round(ry * c.y) + 'px'
                    });
                }
            };
        }
    }
    $('.datatable').dataTable({
        "columns": [
            { // set default column settings
                "searchable": true,
                "orderable": true,
            },
            {
                "searchable": true,
                "orderable": true,
            },
            {
                "searchable": true,
                "orderable": true,
            },
            {
                "searchable": true,
                "orderable": true,
            },
            {
                "searchable": false,
                "orderable": false,
            }
        ],
        "order": [
            [0, "asc"]
        ] // set first column as a default sort by asc
    });
    $('.details').on('click', function () {
        var id = $(this).data('id');
        $.ajax({
            url: Routing.generate('pre_declarations_details', {id: id}),
            type: "POST",
            error: function (request, status, error) {
                console.log(request.responseText);
            },
            complete: function () {
            },
            statusCode: {
                //traitement en cas de succès
                200: function (response) {
                    $("#details-pre-declaration-modal .modal-body .pre-declaration-details").html(response);
                    $("#details-pre-declaration-modal").modal();
                    $('[id*="photo-attachment"]').each(function() {
                        var id = $(this).attr('id');
                        ImageCrop.init('#' + id, '.preview-' + id);
                    });
                    return false;
                }
            }
        });
    });
    $('body').on('click', '.reject', function () {
        var td = $(this);
        var id = td.data('id');
        swal({
            title: 'Voulez-vous vraiement rejeter cette pré-déclaration ?',
            text: '',
            type: 'input',
            showCancelButton: true,
            confirmButtonClass: 'btn-success',
            confirmButtonText: 'Confirmer!',
            cancelButtonText: 'Annuler',
            closeOnConfirm: false,
            closeOnCancel: false
        }, function (inputValue) {
            if (inputValue) {
                deleteRowAction(id, td, 'pre_declarations_reject', inputValue);
            } else {
                swal('Action annulée', "Aucune action n'a été exécutée", 'error');
            }
        });
    });
    $('body').on('click', '.accept', function () {
        var td = $(this);
        var id = td.data('id');
        swal({
            title: 'Voulez-vous vraiement accepter cette pré-déclaration ?',
            text: '',
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'btn-success',
            confirmButtonText: 'Confirmer!',
            cancelButtonText: 'Annuler',
            closeOnConfirm: false,
            closeOnCancel: false
        }, function (confirm) {
            if (confirm) {
                deleteRowAction(id, td, 'pre_declarations_accept', '');
            } else {
                swal('Action annulée', "Aucune action n'a été exécutée", 'error');
            }
        });
    });
    function deleteRowAction(id, td, route, description) {
        $.ajax({
            url: Routing.generate(route, {id: id}),
            data: {description: description},
            type: 'POST',
            error: function (request, status, error) {
                console.log(request.responseText);
            },
            complete: function () {
            },
            statusCode: {
                200: function (response) {
                    swal.close();
                    setTimeout(function(){
                        swal({
                            title: "",
                            text: response.message,
                            timer: 3000,
                            showConfirmButton: false,
                            customClass: 'custom-swal',
                        });
                    }, 500);
                    var $row = $('tr[data-id="' + id + '"]');
                    $('.datatable').DataTable().row($row).remove().draw();
                    $("#details-pre-declaration-modal").hide();
                },
                400: function (response) {
                    swal.close();
                    setTimeout(function() {
                        toastr.error(response.responseJSON.message);
                    }, 500);
                }
            }
        });
    }
});