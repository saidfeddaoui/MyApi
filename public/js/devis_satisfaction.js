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
            { // set default column settings
                "searchable": true,
                "orderable": true,
            },
            { // set default column settings
                "searchable": true,
                "orderable": true,
            },
            { // set default column settings
                "searchable": false,
                "orderable": false,
            }
        ],
        "order": [
            [9, "desc"]
        ] // set first column as a default sort by asc
    });


    $("body").on('click','.devis_satisfaction_details', function () {
        var id = $(this).data('id');
        url= Routing.generate('devis_satisfaction_details', {id: id});
        window.open(url, '_blank');
    });

    $("body").on('click','.actionDevis', function () {
        var id = $('#idDevis').val();
        var action=$(this).data('val');
        var observation=$('.observation').val();

        if(observation===""){
            observation="NULL";
        }

        $.ajax({
            url: Routing.generate('devis_satisfaction_update_action', {id: id,action: action,observation: observation}),
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
                }
            }
        });
    });


});