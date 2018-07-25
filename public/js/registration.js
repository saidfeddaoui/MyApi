$(document).ready(function() {
    $('.datatable').dataTable({
        "columns": [
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
    $('.remove').on('click', function () {
        var $this = $(this).data('id');
        var remove_cuerrent = $(this);
        swal({
            title: "Voulez-vous vraiement supprimer cet utilisateur ?",
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
                RetourActionLeft();
            } else {
                swal("Action annulée", "Aucune action n'a été exécutée", "error");
            }
        });

        function RetourActionLeft() {
            $.ajax({
                url: Routing.generate('administration_delete_user', {id: $this}),
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
                                text: "Utilisateur supprimé avec succès",
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