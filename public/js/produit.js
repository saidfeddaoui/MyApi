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
            url: Routing.generate('edit_produit', {id: id}),
            type: "POST",
            error: function (request, status, error) {
                console.log(request.responseText);
            },
            complete: function () {
            },
            statusCode: {
                //traitement en cas de succès
                200: function (response) {
                    $("#edit-product-modal .modal-body .form").html(response);
                    $("#edit-product-modal").modal();
                    return false;
                }
            }
        });
    });
    $('.delete').on('click', function () {
        var remove_cuerrent = $(this);
        var td = $(this);
        var id = td.data('id');
        swal({
            title: "Voulez-vous vraiement supprimer ce produit ?",
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
            url: Routing.generate('delete_produit', {id: id}),
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
});