jQuery(document).ready(function() {
    $('.datatable').dataTable({
        "columns": [
            { // set default column settings
                "searchable": true,
                "orderable": true,
            },
            { // set default column settings
                "searchable": true,
                "orderable": true,
            },
            { // set default column settings
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
    $('.edit').on('click', function () {
        var id = $(this).data('id');
        $.ajax({
            url: Routing.generate('content_types_edit_emergency', {id: id}),
            type: "POST",
            error: function (request, status, error) {
                console.log(request.responseText);
            },
            complete: function () {
            },
            statusCode: {
                //traitement en cas de succès
                200: function (response) {
                    $("#edit-emergency-modal .modal-body .form").html(response);
                    $("#edit-emergency-modal").modal();
                    return false;
                }
            }
        });
    });
    $('.delete').on('click', function () {
        var td = $(this);
        var id = td.data('id');
        swal({
            title: "Voulez-vous vraiement supprimer ce contact d'urgence ?",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-success",
            confirmButtonText: "Confirmer!",
            cancelButtonText: "Annuler",
            closeOnConfirm: false,
            closeOnCancel: false
        }, function (isConfirm) {
            if (isConfirm) {
                deleteAction(id, td);
            } else {
                swal("Action annulée", "Aucune action n'a été exécutée", "error");
            }
        });
    });
    function deleteAction(id, td) {
        $.ajax({
            url: Routing.generate('content_types_delete_emergency', {id: id}),
            type: "POST",
            error: function (request, status, error) {
                console.log(request.responseText);
            },
            complete: function () {
            },
            statusCode: {
                //traitement en cas de succès
                200: function (response) {
                    /*******************************
                     Flash Notification
                     *******************************/
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
                    /*******************************
                     End Flash Notification
                     *******************************/
                    $('.datatable').DataTable().row( td.parents('tr') ).remove().draw();
                }
            }
        });
    }

    // $('#emergency_subTitle').on('keyup', function(){
    //     var value = $(this).val(),
    //         valueAfterRegix = value.replace(/[^\d\+]/g,"");
    //     $(this).val(valueAfterRegix);
    // });

});