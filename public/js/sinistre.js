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
            {
                "searchable": false,
                "orderable": false,
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
            url: Routing.generate('edit_type_sinistre', {id: id}),
            type: "POST",
            error: function (request, status, error) {
                console.log(request.responseText);
            },
            complete: function () {
            },
            statusCode: {
                //traitement en cas de succès
                200: function (response) {
                    $("#edit-typeSinistre-modal .modal-body .form").html(response);
                    $("#edit-typeSinistre-modal").modal();
                    return false;
                }
            }
        });
    });
    $('.delete').on('click', function () {
        var td = $(this);
        var id = td.data('id');
        swal({
            title: "Voulez-vous vraiement supprimer ce type ?",
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
            url: Routing.generate('delete_type_sinistre', {id: id}),
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

    $('.edit_photo').on('click', function () {
        var $this = $(this).data('id');
        $.ajax({
            url: Routing.generate('edit_photo_sinistre', {id: $this}),
            type: "POST",
            error: function (request, status, error) {
                console.log(request.responseText);
            },
            complete: function () {
            },
            statusCode: {
                //traitement en cas de succès
                200: function (response) {
                    $("#edit-photoSinistre-modal .modal-body").html("");
                    $('#edit-photoSinistre-modal').find('.modal-body').append(response);
                    $('.select2').select2();
                    $("#edit-photoSinistre-modal").modal();
                    return false;
                }
            }
        });
    });
    $('.remove_photo').on('click', function () {
        var $this = $(this).data('id');
        var remove_cuerrent = $(this);
        swal({
            title: "Voulez-vous vraiement supprimer cet photo ?",
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
                DeleteAction();
            } else {
                swal("Action annulée", "Aucune action n'a été exécutée", "error");
            }
        });

        function DeleteAction() {
            $.ajax({
                url: Routing.generate('delete_photo_sinistre', {id: $this}),
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
                         Flash Notificaiton
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
                         End Flash Notificaiton
                         *******************************/
                        $('.datatable').DataTable().row( remove_cuerrent.parents('tr') ).remove().draw();
                    }
                }
            });
        }

    });
});