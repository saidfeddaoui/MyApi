/**
 * Created by mobiblanc on 08/10/2018.
 */
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
            }
        ],
        "order": [
            [0, "asc"]
        ] // set first column as a default sort by asc
    });
    $('.edit').on('click', function () {
        console.log("entrer !!");
        var id = $(this).data('id');
        $.ajax({
            url: Routing.generate('modes_reparation_edit', {id: id}),
            type: "POST",
            error: function (request, status, error) {
                console.log(request.responseText);
            },
            complete: function () {
            },
            statusCode: {
                //traitement en cas de succès
                200: function (response) {
                    $("#edit-mode-modal .modal-body .form").html(response);
                    $("#edit-mode-modal").modal();
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
            title: "Voulez-vous vraiement supprimer ce mode de réparation ?",
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
            url: Routing.generate('modes_reparation_delete', {id: id}),
            type: "POST",
            error: function (request, status, error) {
                console.log(request.responseText);
            },
            complete: function () {
            },
            statusCode: {
                //traitement en cas de succès
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
                    $('.datatable').DataTable().row( td.parents('tr') ).remove().draw();
                }
            }
        });
    }
    $("body").on("click", ".SaveMode", function(event){
        event.preventDefault();
        var form = $(this).closest("form").serializeArray();
        $(this).closest("form").submit();
    });
});