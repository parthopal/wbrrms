$(document).ready(function () {
    $('#district_id').on('change', function (e) {
        e.preventDefault();
        get_block_list();
    });
    $('#block_id').on('change', function (e) {
        e.preventDefault();
        get_gp_list();
    });
    $('#search').on('click', function (e) {
        e.preventDefault();
        get_approved_list();
    });
});
function get_block_list() {
    $.ajax({
        url: baseURL + '/sfurgent/get_block_list',
        type: 'get',
        data: { district_id: $('#district_id').val() },
        dataType: 'json',
        async: false
    }).done(function (data) {
        $('#block_id').empty();
        if (data.length > 0) {
            $('#block_id').append(
                $('<option>', { value: '0', text: '--All Block--' })
            );
            $.each(data, function (i, item) {
                $('#block_id').append(
                    $('<option>', { value: item.id, text: item.name })
                );
            });
        } else if ($('#district_id').val() === 0) {
            $('#block_id').append(
                $('<option>', { value: '0', text: '--All Block--' })
            );
        } else {
            $('#block_id').append(
                $('<option>', { value: '', text: '--Select Block--' })
            );
        }
    });
}

function get_gp_list() {
    console.log('block_id: ' + $('#block_id').val());
    $.ajax({
        url: baseURL + '/sfurgent/get_gp_list',
        type: 'get',
        data: { block_id: $('#block_id').val() },
        dataType: 'json',
        async: false
    }).done(function (data) {
        $('#gp_id').empty();
        if (data.length > 0) {
            $('#gp_id').append($('<option>', { value: '', text: '--Select GP--' }));
            $.each(data, function (i, item) {
                $('#gp_id').append($('<option>', { value: item.id, text: item.name }));
            });
        }
    });
}

function get_approved_list() {
    if ($('#district_id').val() > 0) {
        $.ajax({
            url: baseURL + '/sfurgent/get_approved_list',
            type: 'get',
            data: { district_id: $('#district_id').val() },
            dataType: 'json',
            async: false
        }).done(function (data) {
            // console.log(data);
            _load_approved_list(data);
        });
    }
}

function _load_approved_list(data) {
    var i = 1;
    $('#tbl').dataTable().fnDestroy();
    var currentdate = new Date();
    $('#tbl').DataTable({
        data: data,
        dom: 'lBfrtip',
        processing: true,
        scrollY: '450px',
        scrollX: true,
        scrollCollapse: true,
        paging: false,
        responsive: true,
        stateSave: true,
        colReorder: true,
        fixedColumns: {
            left: 2,
            right: 1
        },
        buttons: [
            {
                extend: 'excel',
                text: 'Excel',
                filename: 'sfu_master_' + $.now(),
                title: 'State Fund Urgent MASTER ON ' + String(currentdate.getDate()).padStart(2, '0') + '/' + String((currentdate.getMonth() + 1)).padStart(2, '0') + '/'
                    + currentdate.getFullYear() + ' ' + String(currentdate.getHours()).padStart(2, '0') + ':' + String(currentdate.getMinutes()).padStart(2, '0'),
                footer: true,
                exportOptions: {
                    columns: ':not(.not-export)'
                },
                customize: function (xlsx) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    $('row c', sheet).attr('s', '25');
                }
            },
            {
                extend: 'print',
                text: 'Print',
                title: 'State Fund Urgent MASTER',
                footer: true,
                exportOptions: {
                    columns: ':not(.not-export)'
                },
                customize: function (win) {
                    $(win.document.body)
                        .find('h1').css('text-align', 'center')
                        .css('font-size', '10pt')
                        .prepend(
                            '<img src="' + baseURL + '/templates/img/pathashree.jpg" style="position:absolute; top:0; left:0;" />'
                        );
                    $(win.document.body).find('table')
                        .addClass('compact')
                        .css('font-size', 'inherit')
                        .css('margin', '50px auto');
                }
            }
        ],
        columnDefs: [
            {
                targets: 0, defaultContent: '',
                render: function (data, type, full, meta) {
                    return i++;
                }
            },
            {
                targets: 1, defaultContent: '',
                render: function (data, type, full, meta) {
                    return ('<p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' + full.name + '">' + full.name + '</p>');
                }
            },
            { targets: 2, data: 'district' },

            { targets: 3, data: 'block' },
            {
                targets: 4, defaultContent: '',
                render: function (data, type, full, meta) {
                    return full.gp;
                }
            },
            { targets: 5, data: 'ref_no' },
            { targets: 6, data: 'agency' },
            { targets: 7, data: 'approved_length' },
            { targets: 8, data: 'work_type' },
            { targets: 9, data: 'road_type' },
            { targets: 10, data: 'cost' },
            {
                targets: 11,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    // delete button (only for role_id < 3)
                    var remove = role_id < 3
                        ? `<button data-toggle="tooltip" data-placement="bottom" 
                        title="Delete" 
                        class="btn btn-danger btn-sm d-flex align-items-center justify-content-center mx-1 rounded-circle" 
                        style="width:32px; height:32px;" 
                        onclick="_remove(${full.id})">
                        <i class="fas fa-trash"></i>
                        </button>` : '';

                    // document button (only if document exists)
                    var document = full.approved_doc && full.approved_doc.length > 0
                        ? `<button data-toggle="tooltip" data-placement="bottom" 
                        title="View Document" 
                        class="btn btn-primary btn-sm d-flex align-items-center justify-content-center mx-1 rounded-circle" 
                        style="width:32px; height:32px;" 
                        onclick="_document('${baseURL}/${full.approved_doc}')">
                        <i class="fas fa-file-pdf"></i>
                        </button>`
                        : '';

                    // edit button (always visible)
                    var edit = !(role_id > 3 && full.approved_doc && full.approved_doc.length > 0)
                        ? `<button data-toggle="tooltip" data-placement="bottom" 
                            title="Edit" 
                            class="btn btn-warning btn-sm d-flex align-items-center justify-content-center mx-1 rounded-circle" 
                            style="width:32px; height:32px;" 
                            onclick="_edit(${full.id})">
                            <i class="fas fa-pen"></i>
                        </button>`
                        : '';

                    return `
                        <div class="d-flex justify-content-start">
                            ${document}
                            ${edit}
                            ${remove}
                        </div> `;
                }
            }




        ]
    });
}
function _edit(id) {
    window.location.href = baseURL + "/sfurgent/entry/" + id;
}

function _document(url) {
    window.open(url, "_blank");
}
function _remove(id) {
    var r = confirm("Do you want to delete this?");
    if (r === true) {
        $.ajax({
            url: baseURL + "/sfurgent/remove",
            type: "post",
            data: { id: id },
            dataType: "json",
            async: false,
        });
        alert("Deleted");
        location.reload();
    }
}