var MamdaAppContext = {
    initDataTable: function () {
        $.extend(true, $.fn.dataTable.defaults, {
            "lengthMenu": [
                [5, 15, 20, -1],
                [5, 15, 20, "All"] // change per page values here
            ],
            // set the initial value
            "pageLength": 5,
            "language": {
                "lengthMenu": " _MENU_ records"
            },
            "aaSorting": [[0, "desc"]],
            "bDestroy": true,
            "oLanguage": {
                "sSearch": "",
                "sUrl": "http://cdn.datatables.net/plug-ins/1.10.7/i18n/French.json"
            }
        })
    },
    initSelect2: function () {
        $('.select2').select2();
    },
    date_picker: function (){

        $('.datetimepicker').datetimepicker({
            locale:'fr',
            // format: 'd-m-Y H:i',
            // step:30,
            // dayOfWeekStart: 1,
            // closeOnDateSelect:true
        });

        $.datetimepicker.setLocale('fr');
        $(document).on("focus", ".datetimepicker", function(){
            $(this).datetimepicker({
                locale:'fr',
                format: 'd-m-Y H:i',
                closeOnDateSelect:true
            });
        });
    },
    initSideBar: function () {
        var $current_url = window.location.pathname;
        $('.page-sidebar-menu .nav-link').each(function(){
            if($current_url == $(this).attr('href')) {
                $(this).parent().addClass('active open');
                if($(this).closest('.sub-menu').length > 0) {
                    $(this).closest('.page-sidebar-menu > .nav-item').addClass('active open');
//                    if($(this).closest('.sub-menu').closest('.sub-menu').length > 0){
//                        $(this).closest('.page-sidebar-menu > .nav-item').addClass('active open');
//                    }
                }
                return false;
            }
        });
    },
    initApp: function () {
        this.initDataTable();
        this.initSelect2();
        this.initSideBar();
        this.date_picker();
    }
}
jQuery(document).ready(function() {
    MamdaAppContext.initApp();
});