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
                "searchable": false,
                "orderable": false,
            }
        ],
        "order": [
            [0, "asc"]
        ] // set first column as a default sort by asc
    });

    $("body").on('click','.detail_devis_mrh', function () {
        var id = $(this).data('id');
        url= Routing.generate('devis_habitation_details', {id: id});
        window.open(url, '_blank');
    });
});