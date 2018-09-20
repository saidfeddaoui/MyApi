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
                "searchable": false,
                "orderable": false,
            }
        ],
        "order": [
            [0, "desc"]
        ] // set first column as a default sort by asc
    });
});