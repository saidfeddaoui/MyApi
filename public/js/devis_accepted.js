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
            }
        ],
        "order": [
            [0, "asc"]
        ] // set first column as a default sort by asc
    });


    $("body").on('click','.detail_devis', function () {
        var id = $(this).data('id');
        url= Routing.generate('devis_auto_details', {id: id});

        console.log(url);
        window.open(url, '_blank');
    });
});