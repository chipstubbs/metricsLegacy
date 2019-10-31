var productionTable = $('#production').DataTable({
    "search": {
        "smart": true
    },
    "order": [
        [8, "desc"]
    ],
    "lengthChange": true,
    scrollX: true,
    scrollCollapse: true,
    scroller: true,
    fixedColumns: true,
    "iDisplayLength": 10,
    responsive: false,
    "language": {
        "search": "",
        "paginate": {
            "previous": "<i class='fa fa-arrow-left'></i>",
            "next": "<i class='fa fa-arrow-right'></i>"
        }
    }
});

var eventtTable = $('#eventOverview').DataTable({
    "search": {
        "smart": true
    },
    "order": [
        [1, "desc"]
    ],
    "lengthChange": true,
    "iDisplayLength": 10,
    scrollX: true,
    scrollCollapse: true,
    // fixedColumns: true,
    responsive: false,
    "language": {
        "search": "",
        "paginate": {
            "previous": "<i class='fa fa-arrow-left'></i>",
            "next": "<i class='fa fa-arrow-right'></i>"
        }
    }
});

var cA = $('#CompareAverages').DataTable({
    "search": {
        "smart": true
    },
    "order": [],
    scrollX: true,
    scrollCollapse: true,
    // fixedColumns: true,
    "lengthChange": false,
    "iDisplayLength": 10,
    // responsive: true,
    "language": {
        "search": "",
        "paginate": {
            "previous": "<i class='fa fa-arrow-left'></i>",
            "next": "<i class='fa fa-arrow-right'></i>"
        }
    }
});

// var cA = $('#CompareAverages').DataTable();


new $.fn.dataTable.FixedColumns(productionTable, {
    left: true
});