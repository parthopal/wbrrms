
/* To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {
    if ($('#approved').is(':checked')) {
        $('.isapproved').show();
        $('.lblreqd').text('*');
        $('.reqd').attr('required', 'required');
        $('#approved').val(1);
        $('#isapproved').val(1);
    } else {
        $('.isapproved').hide();
        $('.lblreqd').text('');
        $('.reqd').removeAttr('required');
        $('#approved').val(0);
        $('#isapproved').val(0);
    }
    $('#district_id').on('change', function (e) {
        e.preventDefault();
        get_block_list();
        get_assembly_list();
    });
    $('#search').on('click', function (e) {
        e.preventDefault();
        get_survey_list();
    });
    $('#ta_search').on('click', function (e) {
        e.preventDefault();
        get_tender_list();
    });
    $('#search_qm').on('click', function (e) {
        e.preventDefault();
        get_rpt_qm_list();
    });
    $('#search_survey').on('click', function (e) {
        e.preventDefault();
        get_survey_pending_list();
    });
    $('#chkall').change(function () {
        $('input:checkbox:enabled').prop('checked', $(this).prop('checked'));
    });
    $('.chk').click(function () {
        if ($('.chk:checked').length === $('.chk').length) {
            $('#chkall').prop('checked', true);
        } else {
            $('#chkall').prop('checked', false);
        }
    });
    $('#survey').on('submit', function (e) {
        e.preventDefault();
        var arr = [];
        $('.chk').each(function () {
            var $this = $(this);
            if ($this.is(':checked')) {
                arr.push($this.attr('id').replace('chk_', ''));
            }
        });
        if (arr.length > 0) {
            var formData = new FormData(this);
            $.ajax({
                url: baseURL + '/capex/create_lot_no',
                type: 'post',
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,
                async: false
            }).done(function (data) {
                alert('Your lot no is ' + data);
                get_survey_pending_list();
            });
        }
    });
    $('#search_lotno').on('click', function (e) {
        e.preventDefault();
        get_lot_list();
    });
    $('#lot').on('submit', function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: baseURL + '/capex/forwarded',
            type: 'post',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            async: false
        }).done(function (data) {
            alert('Successfully forwarded');
            window.location.href = baseURL + '/capex/lot';
        });
    });
    $('#search_approval').on('click', function (e) {
        e.preventDefault();
        get_approval_list();
    });

    $('#search_not_imp_list').on('click', function (e) {
        e.preventDefault();
        get_not_imp_list();
    });
    $('#approval').on('submit', function (e) {
        e.preventDefault();
        var arr = [];
        $('.chk').each(function () {
            var $this = $(this);
            if ($this.is(':checked')) {
                arr.push($this.attr('id').replace('chk_', ''));
            }
        });
        if (arr.length > 0) {
            var formData = new FormData(this);
            $.ajax({
                url: baseURL + '/capex/create_lot_no',
                type: 'post',
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,
                async: false
            }).done(function (data) {
                alert('Your lot no is ' + data);
                get_approval_list();
            });
        } else {
            alert('Please choose scheme.');
        }
    });

    $('#admin').on('submit', function (e) {
        e.preventDefault();
        if ($('#lotno').val().length > 0) {
            var formData = new FormData(this);
            $.ajax({
                url: baseURL + '/capex/admin_approval',
                type: 'post',
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,
                async: false
            }).done(function (data) {
                alert('saved successfully');
                window.location.href = baseURL + '/capex/lot';
            });
        } else {
            alert('Please choose lot no.');
        }
    });
    $('#backward').on('click', function (e) {
        e.preventDefault();
        var msg = prompt('Do you want to return the selected scheme(s) to previous level?', '');
        if (msg.length > 0) {
            var arr = [];
            $('.chk').each(function () {
                var $this = $(this);
                if ($this.is(':checked')) {
                    arr.push($this.attr('id').replace('chk_', ''));
                }
            });
            if (arr.length > 0) {
                $.ajax({
                    url: baseURL + '/capex/return_to_prev',
                    type: 'post',
                    data: { district_id: $('#district_id').val(), block_id: $('#block_id').val(), arr: arr, msg: msg },
                    dataType: 'json',
                    async: false
                }).done(function (data) {
                    get_approval_list();
                });
            } else {
                alert('Please choose scheme.');
            }
        }
    });
    $('#type_id').on('change', function (e) {
        e.preventDefault();
        let typeId = $('#type_id').val();
        $('.nd').prop('required', false);
        if (typeId === '1') {
            $('.rd').show();
            $('.nd').hide();
        } else if (typeId > 0) {
            $('.rd').hide();
            $('.nd').show();
            $('.nd').prop('required', true);
        } else {
            $('.rd').hide();
            $('.nd').hide();
        }
    });

    $("#search_bridge").on("click", function (e) {
        e.preventDefault();  // this would stop form submit
    });

    // $('#search_bridge').on('click', function (e) {
    //     if ($("#district_id").val() == 0) {
    //         alert('Select District')
    //     }
    //     e.preventDefault();
    //     get_bridge_list();
    // });


});

function get_block_list() {
    $.ajax({
        url: baseURL + '/capex/get_block_list',
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
        }
    });
}

function get_assembly_list() {

    $.ajax({
        url: baseURL + '/capex/get_assembly_list',
        type: 'get',
        data: { district_id: $('#district_id').val() },
        dataType: 'json',
        async: false
    }).done(function (data) {
        $('#ac_id').empty();
        if (data.length > 0) {
            $('#ac_id').append(
                $('<option>', { value: '0', text: '--All Assembly--' })
            );
            $.each(data, function (i, item) {
                $('#ac_id').append(
                    $('<option>', { value: item.id, text: item.name })
                );
            });
        }
    });
}
function get_survey_list() {
    if ($('#district_id').val() > 0) {
        $.ajax({
            url: baseURL + '/capex/get_survey_list',
            type: 'get',
            data: { district_id: $('#district_id').val(), status: $('input[name="status"]:checked').val() },
            dataType: 'json',
            async: false
        }).done(function (data) {
            _load_survey_list(data);
        });
    }
}
function _load_survey_list(data) {
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
            right: 2
        },
        buttons: [
            {
                extend: 'excel',
                text: 'Excel',
                filename: 'capex_master_' + $.now(),
                title: 'CAPEX MASTER ON ' + String(currentdate.getDate()).padStart(2, '0') + '/' + String((currentdate.getMonth() + 1)).padStart(2, '0') + '/'
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
                title: 'CAPEX MASTER',
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
            { targets: 2, data: 'ac' },
            { targets: 3, data: 'district' },

            { targets: 4, data: 'block' },
            {
                targets: 5, defaultContent: '',
                render: function (data, type, full, meta) {
                    return full.gp;
                }
            },
            { targets: 6, data: 'ref_no' },
            { targets: 7, data: 'agency' },
            { targets: 8, data: 'work_type' },
            { targets: 9, data: 'road_type' },
            {
                targets: 10, defaultContent: '',
                render: function (data, type, full, meta) {
                    var status = full.isactive === '-1' ? '<span class="badge btn-danger">Not Implemented</span>' : '';
                    if (full.isactive === '1') {
                        status = full.survey_status === '6' ? '<span class="badge btn-success">Approved</span>'
                            : full.survey_status === '5' ? '<span class="badge btn-info">State Admin Level </span>'
                                : full.survey_status === '4' ? '<span class="badge btn-info">SE Level </span>'
                                    : full.survey_status === '3' ? '<span class="badge btn-info">DM Level </span>'
                                        : full.survey_status === '2' ? '<span class="badge btn-info">Survey Completed</span>'
                                            : full.survey_status === '1' ? '<span class="badge btn-info">Ongoing Survey</span>'
                                                : full.survey_status === '0' ? '<span class="badge btn-warning">Survey Not Started</span>' : '';
                    }
                    return status;
                }
            },
            {
                targets: 11, defaultContent: '',
                render: function (data, type, full, meta) {
                    if ($('input[name="status"]:checked').val() > -1) {
                        var not_implemented = role_id < 3 ? '&nbsp;<button title="Mark scheme as not implemented" class="btn btn-icon btn-round btn-sm btn-danger" onclick="mark_not_traceable(' + full.id + ')"><i class="fas fa-minus-circle"></i></button>' : '';
                        var sent_to_inbox = role_id < 3 && full.survey_status == 0 && full.survey_lot_no !== null && full.survey_lot_no.length > 0 > 0 && full.dm_lot_no === null && full.se_lot_no === null ? '&nbsp;<button title="back to scheme inbox" class="btn btn-icon btn-round btn-sm btn-warning" onclick="back_to_inbox(' + full.id + ')"><i class="fa fa-undo"></i></button>' : '';
                        return '<button class="btn btn-icon btn-round btn-sm btn-primary" onclick="add(' + full.id + ')"  title="Edit"><i class="fas fa-pen pointer"></i></button>' + not_implemented + sent_to_inbox;
                    } else {
                        return ('<p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' + full.remarks + '">' + full.remarks + '</p>');
                    }
                }
            }
        ]
    });
}



// ############## TENDER ###################

$('#search_tender').on('click', function (e) {
    e.preventDefault();
    get_tender_list();
});

function tender_entry(id) {
    // alert(id);
    window.location.href = baseURL + '/capex/tender_entry/' + id;
}

function get_tender_list() {
    if ($('#district_id').val() > 0) {
        $.ajax({
            url: baseURL + '/capex/get_tender_list',
            type: 'get',
            data: {
                district_id: $('#district_id').val(),
                category_id: $('#category_id').val(),
                type_id: $('#type_id').val()
            },
            dataType: 'json',
            async: false,
        }).done(function (data) {
            _load_tender_list(data);
        });
    }
}
function _load_tender_list(data) {
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
            left: 3,
            right: 1
        },
        buttons: [
            {
                extend: 'excel',
                text: 'Excel',
                filename: 'tender_list_' + $.now(),
                title: 'TENDER LIST ON ' + String(currentdate.getDate()).padStart(2, '0') + '/' + String((currentdate.getMonth() + 1)).padStart(2, '0') + '/'
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
                title: 'TENDER LIST',
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
                    return (
                        '<input type="checkbox" name="chk[' +
                        full.id +
                        ']" id="chk_' +
                        full.id +
                        '" class="chk" value="">'
                    );
                }
            },
            { targets: 1, data: 'district' },
            { targets: 2, data: 'ac' },
            { targets: 3, data: 'block' },
            { targets: 4, data: 'scheme_id' },
            {
                targets: 5, defaultContent: '',
                render: function (data, type, full, meta) {
                    return (
                        '<p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' +
                        full.name +
                        '">' +
                        full.name +
                        '</p>'
                    );
                }
            },
            { targets: 6, data: 'length' },
            { targets: 7, data: 'agency' },
            { targets: 8, data: 'sanctioned_cost' },
            { targets: 9, data: 'tender_number' },
            { targets: 10, data: 'tender_publication_date' },
            {
                targets: 11, defaultContent: '',
                render: function (data, type, full, meta) {
                    return full.tender_status === '0'
                        ? 'Not Started'
                        : full.tender_status === '1'
                            ? 'On Progress'
                            : full.tender_status === '2'
                                ? 'Completed'
                                : 'Retendering';
                }
            },
            { targets: 12, data: 'bid_closing_date' },
            { targets: 13, data: 'bid_opeaning_date' },
            {
                targets: 14, defaultContent: '',
                render: function (data, type, full, meta) {
                    return full.evaluation_status === null
                        ? ''
                        : full.evaluation_status === '0'
                            ? 'No'
                            : 'Yes';
                },
            },
            {
                targets: 15, defaultContent: '',
                render: function (data, type, full, meta) {
                    return full.bid_opening_status === null
                        ? ''
                        : full.bid_opening_status === '0'
                            ? 'No'
                            : 'Yes';
                }
            },
            {
                targets: 16, defaultContent: '',
                render: function (data, type, full, meta) {
                    return full.bid_matured_status === null
                        ? ''
                        : full.bid_matured_status === '0'
                            ? 'No'
                            : 'Yes';
                }
            },
            {
                targets: 17, defaultContent: '',
                render: function (data, type, full, meta) {
                    return (
                        '<p style="margin:0px; width: 80px"><button class="btn btn-icon btn-round btn-sm btn-primary" onclick="_edit_tender(' +
                        full.id + ',' + full.tender_status +
                        ')"  title="Edit"><i class="fas fa-pen pointer"></i></button></p>'
                    );
                }
            }
        ]
    });
}


// ##############  WORK ORDER   #####################################
$("#search_wo").on("click", function (e) {
    // console.log("hiiiii");
    e.preventDefault();
    get_wo_list();
});

function get_wo_list() {
    $.ajax({
        url: baseURL + "/capex/get_wo_list",
        type: "get",
        data: {
            district_id: $("#district_id").val(),
            category_id: $("#category_id").val(),
            type_id: $("#type_id").val(),
        },
        dataType: "json",
        async: false,
    }).done(function (data) {
        // console.log(data);
        _load_wo_list(data);
    });
}

function _load_wo_list(data) {
    // console.log(data);
    var i = 1;
    $("#tbl").dataTable().fnDestroy();
    var currentdate = new Date();

    $("#tbl").DataTable({
        data: data,
        dom: "lBfrtip",
        processing: true,
        scrollY: "450px",
        scrollX: true,
        scrollCollapse: true,
        paging: false,
        responsive: true,
        stateSave: true,
        colReorder: true,
        fixedColumns: {
            left: 3,
            right: 1,
        },
        buttons: [
            {
                extend: "excel",
                text: "Excel",
                filename: "wo_list_" + $.now(),
                title:
                    "WORK ORDER LIST ON " +
                    String(currentdate.getDate()).padStart(2, "0") +
                    "/" +
                    String(currentdate.getMonth() + 1).padStart(2, "0") +
                    "/" +
                    currentdate.getFullYear() +
                    " " +
                    String(currentdate.getHours()).padStart(2, "0") +
                    ":" +
                    String(currentdate.getMinutes()).padStart(2, "0"),
                footer: true,
                exportOptions: {
                    columns: ":not(.not-export)",
                },
                customize: function (xlsx) {
                    var sheet = xlsx.xl.worksheets["sheet1.xml"];
                    $("row c", sheet).attr("s", "25");
                },
            },
            {
                extend: "print",
                text: "Print",
                title: "WORK ORDER LIST",
                footer: true,
                exportOptions: {
                    columns: ":not(.not-export)",
                },
                customize: function (win) {
                    $(win.document.body)
                        .find("h1")
                        .css("text-align", "center")
                        .css("font-size", "10pt")
                        .prepend(
                            '<img src="' +
                            baseURL +
                            '/templates/img/pathashree.jpg" style="position:absolute; top:0; left:0;" />'
                        );
                    $(win.document.body)
                        .find("table")
                        .addClass("compact")
                        .css("font-size", "inherit")
                        .css("margin", "50px auto");
                },
            },
        ],
        columnDefs: [
            {
                targets: 0,
                defaultContent: "",
                render: function (data, type, full, meta) {
                    return i++;
                },
            },
            { targets: 1, data: "district" },
            { targets: 2, data: "ac" },
            { targets: 3, data: "block" },
            {
                targets: 4,
                defaultContent: "",
                render: function (data, type, full, meta) {
                    return (
                        '<p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' +
                        full.name +
                        '">' +
                        full.name +
                        "</p>"
                    );
                },
            },
            { targets: 5, data: "agency" },
            { targets: 6, data: "wo_no" },
            { targets: 7, data: "wo_date" },
            { targets: 8, data: "contractor" },
            { targets: 9, data: "completion_date" },

            {
                targets: 10,
                defaultContent: "",
                render: function (data, type, full, meta) {
                    var document =
                        full.document !== null && full.document.length > 0
                            ? '<button class="btn btn-icon btn-round btn-sm btn-primary" onclick="_document(\'' +
                            baseURL +
                            "/" +
                            full.document +
                            '\')"  title="Document"><i class="fas fa-file-pdf"></i></button>'
                            : "";
                    return document;
                },
            },
            {
                targets: 11,
                defaultContent: "",
                render: function (data, type, full, meta) {
                    return (
                        '<button class="btn btn-icon btn-round btn-sm btn-primary" onclick="wo_add(' +
                        full.capex_id +
                        ')"  title="Edit"><i class="fas fa-pen pointer"></i></button>'
                    );
                },
            },
        ],
    });
}
function _document(url) {
    window.open(url, "_blank");
}
function wo_add(id) {
    window.location.href = baseURL + "/capex/wo_entry/" + id;
}
function wo_remove(id) {
    console.log("hiii");
    var r = confirm("Do you want to delete this?");
    if (r === true) {
        $.ajax({
            url: baseURL + "/capex/wo_remove",
            type: "get",
            data: { id: id },
            dataType: "json",
            async: false,
        }).done(function (data) {
            get_wo_list();
        });
    }
}






// ############### Capex Bridgr #######################




function get_bridge_list() {
    if ($("#district_id").val() > 0) {
        $.ajax({
            url: baseURL + "/capex/get_bridge_list",
            type: "get",
            data: {
                district_id: $("#district_id").val(),
                category_id: $("#category_id").val(),
                type_id: $("#type_id").val(),
            },
            dataType: "json",
            async: false,
        }).done(function (data) {
            _load_approved_list(data);
        });
    }
}


$("#search_bridge_tender").on("click", function (e) {
    e.preventDefault();
    get_bridge_tender_list();
});

function get_bridge_tender_list() {
    if ($("#district_id").val() > 0) {
        $.ajax({
            url: baseURL + "/capex/get_bridge_tender_list",
            type: "get",
            data: {
                district_id: $("#district_id").val(),
                category_id: $("#category_id").val(),
                type_id: $("#type_id").val(),
            },
            dataType: "json",
            async: false,
        }).done(function (data) {
            _load_bridge_tender_list(data);
        });
    }
}


function _load_bridge_tender_list(data) {
    // console.log(data);
    var i = 1;
    $("#tbl").dataTable().fnDestroy();
    var currentdate = new Date();

    $("#tbl").DataTable({
        data: data,
        dom: "lBfrtip",
        processing: true,
        scrollY: "450px",
        scrollX: true,
        scrollCollapse: true,
        paging: false,
        responsive: true,
        stateSave: true,
        colReorder: true,
        fixedColumns: {
            left: 3,
            right: 1,
        },
        buttons: [
            {
                extend: "excel",
                text: "Excel",
                filename: "tender_list_" + $.now(),
                title:
                    "BRIDGE TENDER LIST ON " +
                    String(currentdate.getDate()).padStart(2, "0") +
                    "/" +
                    String(currentdate.getMonth() + 1).padStart(2, "0") +
                    "/" +
                    currentdate.getFullYear() +
                    " " +
                    String(currentdate.getHours()).padStart(2, "0") +
                    ":" +
                    String(currentdate.getMinutes()).padStart(2, "0"),
                footer: true,
                exportOptions: {
                    columns: ":not(.not-export)",
                },
                customize: function (xlsx) {
                    var sheet = xlsx.xl.worksheets["sheet1.xml"];
                    $("row c", sheet).attr("s", "25");
                },
            },
            {
                extend: 'print',
                text: 'Print',
                title: '',
                footer: true,
                exportOptions: {
                    columns: ':not(.not-export)'
                },
                customize: function (win) {
                    // Remove default title
                    $(win.document.body).find('h1').remove();

                    // Add header: logo + title in one line (left aligned)
                    $(win.document.body).prepend(
                        '<div style="display:flex; align-items:center; margin-bottom:15px;">' +
                        '<img src="' + baseURL + '/templates/img/pathashree.jpg" ' +
                        'style="height:45px; margin-right:12px;" />' +
                        '<h2 style="margin:0; font-size:13pt; font-weight:bold;">' +
                        'RURAL ROADS (2025) – BRIDGE TENDER LIST' +
                        '</h2>' +
                        '</div>'
                    );

                    $(win.document.body).find('table')
                        .addClass('compact')
                        .css('font-size', '10pt')
                        .css('border-collapse', 'collapse')
                        .css('width', '100%');
                    $(win.document.body).find('table thead tr th')
                        .css('background-color', '#f2f2f2')
                        .css('text-align', 'center')
                        .css('padding', '6px');

                    $(win.document.body).find('table tbody tfoot tr td')
                        .css('padding', '4px 6px')
                        .css('text-align', 'center');
                }
            }
        ],
        columnDefs: [
            {
                targets: 0,
                defaultContent: "",
                render: function (data, type, full, meta) {
                    return (
                        '<input type="checkbox" name="chk[' +
                        full.id +
                        ']" id="chk_' +
                        full.id +
                        '" class="chk" value="">'
                    );
                },
            },
            { targets: 1, data: "district" },
            { targets: 2, data: "ac" },
            { targets: 3, data: "block" },
            { targets: 4, data: "scheme_id" },
            {
                targets: 5,
                defaultContent: "",
                render: function (data, type, full, meta) {
                    return (
                        '<p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' +
                        full.name +
                        '">' +
                        full.name +
                        "</p>"
                    );
                },
            },
            { targets: 6, data: "length" },
            { targets: 7, data: "agency" },
            { targets: 8, data: "sanctioned_cost" },
            { targets: 9, data: "tender_number" },
            { targets: 10, data: "tender_publication_date" },
            {
                targets: 11,
                defaultContent: "",
                render: function (data, type, full, meta) {
                    switch (full.tender_status) {
                        case "0":
                            return '<span style="color: gray; font-weight: bold;">Not Started</span>';
                        case "1":
                            return '<span style="color: orange; font-weight: bold;">On Progress</span>';
                        case "2":
                            return '<span style="color: green; font-weight: bold;">Completed</span>';
                        default:
                            return '<span style="color: red; font-weight: bold;">Retendering</span>';
                    }
                },
            },
            { targets: 12, data: "bid_closing_date" },
            { targets: 13, data: "bid_opeaning_date" },
            {
                targets: 14,
                defaultContent: "",
                render: function (data, type, full, meta) {
                    return full.evaluation_status === "1"
                        ? "Yes"
                        : full.evaluation_status === "0"
                            ? "No"
                            : "";
                },
            },
            {
                targets: 15,
                defaultContent: "",
                render: function (data, type, full, meta) {
                    return full.bid_opening_status === "1"
                        ? "Yes"
                        : full.bid_opening_status === "0"
                            ? "No"
                            : "";
                },
            },
            {
                targets: 16,
                defaultContent: "",
                render: function (data, type, full, meta) {
                    return full.bid_matured_status === "1"
                        ? "Yes"
                        : full.bid_matured_status === "0"
                            ? "No"
                            : "";
                },
            },
            {
                targets: 17,
                defaultContent: "",
                render: function (data, type, full, meta) {
                    return (
                        '<p style="margin:0px; width: 80px;"><button class="btn btn-icon btn-round btn-sm btn-primary" onclick="edit_tender(' +
                        full.id +
                        "," +
                        full.tender_status +
                        ')" title="Edit">' +
                        '<i class="fas fa-pen pointer"></i></button></p>'
                    );
                },
            },
        ],
    });
}

function edit_tender(id, tender_status) {
    // console.log(baseURL);
    // console.log(baseURL + "/ridf/tender_entry/" + id);
    if (
        tender_status == 0 ||
        tender_status == 1 ||
        tender_status == 2 ||
        tender_status == 3
    ) {
        window.location.href = baseURL + "/capex/bridge_tender_entry/" + id;
    }
}


$("#search_bridge_wo").on("click", function (e) {
    e.preventDefault();
    get_bridge_wo_list();
});

function get_bridge_wo_list() {
    $.ajax({
        url: baseURL + "/capex/get_bridge_wo_list",
        type: "get",
        data: {
            district_id: $("#district_id").val(),
            category_id: $("#category_id").val(),
            type_id: $("#type_id").val(),
        },
        dataType: "json",
        async: false,
    }).done(function (data) {
        // console.log(data);
        _load_bridge_wo_list(data);
    });
}

function _load_bridge_wo_list(data) {
    console.log(data);
    var i = 1;
    $("#tbl").dataTable().fnDestroy();
    var currentdate = new Date();

    $("#tbl").DataTable({
        data: data,
        dom: "lBfrtip",
        processing: true,
        scrollY: "450px",
        scrollX: true,
        scrollCollapse: true,
        paging: false,
        responsive: true,
        stateSave: true,
        colReorder: true,
        fixedColumns: {
            left: 3,
            right: 1,
        },
        buttons: [
            {
                extend: "excel",
                text: "Excel",
                filename: "bridge_wo_list_" + $.now(),
                title:
                    "WORK ORDER LIST ON " +
                    String(currentdate.getDate()).padStart(2, "0") +
                    "/" +
                    String(currentdate.getMonth() + 1).padStart(2, "0") +
                    "/" +
                    currentdate.getFullYear() +
                    " " +
                    String(currentdate.getHours()).padStart(2, "0") +
                    ":" +
                    String(currentdate.getMinutes()).padStart(2, "0"),
                footer: true,
                exportOptions: {
                    columns: ":not(.not-export)",
                },
                customize: function (xlsx) {
                    var sheet = xlsx.xl.worksheets["sheet1.xml"];
                    $("row c", sheet).attr("s", "25");
                },
            },
            {
                extend: "print",
                text: "Print",
                title: "WORK ORDER LIST",
                footer: true,
                exportOptions: {
                    columns: ":not(.not-export)",
                },
                customize: function (win) {
                    $(win.document.body)
                        .find("h1")
                        .css("text-align", "center")
                        .css("font-size", "10pt")
                        .prepend(
                            '<img src="' +
                            baseURL +
                            '/templates/img/pathashree.jpg" style="position:absolute; top:0; left:0;" />'
                        );
                    $(win.document.body)
                        .find("table")
                        .addClass("compact")
                        .css("font-size", "inherit")
                        .css("margin", "50px auto");
                },
            },
        ],
        columnDefs: [
            {
                targets: 0,
                defaultContent: "",
                render: function (data, type, full, meta) {
                    return i++;
                },
            },
            { targets: 1, data: "district" },
            { targets: 2, data: "ac" },
            { targets: 3, data: "block" },
            {
                targets: 4,
                defaultContent: "",
                render: function (data, type, full, meta) {
                    return (
                        '<p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' +
                        full.name +
                        '">' +
                        full.name +
                        "</p>"
                    );
                },
            },
            { targets: 5, data: "agency" },
            { targets: 6, data: "wo_no" },
            { targets: 7, data: "wo_date" },
            { targets: 8, data: "contractor" },
            { targets: 9, data: "completion_date" },
            {
                targets: 10,
                defaultContent: "",
                render: function (data, type, full, meta) {
                    var document =
                        full.document !== null && full.document.length > 0
                            ? '<button class="btn btn-icon btn-round btn-sm btn-primary" onclick="_document(\'' +
                            baseURL +
                            "/" +
                            full.document +
                            '\')"  title="Document"><i class="fas fa-file-pdf"></i></button>'
                            : "";
                    return document;
                },
            },
            {
                targets: 11,
                defaultContent: "",
                render: function (data, type, full, meta) {
                    return (
                        '<button class="btn btn-icon btn-round btn-sm btn-primary" onclick="wo_add(' +
                        full.capex_bridge_id +
                        ')"  title="Edit"><i class="fas fa-pen pointer"></i></button>'
                    );
                },
            },
        ],
    });
}

function wo_add(id) {
  window.location.href = baseURL + "/capex/bridge_wo_entry/" + id;
}