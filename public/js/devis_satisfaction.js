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
                "searchable": false,
                "orderable": false,
            }
        ],
        "order": [
            [0, "desc"]
        ] // set first column as a default sort by asc
    });


    $("body").on('click','.devis_satisfaction_details', function () {
        var id = $(this).data('id');
        url= Routing.generate('devis_satisfaction_details', {id: id});
        window.open(url, '_blank');
    });


});