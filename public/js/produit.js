jQuery(document).ready(function() {

    $('.edit_product').on('click', function () {
        var $this = $(this).data('id');

            $.ajax({
                url: Routing.generate('edit_produit', {id: $this}),
                type: "POST",
                error: function (request, status, error) {
                    console.log(request.responseText);
                },
                complete: function () {
                },
                statusCode: {
                    //traitement en cas de succès
                    200: function (response) {
                        $("#edit-product-modal .modal-body").html("");
                        $('#edit-product-modal').find('.modal-body').append(response);
                        $("#edit-product-modal").modal();
                        return false;
                    }
                }
            });
    });

    $('.remove').on('click', function () {
        var $this = $(this).data('id');
        var remove_cuerrent = $(this);
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
                DeleteAction();
            } else {
                swal("Action annulée", "Aucune action n'a été exécutée", "error");
            }
        });

        function DeleteAction() {
            $.ajax({
                url: Routing.generate('delete_produit', {id: $this}),
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