
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
        get_lotno_list();
        get_assembly_list();
    });
    $('#block_id').on('change', function (e) {
        e.preventDefault();
        get_gp_list();
    });
    $('#search').on('click', function (e) {
        e.preventDefault();
        get_survey_list();
    });
    $('#search_qm').on('click', function (e) {
        e.preventDefault();
        get_rpt_qm_list();
    });
    $('#search_survey').on('click', function (e) {
        e.preventDefault();
        get_survey_pending_list();
    });
    $('#search_wo').on('click', function (e) {
        e.preventDefault();
        get_wo_list();
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
                url: baseURL + '/ssm/create_lot_no',
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
            url: baseURL + '/ssm/forwarded',
            type: 'post',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            async: false
        }).done(function (data) {
            alert('Successfully forwarded');
            window.location.href = baseURL + '/ssm/lot';
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
                url: baseURL + '/ssm/create_lot_no',
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
    $('#search_approved').on('click', function (e) {
        e.preventDefault();
        get_approved_list();
    });
    $('#admin').on('submit', function (e) {
        e.preventDefault();
        if ($('#lotno').val().length > 0) {
            var formData = new FormData(this);
            $.ajax({
                url: baseURL + '/ssm/admin_approval',
                type: 'post',
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,
                async: false
            }).done(function (data) {
                alert('saved successfully');
                window.location.href = baseURL + '/ssm/lot';
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
                    url: baseURL + '/ssm/return_to_prev',
                    type: 'post',
                    data: {district_id: $('#district_id').val(), block_id: $('#block_id').val(), arr: arr, msg: msg},
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
});
function get_lotno_list() {
    $.ajax({
        url: baseURL + '/ssm/get_lotno_list',
        type: 'get',
        data: {district_id: $('#district_id').val()},
        dataType: 'json',
        async: false
    }).done(function (data) {
        $('#lotno').empty();
        if (data.length > 0) {
            $('#lotno').append(
                    $('<option>', {value: '0', text: '--Select Lot No--'})
                    );
            $.each(data, function (i, item) {
                $('#lotno').append(
                        $('<option>', {value: item.lotno, text: item.lotno})
                        );
            });
        }
    });
}
function get_block_list() {
    $.ajax({
        url: baseURL + '/ssm/get_block_list',
        type: 'get',
        data: {district_id: $('#district_id').val()},
        dataType: 'json',
        async: false
    }).done(function (data) {
        $('#block_id').empty();
        if (data.length > 0) {
            $('#block_id').append(
                    $('<option>', {value: '0', text: '--All Block--'})
                    );
            $.each(data, function (i, item) {
                $('#block_id').append(
                        $('<option>', {value: item.id, text: item.name})
                        );
            });
        } else if ($('#district_id').val() === 0) {
            $('#block_id').append(
                    $('<option>', {value: '0', text: '--All Block--'})
                    );
        } else {
            $('#block_id').append(
                    $('<option>', {value: '', text: '--Select Block--'})
                    );
        }
    });
}

function get_assembly_list() {

    $.ajax({
        url: baseURL + '/ssm/get_assembly_list',
        type: 'get',
        data: {district_id: $('#district_id').val()},
        dataType: 'json',
        async: false
    }).done(function (data) {
        $('#ac_id').empty();
        if (data.length > 0) {
            $('#ac_id').append(
                    $('<option>', {value: '0', text: '--All Assembly--'})
                    );
            $.each(data, function (i, item) {
                $('#ac_id').append(
                        $('<option>', {value: item.id, text: item.name})
                        );
            });
        }
    });
}
function get_gp_list() {
    console.log('block_id: ' + $('#block_id').val());
    $.ajax({
        url: baseURL + '/ssm/get_gp_list',
        type: 'get',
        data: {block_id: $('#block_id').val()},
        dataType: 'json',
        async: false
    }).done(function (data) {
        $('#gp_id').empty();
        if (data.length > 0) {
            $('#gp_id').append($('<option>', {value: '', text: '--Select GP--'}));
            $.each(data, function (i, item) {
                $('#gp_id').append($('<option>', {value: item.id, text: item.name}));
            });
        }
    });
}
function get_survey_list() {
    if ($('#district_id').val() > 0) {
        $.ajax({
            url: baseURL + '/ssm/get_survey_list',
            type: 'get',
            data: {district_id: $('#district_id').val(), status: $('input[name="status"]:checked').val()},
            dataType: 'json',
            async: false
        }).done(function (data) {
//            console.log(data);
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
                filename: 'ssm_master_' + $.now(),
                title: 'PATHASHREE-III MASTER ON ' + String(currentdate.getDate()).padStart(2, '0') + '/' + String((currentdate.getMonth() + 1)).padStart(2, '0') + '/'
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
                title: 'PATHASHREE-III MASTER',
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
            {targets: 2, data: 'ac'},
            {targets: 3, data: 'district'},

            {targets: 4, data: 'block'},
            {
                targets: 5, defaultContent: '',
                render: function (data, type, full, meta) {
                    return full.gp;
                }
            },
            {targets: 6, data: 'ref_no'},
            {targets: 7, data: 'agency'},
            {targets: 8, data: 'work_type'},
            {targets: 9, data: 'road_type'},
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
                    if($('input[name="status"]:checked').val() > -1) {
                        var not_implemented = role_id < 3 ? '&nbsp;<button title="Mark scheme as not implemented" class="btn btn-icon btn-round btn-sm btn-danger" onclick="mark_not_traceable(' + full.id + ')"><i class="fas fa-minus-circle"></i></button>' : '';
                        var sent_to_inbox = role_id < 3 && full.survey_status == 0 && full.survey_lot_no !== null && full.survey_lot_no.length > 0 > 0 && full.dm_lot_no === null && full.se_lot_no === null ? '&nbsp;<button title="back to scheme inbox" class="btn btn-icon btn-round btn-sm btn-warning" onclick="back_to_inbox(' + full.id + ')"><i class="fa fa-undo"></i></button>' : '';
                        return '<button class="btn btn-icon btn-round btn-sm btn-primary" onclick="add(' + full.id + ')"  title="Edit"><i class="fas fa-pen pointer"></i></button>' + not_implemented + sent_to_inbox;
                    } else {
                        return ('<p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' + full.remarks + '">' + full.remarks + '</p>');
                    }                    
                }
            }
//            {
//                targets: 11,
//                render: function (data, type, full, meta) {
//                    var remove = role_id < 4 && full.agency === null ? '<button class="btn btn-icon btn-round btn-sm btn-danger" onclick="remove(' + full.id + ')"  title="Remove"><i class="fas fa-times pointer"></i></button>' : '';
//                    return '<p style="margin:0px; width: 90px"><button class="btn btn-icon btn-round btn-sm btn-primary" onclick="add(' +
//                            full.id + ')"  title="Edit"><i class="fas fa-pen pointer"></i></button>&nbsp;&nbsp;&nbsp;' + remove + '</p>';
//                }
//            }
        ]
    });
}

function get_rpt_qm_list() {
    $.ajax({
        url: baseURL + '/ssm/get_rpt_qm_list',
        type: 'get',
        data: {start_date: $('#start_date').val(), end_date: $('#end_date').val()},
        dataType: 'json',
        async: false
    }).done(function (data) {
        console.log(data);
        var total = 0;
        var grand_total = 0;
        var s_total = 0;
        var sri_total = 0;
        var u_total = 0;
        var old_id = '0';
        var new_id = '0';
        var old_agency = '';
        var new_agency = '';
        var i = 0;
        $.each(data, function (index, item) {
            console.log(i);
            new_id = item.id;
            old_id = i == 0 ? item.id : old_id;
            new_agency = item.agency;
            old_agency = i == 0 ? item.agency : old_agency;
            //console.log('old_id: ' + old_id + ' ## new_id: ' + new_id);
            //console.log('old_agency: ' + old_agency + ' ## new_agency: ' + new_agency);
            if (old_id != new_id) {
                //console.log('district: ' + u_total);
                $('#' + old_agency.toLowerCase() + '_total_' + old_id).text(total);
                $('#' + 's_total_' + old_id).text(s_total);
                $('#' + 'sri_total_' + old_id).text(sri_total);
                $('#' + 'u_total_' + old_id).text(u_total);
                $('#' + 'grand_total_' + old_id).text(parseInt(s_total + sri_total + u_total));
                total = 0;
                s_total = 0;
                sri_total = 0;
                u_total = 0;
            } else if (old_agency != new_agency) {
                //console.log('agency: ' + total);
                $('#' + old_agency.toLowerCase() + '_total_' + old_id).text(total);
                total = 0;
            }
            $('#' + item.agency.toLowerCase() + '_' + item.overall_grade.toLowerCase() + '_' + item.id).text(item.cnt);
            switch (item.overall_grade.toLowerCase()) {
                case 's':
                    s_total += parseInt(item.cnt);
                    break;
                case 'sri':
                    sri_total += parseInt(item.cnt);
                    break;
                case 'u':
                    u_total += parseInt(item.cnt);
                    break;
                default:
                    break;
            }
            old_id = new_id;
            old_agency = new_agency;
            total += parseInt(item.cnt);
            i++;
        });
        $('#' + old_agency.toLowerCase() + '_total_' + old_id).text(total);
        $('#' + 's_total_' + old_id).text(s_total);
        $('#' + 'sri_total_' + old_id).text(sri_total);
        $('#' + 'u_total_' + old_id).text(u_total);
        $('#' + 'grand_total_' + old_id).text(parseInt(s_total + sri_total + u_total));
        console.log('s_total: ' + s_total);
        console.log('sri_total: ' + sri_total);
        console.log('u_total: ' + u_total);
        console.log('grand_total: ' + parseInt(s_total + sri_total + u_total));
        // console.log(s_total+sri_total+u_total);
        console.log('total: ' + total);


    });
}
function remove(id) {
    if (confirm('Do you want to remove this scheme?') === true) {
        $.ajax({
            url: baseURL + '/ssm/remove_survey_list',
            type: 'get',
            data: {id: id, district_id: $('#district_id').val(), block_id: $('#block_id').val()},
            dataType: 'json',
            async: false
        }).done(function (data) {
            _load_survey_list(data);
        });
    }
}
function add(id) {
    window.location.href = baseURL + '/ssm/entry/' + id;
}
function get_survey_pending_list() {
    if ($('#district_id').val() > 0 && $('#block_id').val() > 0) {
        $.ajax({
            url: baseURL + '/ssm/get_survey_pending_list',
            type: 'get',
            data: {
                district_id: $('#district_id').val(),
                block_id: $('#block_id').val(),
                ac_id: $('#ac_id').val()
            },
            dataType: 'json',
            async: false
        }).done(function (data) {
            _load_survey_pending_list(data);
        });
    }
}
function _load_survey_pending_list(data) {
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
                filename: 'survey_pending_list_' + $.now(),
                title: 'SURVEY PENDING LIST ON ' + String(currentdate.getDate()).padStart(2, '0') + '/' + String((currentdate.getMonth() + 1)).padStart(2, '0') + '/'
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
                title: 'SURVEY PENDING LIST',
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
                    var disabled = full.status === '2' ? '' : 'disabled';
                    return '<input type="checkbox" name="chk[' + full.id + ']" id="chk_' + full.id + '" class="chk" value="" ' + disabled + '>';
                }
            },
            {targets: 1, data: 'district'},
            {targets: 2, data: 'ac'},
            {targets: 3, data: 'block'},
            {targets: 4, data: 'gp'},
            {targets: 5, data: 'ref_no'},
            {
                targets: 6, defaultContent: '',
                render: function (data, type, full, meta) {
                    return '<p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' + full.name + '">' + full.name + '</p>';
                }
            },
            {targets: 7, data: 'length'},
            {targets: 8, data: 'road_type'},
            {targets: 9, data: 'work_type'},
            {targets: 10, data: 'agency'},
            {
                targets: 11, defaultContent: '',
                render: function (data, type, full, meta) {
                    return full.status === '0'
                            ? 'Not Started'
                            : full.status === '1'
                            ? 'On Going'
                            : 'Completed';
                }
            },
            {
                targets: 12, defaultContent: '',
                render: function (data, type, full, meta) {
                    var vec = full.status === '2' ? '<i class="fa fa-edit pointer" onclick="vec(' + full.id + ')"></i>' : '';
                    return '<span id="cost_' + full.id + '">' + (full.cost === null ? "" : full.cost) + '</span>' + vec;
                }
            },
            {targets: 13, data: 'return_cause'},
            {
                targets: 14, defaultContent: '',
                render: function (data, type, full, meta) {
                    var survey = full.status === '2' ? 'disabled' : '';
                    return (
                            '<p style="margin:0px; width: 90px"><button class="btn btn-icon btn-round btn-sm btn-primary" onclick="add_survey(' +
                            full.id +
                            ')"  title="Edit" ' +
                            survey +
                            '><i class="fas fa-plus pointer"></i></button>&nbsp; <button data-toggle="tooltip" data-placement="bottom" title="Mark Scheme Not Implemented" class="btn btn-danger btn-icon btn-round btn-sm" onclick="mark_not_traceable(' +
                            full.id +
                            ')" ><i class="fas fa-minus-circle"></i></button> </p>'
                            );
                }
            }
        ]
    });
}

function back_to_inbox(id) {
    window.location.href = baseURL + '/ssm/back_to_inbox/' + id;
}

function mark_not_traceable(id) {
    window.location.href = baseURL + '/ssm/not_traceable/' + id;
}

function add_survey(id) {
    window.location.href = baseURL + '/ssm/survey_entry/' + id;
}
function vec(id) {
    var cost = prompt('Give your vetted estimated cost:');
    if (isNaN(cost)) {
        alert('You must input numbers');
    } else {
        $('#cost_' + id).text(cost);
        $.ajax({
            url: baseURL + '/ssm/survey_vec_save',
            type: 'get',
            data: {id: id, cost: cost},
            dataType: 'json',
            async: false,
        }).done(function (data) {
            $('#chk_' + id).prop('disabled', false);
        });
    }
}
//function forwarded_to_dm(id) {
//    if(confirm('Are you sure you want to forward this scheme to DM?')) {
//        $.ajax({
//            url: baseURL + '/ssm/forwarded_to_dm',
//            type: 'get',
//            data: {id: id, district_id: $('#district_id').val(), block_id: $('#block_id').val()},
//            dataType: 'json',
//            async: false
//        }).done(function(data) {
//            alert('The scheme has been to forwarded to DM successfully.');
//            _load_survey_pending_list(data);
//        });
//    }
//}
function print_lot() {
    window.open(baseURL + '/ssm/print_lot/' + $('#district_id').val() + '/' + $('#lotno').val(), '_blank');
}
function get_lot_list() {
    if ($('#district_id').val() > 0 && $('#lotno').val().length > 0) {
        $.ajax({
            url: baseURL + '/ssm/get_lot_list',
            type: 'get',
            data: {district_id: $('#district_id').val(), lotno: $('#lotno').val()},
            dataType: 'json',
            async: false
        }).done(function (data) {
            console.log(data);
            _load_lot_list(data);
        });
    }
}
function _load_lot_list(data) {
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
                filename: 'lot_' + $.now(),
                title: 'LOT ON ' + String(currentdate.getDate()).padStart(2, '0') + '/' + String((currentdate.getMonth() + 1)).padStart(2, '0') + '/'
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
                title: 'LOT',
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
            {targets: 1, data: 'district'},
            {targets: 2, data: 'ac'},
            {targets: 3, data: 'block'},
            {targets: 4, data: 'gp'},
            {targets: 5, data: 'ref_no'},
            {targets: 6, data: 'lotno'},
            {
                targets: 7, defaultContent: '',
                render: function (data, type, full, meta) {
                    return '<p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' + full.name + '">' + full.name + '</p>';
                }
            },
            {targets: 8, data: 'length'},
            {targets: 9, data: 'road_type'},
            {targets: 10, data: 'work_type'},
            {targets: 11, data: 'agency'},
            {
                targets: 12, defaultContent: '',
                render: function (data, type, full, meta) {
                    return full.status === '0'
                            ? 'Not Started'
                            : full.status === '1'
                            ? 'On Going'
                            : 'Completed';
                }
            },
            {
                targets: 13, defaultContent: '',
                render: function (data, type, full, meta) {
                    var vec =
                            full.status === '2'
                            ? '<i class="fa fa-edit pointer" onclick="vec(' +
                            full.id +
                            ')"></i>'
                            : '';
                    return '<span id="cost_' + full.id + '">' + (full.cost === null ? '' : full.cost) + '</span>' + vec;
                }
            }
        ]
    });
}

function get_approval_list() {
    if ($('#district_id').val() > 0) {
        $.ajax({
            url: baseURL + '/ssm/get_approval_list',
            type: 'get',
            data: {
                district_id: $('#district_id').val(),
                block_id: $('#block_id').val(),
            },
            dataType: 'json',
            async: false
        }).done(function (data) {
            _load_approval_list(data);
        });
    }
}
function _load_approval_list(data) {
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
                filename: 'lot_' + $.now(),
                title: 'LOT ON ' + String(currentdate.getDate()).padStart(2, '0') + '/' + String((currentdate.getMonth() + 1)).padStart(2, '0') + '/'
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
                title: 'LOT',
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
                    return '<input type="checkbox" name="chk[' + full.id + ']" id="chk_' + full.id + '" class="chk" value="">';
                }
            },
            {targets: 1, data: 'lot_no'},
            {targets: 2, data: 'district'},
            {targets: 3, data: 'ac'},
            {targets: 4, data: 'block'},
            {targets: 5, data: 'gp'},
            {targets: 6, data: 'ref_no'},
            {
                targets: 7, defaultContent: '',
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
            {targets: 8, data: 'proposed_length'},
            {targets: 9, data: 'length'},
            {targets: 10, data: 'road_type'},
            {targets: 11, data: 'work_type'},
            {targets: 12, data: 'agency'},
            {
                targets: 13, defaultContent: '',
                render: function (data, type, full, meta) {
                    return full.status === '0'
                            ? 'Not Started'
                            : full.status === '1'
                            ? 'On Going'
                            : 'Completed';
                }
            },
            {
                targets: 14, defaultContent: '',
                render: function (data, type, full, meta) {
                    var vec =
                            full.status === '4'
                            ? '<i class="fa fa-edit pointer" onclick="vec(' +
                            full.id +
                            ')"></i>'
                            : '';
                    return '<span id="cost_' + full.id + '">' + (full.cost === null ? "" : full.cost) + '</span>' + vec;
                }
            },

            {
                targets: 15, defaultContent: '',
                render: function (data, type, full, meta) {
                    var vec =
                            full.status === '4'
                            ? '<i class="fa fa-edit pointer" onclick="vec(' +
                            full.id +
                            ')"></i>'
                            : '';
                    if (
                            full.cost !== null &&
                            full.cost !== 0 &&
                            full.length !== null &&
                            full.length !== 0
                            ) {
                        return (
                                '<span id="cost_' +
                                full.id +
                                '">' +
                                (full.cost === null
                                        ? ""
                                        : (full.cost / full.length / 100000).toFixed(2)) +
                                '</span>' +
                                vec
                                );
                    }
                    return '<span id="cost_' + full.id + '"> 0.00 </span>' + vec;
                }
            },
            {targets: 16, data: 'return_cause'},
            {
                targets: 17, defaultContent: '',
                render: function (data, type, full, meta) {
                    var doc =
                            full.lot_doc.length > 0
                            ? '<a target="_blank" class="btn btn-sm btn-success" href="' +
                            baseURL +
                            "/" +
                            full.lot_doc +
                            '"><i class="fas fa-file-pdf"></i></a>'
                            : '';
                    return doc;
                }
            }
        ]
    });
}
function get_approved_list() {
    if ($('#district_id').val() > 0) {
        $.ajax({
            url: baseURL + '/ssm/get_approved_list',
            type: 'get',
            data: {
                district_id: $('#district_id').val(),
                block_id: $('#block_id').val(),
                ac_id: $('#ac_id').val()
            },
            dataType: 'json',
            async: false
        }).done(function (data) {
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
            left: 3,
            right: 1
        },
        buttons: [
            {
                extend: 'excel',
                text: 'Excel',
                filename: 'approved_list_' + $.now(),
                title: 'APPROVED LIST ON ' + String(currentdate.getDate()).padStart(2, '0') + '/' + String((currentdate.getMonth() + 1)).padStart(2, '0') + '/'
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
                title: 'APPROVED LIST',
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
            {targets: 0, defaultContent: '',
                render: function (data, type, full, meta) {
                    return ('<input type="checkbox" name="chk[' + full.id + ']" id="chk_' + full.id + '" class="chk" value="">');
                }
            },
            {targets: 1, data: 'district'},
            {targets: 2, data: 'ac'},
            {targets: 3, data: 'block'},
            {targets: 4, data: 'gp'},
            {targets: 5, data: 'ref_no'},
            {targets: 6, defaultContent: '',
                render: function (data, type, full, meta) {
                    return ('<p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' + full.name + '">' + full.name + '</p>');
                }
            },
            {targets: 7, data: 'length'},
            {targets: 8, data: 'road_type'},
            {targets: 9, data: 'work_type'},
            {targets: 10, data: 'agency'},
            {targets: 11, defaultContent: '',
                render: function (data, type, full, meta) {
                    return full.status === '0' ? 'Not Started' : (full.status === '1' ? 'On Going' : 'Completed');
                }
            },
            {targets: 12, data: 'cost'},
            {targets: 13, defaultContent: '',
                render: function (data, type, full, meta) {
                    var doc = full.lot_doc.length > 0 ? '<a target="_blank" class="btn btn-sm btn-success" href="' + baseURL + '/' + full.lot_doc + '"><i class="fas fa-file-pdf"></i></a>' : '';
                    return doc;
                }
            }
        ]
    });
}

function get_state_approval_list() {
    if ($('#district_id').val() > 0 && $('#block_id').val() > 0) {
        $.ajax({
            url: baseURL + '/ssm/get_state_approval_list',
            type: 'get',
            data: {
                district_id: $('#district_id').val(),
                block_id: $('#block_id').val(),
            },
            dataType: 'json',
            async: false,
        }).done(function (data) {
            _load_state_approval_list(data);
        });
    }
}
function _load_state_approval_list(data) {
    var i = 1;
    console.log(data);
    $('#tblsrdabody').empty();
    $.each(data, function (index, item) {
        var row =
                '<td>' +
                i +
                '</td><td>' +
                item.district +
                '</td><td>' +
                item.block +
                '</td><td>' +
                item.gp +
                '</td><td>' +
                item.name +
                '</td><td>' +
                item.length +
                '</td><td>' +
                item.road_type +
                '</td><td>' +
                item.agency +
                '</td><td>Completed</td><td><input type="checkbox" name="chk[' +
                item.id +
                ']" id="chk_"' +
                item.id +
                ' class="chk" value=""></td>';
        $('#tblsrdabody').append('<tr>' + row + '</tr>');
        i += 1;
    });
}

function get_not_imp_list() {
    $.ajax({
        url: baseURL + '/ssm/get_not_imp_list',
        type: 'get',
        data: {
            district_id: $('#district_id').val(),
            block_id: $('#district_id').val() > 0 ? $('#block_id').val() : 0,
        },
        dataType: 'json',
        async: false,
    }).done(function (data) {
        _load_not_imp_list(data);
    });
}
function _load_not_imp_list(data) {
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
                filename: 'not_implemented_list_' + $.now(),
                title: 'NOT IMPLEMENTED LIST ON ' + String(currentdate.getDate()).padStart(2, '0') + '/' + String((currentdate.getMonth() + 1)).padStart(2, '0') + '/'
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
                title: 'NOT IMPLEMENTED LIST',
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
            {targets: 1, data: 'created'},
            {targets: 2, data: 'district'},
            {targets: 3, data: 'block'},
            {targets: 4, data: 'gp'},
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
            {targets: 6, data: 'proposed_length'},
            {targets: 7, data: 'road_type'},
            {targets: 8, data: 'work_type'},
            {targets: 9, data: 'agency'},
            {
                targets: 10, defaultContent: '',
                render: function (data, type, full, meta) {
                    return full.status === '0'
                            ? 'Not Traceable'
                            : full.status === '1'
                            ? 'Taken up with Other Scheme/Fund'
                            : 'Others';
                }
            },
            {targets: 11, data: 'remarks'},
            {
                targets: 12, defaultContent: '',
                render: function (data, type, full, meta) {
                    return (
                            '<td><p style="margin:0px; width: 80px"><button class="btn btn-icon btn-round btn-sm btn-danger" onclick="delete_not_traceable('
                            + full.id +
                            ')"  title="Delete"><i class="fas fa-trash pointer"></i></button></p></td>'
                            );
                }
            }
        ]
    });
}

function delete_not_traceable(id) {
    var r = confirm('Do you want to delete this?')
    if (r === true) {
        window.location.href = baseURL + '/ssm/delete_not_traceable/' + id;
    }
}

$('#search_tender').on('click', function (e) {
    e.preventDefault();
    get_tender_list();
});

function tender_entry(id) {
    window.location.href = baseURL + '/ssm/tender_entry/' + id;
}

function get_tender_list() {
    if ($('#district_id').val() > 0) {
        $.ajax({
            url: baseURL + '/ssm/get_tender_list',
            type: 'get',
            data: {
                district_id: $('#district_id').val(),
                block_id: $('#block_id').val(),
                ac_id: $('#ac_id').val()
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
            {targets: 1, data: 'district'},
            {targets: 2, data: 'ac'},
            {targets: 3, data: 'block'},
            {targets: 4, data: 'gp'},
            {targets: 5, data: 'ref_no'},
            {
                targets: 6, defaultContent: '',
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
            {targets: 7, data: 'length'},
            {targets: 8, data: 'agency'},
            {targets: 9, data: 'cost'},
            {targets: 10, data: 'tender_number'},
            {targets: 11, data: 'tender_publication_date'},
            {
                targets: 12, defaultContent: '',
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
            {targets: 13, data: 'bid_closing_date'},
            {targets: 14, data: 'bid_opeaning_date'},
            {
                targets: 15, defaultContent: '',
                render: function (data, type, full, meta) {
                    return full.evaluation_status === null
                            ? ''
                            : full.evaluation_status === '0'
                            ? 'No'
                            : 'Yes';
                },
            },
            {
                targets: 16, defaultContent: '',
                render: function (data, type, full, meta) {
                    return full.bid_opening_status === null
                            ? ''
                            : full.bid_opening_status === '0'
                            ? 'No'
                            : 'Yes';
                }
            },
            {
                targets: 17, defaultContent: '',
                render: function (data, type, full, meta) {
                    return full.bid_matured_status === null
                            ? ''
                            : full.bid_matured_status === '0'
                            ? 'No'
                            : 'Yes';
                }
            },
            {
                targets: 18, defaultContent: '',
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

function get_wo_list() {
    $.ajax({
        url: baseURL + '/ssm/get_wo_list',
        type: 'get',
        data: {district_id: $('#district_id').val(), block_id: $('#block_id').val(), ac_id: $('#ac_id').val()},
        dataType: 'json',
        async: false
    }).done(function (data) {
        _load_wo_list(data);
    });
}
function _load_wo_list(data) {
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
                filename: 'wo_list_' + $.now(),
                title: 'WORK ORDER LIST ON ' + String(currentdate.getDate()).padStart(2, '0') + '/' + String((currentdate.getMonth() + 1)).padStart(2, '0') + '/'
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
                title: 'WORK ORDER LIST',
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
            {targets: 0, defaultContent: '',
                render: function (data, type, full, meta) {
                    return i++;
                }},
            {targets: 1, data: 'district'},
            {targets: 2, data: 'ac'},
            {targets: 3, data: 'block'},
            {targets: 4, defaultContent: '',
                render: function (data, type, full, meta) {
                    return '<p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' + full.name + '">' + full.name + '</p>'
                }
            },
            {targets: 5, data: 'agency'},
            {targets: 6, data: 'wo_no'},
            {targets: 7, data: 'wo_date'},
            {targets: 8, data: 'contractor'},
            {targets: 9, data: 'completion_date'},
            {targets: 10, data: 'assigned_engineer'},
            {targets: 11, defaultContent: '',
                render: function (data, type, full, meta) {
                    var document = full.document !== null && full.document.length > 0 ? '<button class="btn btn-icon btn-round btn-sm btn-primary" onclick="_document(\'' + baseURL + '/' + full.document + '\')"  title="Document"><i class="fas fa-file-pdf"></i></button>' : '';
                    return document;
                }
            },
            {targets: 12, defaultContent: '',
                render: function (data, type, full, meta) {
                    return '<button class="btn btn-icon btn-round btn-sm btn-primary" onclick="wo_add(' + full.ssm_id + ')"  title="Edit"><i class="fas fa-pen pointer"></i></button>'
                }}
        ]
    });
}

function _document(url) {
    window.open(url, '_blank');
}

function wo_add(id) {
    window.location.href = baseURL + '/ssm/wo_entry/' + id;
}
function wo_remove(id) {
    var r = confirm('Do you want to delete this?')
    if (r === true) {
        $.ajax({
            url: baseURL + '/ssm/wo_remove',
            type: 'get',
            data: {id: id},
            dataType: 'json',
            async: false
        }).done(function (data) {
            get_wo_list();
        });
    }
}
