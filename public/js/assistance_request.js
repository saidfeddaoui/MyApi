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
    $('.handle').on('click', function () {
        var td = $(this);
        var id = td.data('id');
        swal({
            title: "Voulez-vous vraiement marquer cette demande comme traitée ?",
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
                deleteRowAction(id, td);
            } else {
                swal("Action annulée", "Aucune action n'a été exécutée", "error");
            }
        });
    });
    function deleteRowAction(id, td) {
        $.ajax({
            url: Routing.generate('assistance_handle', {id: id}),
            type: 'POST',
            error: function (request, status, error) {
                console.log(request.responseText);
            },
            complete: function () {},
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

    $(document).on('click', '.latlon' ,function(){
        $id = $(this).attr("data-id");
        $('body').attr('data-id', $id);
    });

    function showMarker(){
        $id = $('body').attr("data-id");
        $(this).closest("tr").children("td:eq(1)").attr('data-firma');
        var latLng = new google.maps.LatLng($(".latlon[data-id='"+$id+"']").attr("data-lat"), $(".latlon[data-id='"+$id+"']").attr("data-lon"));
        myMarker = new google.maps.Marker({
            position: latLng,
            draggable: false,
            map: map
        });
        google.maps.event.addListener(myMarker, 'drag', function() {
            updateMarkerPosition(myMarker.getPosition());
        });
    }
    $('#map-position').on('shown.bs.modal', function () {
        initMap();
        showMarker();
    });

});