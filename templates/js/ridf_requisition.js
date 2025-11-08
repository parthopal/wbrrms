
/* To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {
    var district_id, block_id, category_id, agency_id;
    $('#district_id').on('change', function (e) {
        e.preventDefault();
        district_id = $('#district_id').val();
        category_id = $('#category_id').val();
        agency_id = $('#agency_id').val();
        get_work_list(district_id, category_id, agency_id);
    });
    $('#category_id').on('change', function (e) {
        e.preventDefault();
        district_id = $('#district_id').val();
        category_id = $('#category_id').val();
        agency_id = $('#agency_id').val();
        get_work_list(district_id, category_id, agency_id);
    });
    $('#agency_id').on('change', function (e) {
        e.preventDefault();
        district_id = $('#district_id').val();
        category_id = $('#category_id').val();
        agency_id = $('#agency_id').val();
        get_work_list(district_id, category_id, agency_id);
    });
    $('#ridf_id').on('change', function (e) {
        e.preventDefault();
        get_work_info();
        get_prev_requisition_list();
    });
    $('#iscurrent').on('change', function (e) {
        e.preventDefault();
        var val = $('#iscurrent').val();
        if (val === '0') {
            $('#iscurrent').val('1');
            $('.approved').hide();
            $('#requisition').removeClass('col-md-6');
            $('#requisition').addClass('col-md-12');
            $('.terms').show();
            $('#submit').attr('disabled', 'disabled');
        } else {
            $('#iscurrent').val('0');
            $('.approved').show();
            $('#requisition').removeClass('col-md-12');
            $('#requisition').addClass('col-md-6');
            $('.terms').hide();
            $('#submit').prop('disabled', false);
        }
    });
    $('#claimed').on('change', function (e) {
        e.preventDefault();
        if ($('#claimed').val() === '1') {
            $('.claimed').hide();
            $('#claimed').val(0);
        } else {
            $('.claimed').show();
            $('#claimed').val(1);
        }
    });
    $('#dpr').on('change', function (e) {
        e.preventDefault();
        if ($('#dpr').val() === '1') {
            $('.dpr').hide();
            $('#dpr').val(0);
        } else {
            $('.dpr').show();
            $('#dpr').val(1);
        }
    });
    $('#contigency').on('change', function (e) {
        e.preventDefault();
        if ($('#contigency').val() === '1') {
            $('.contigency').hide();
            $('#contigency').val(0);
        } else {
            $('.contigency').show();
            $('#contigency').val(1);
        }
    });
    $('#terms').on('change', function (e) {
        e.preventDefault();
        var val = $('#terms').val();
        if (val === '0') {
            $('#terms').val('1');
            $('#submit').removeAttr('disabled');
        } else {
            $('#terms').val('0');
            $('#submit').attr('disabled', 'disabled');
        }
    });
});

function get_work_list(district_id, category_id, agency_id) {
    $.ajax({
        url: baseURL + '/ridf/get_work_list',
        type: 'get',
        data: { district_id: district_id, category_id: category_id, agency_id: agency_id },
        dataType: 'json',
        async: false
    }).done(function (data) {
        $('#ridf_id').empty();
        if (data.length > 0) {
            $('#ridf_id').append(
                $('<option>', { value: '0', text: '--Select Work--' })
            );
            $.each(data, function (i, item) {
                $('#ridf_id').append($('<option>', { value: item.id, text: item.name + ' (' + item.scheme_id + ')' }));
            });
        }
    });
}


function get_work_info() {
    let ridf_id = $('#ridf_id').val();
    if (ridf_id > 0) {
        $('#info').show();
        $.ajax({
            url: baseURL + '/ridf/get_work_info',
            type: 'get',
            data: { ridf_id: ridf_id },
            dataType: 'json',
            async: false
        }).done(function (data) {
            $('#info_1').html(data.agency + ' | ' + data.category + ' | ' + data.scheme_id);
            $('#info_2').html(data.name);
            $('#info_3').html(Math.round(data.sanctioned_cost));
            $('#info_4').html(Math.round(data.awarded_cost));
            $('#info_5').html(Math.round(data.total_approved_claimed_amt));
            $('#info_6').html(Math.round(data.total_claimed_expenditure_amt) + Math.round(data.total_dpr_expenditure_amt) + Math.round(data.total_contigency_expenditure_amt));
            $('#info_7').html(Math.round(data.total_approved_claimed_amt) + Math.round(data.total_approved_dpr_amt) + Math.round(data.total_approved_contigency_amt));
            var loan = (data.sanctioned_cost * 0.8).toFixed(0);
            $('#info_8').html(loan);
            $('#info_9').html(parseInt(data.total_approved_dpr_amt) + '/' + (loan * 0.005).toFixed(0));
            $('#info_10').html(parseInt(data.total_approved_contigency_amt) + '/' + (data.awarded_cost * 0.03).toFixed(0));

            $('#physical_progress').on('input', function () {
                let progress = parseFloat($(this).val()) || 0;  
                let claimedAmount = (data.awarded_cost * (progress / 100)) - data.total_approved_claimed_amt;
                $('#claimed_amt').val(claimedAmount.toFixed(0));
            });


        });

    } else {
        $('#info').hide();
    }
}

function get_prev_requisition_list() {

    $.ajax({
        url: baseURL + '/ridf/get_prev_requisition_list',
        type: 'get',
        data: { ridf_id: $('#ridf_id').val() },
        dataType: 'json',
        async: false
    }).done(function (data) {
        if (data.length > 0) {
            let ra_id = Number(data.length + 1);
            $('#ra_id').html('RA - ' + ra_id);
            $('#hdn_ra_id').val(ra_id);
            $('#prev').show();

            var cnt = 0;
            $.each(data, function (i, item) {
                if (item.ref_no.length > 0) {
                    cnt = 1;
                }
            });
            if (cnt > 0) {
                $('#iscurrent').bootstrapToggle('on');
                $('#iscurrent').val(1).trigger('change');
                $('#iscurrent').closest('.form-group').hide();
            } else {
                $('#iscurrent').bootstrapToggle('off');
                $('#iscurrent').val(0).trigger('change');
                $('#iscurrent').closest('.form-group').show();
            }


            _load_requisition_list(data);
            $('#claimed').show();
            $('.claimed').show();
            $('#claimed').attr('checked', 'checked');
            $('#claimed_info').html('<h4>Total: ' + $('#info_4').text() + '<br>Claimed: ' + $('#info_5').text() + '</h4>');
            $('#dpr').show();
            $('.dpr').hide();
            $('#dpr').prop('checked', false);
            $('#dpr').val(0);
            var dpr = $('#info_9').text().split('/');
            $('#dpr_info').html('<h4>Total: ' + dpr[1] + '<br>Claimed: ' + dpr[0] + '</h4>');
            if (parseInt(dpr[1]) - parseInt(dpr[0]) <= 0) {
                $('#dpr').hide();
            }
            $('#contigency').show();
            $('.contigency').hide();
            $('#contigency').prop('checked', false);
            $('#contigency').val(0);
            var contigency = $('#info_10').text().split('/');
            $('#contigency_info').html('<h4>Total: ' + contigency[1] + '<br>Claimed: ' + contigency[0] + '</h4>');
            if (parseInt(contigency[1]) - parseInt(contigency[0]) <= 0) {
                $('#contigency').hide();
            }
        } else {
            $('#ra_id').html('RA - ' + 1);
            $('#hdn_ra_id').val(1);
            $('#prev').hide();
            $('#claimed').show();
            $('.claimed').show();
            $('#claimed').attr('checked', 'checked');
            $('#claimed_info').html('<h4>Total: ' + $('#info_4').text() + '<br>Claimed: ' + $('#info_5').text() + '</h4>');
            $('#dpr').show();
            $('.dpr').show();
            $('#dpr').prop('checked', true);
            $('#dpr').val(1);
            var dpr = $('#info_9').text().split('/');
            $('#dpr_info').html('<h4>Total: ' + dpr[1] + '<br>Claimed: ' + dpr[0] + '</h4>');
            $('#contigency').show();
            $('.contigency').show();
            $('#contigency').prop('checked', true);
            $('#contigency').val(1);
            var contigency = $('#info_10').text().split('/');
            $('#contigency_info').html('<h4>Total: ' + contigency[1] + '<br>Claimed: ' + contigency[0] + '</h4>');
        }
    });
}

function _load_requisition_list(data) {
    var currentdate = new Date();
    $('#tbl').dataTable().fnDestroy();
    $('#tbl').DataTable({
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
            right: 2
        },
        columnDefs: [
            { targets: 0, data: 'ra_id' },
            { targets: 1, data: 'memo_no' },
            { targets: 2, data: 'memo_date' },
            { targets: 3, data: 'ref_no' },
            { targets: 4, data: 'physical_progress' },
            { targets: 5, data: 'claimed_amt' },
            { targets: 6, data: 'dpr_amt' },
            { targets: 7, data: 'contigency_amt' },
            { targets: 8, data: 'approved_date' },
            { targets: 9, data: 'approved_claimed_amt' },
            { targets: 10, data: 'approved_dpr_amt' },
            { targets: 11, data: 'approved_contigency_amt' },
            {
                targets: 12,
                data: null,
                render: function (data, type, full, meta) {
                    var expnd = role_id < 3 && (full.claimed_expenditure_amt === '0.00') ? '&nbsp;&nbsp;<i class="fa fa-edit pointer" onclick="_expenditure(' + full.id + ', \'claimed\')"></i>' : '';
                    return '<span id="expnd_claimed_' + full.id + '">' + (full.claimed_expenditure_amt === null ? '' : full.claimed_expenditure_amt) + '</span>' + expnd;
                }
            },
            {
                targets: 13,
                data: null,
                render: function (data, type, full, meta) {
                    var expnd =role_id < 3 && (full.dpr_expenditure_amt === '0.00') ? '&nbsp;&nbsp;<i class="fa fa-edit pointer" onclick="_expenditure(' + full.id + ', \'dpr\')"></i>' : '';
                    return '<span id="expnd_dpr_' + full.id + '">' + (full.dpr_expenditure_amt === null ? '' : full.dpr_expenditure_amt) + '</span>' + expnd;
                }
            },
            {
                targets: 14,
                data: null,
                render: function (data, type, full, meta) {
                    var expnd = role_id < 3 && (full.contigency_expenditure_amt === '0.00') ? '&nbsp;&nbsp;<i class="fa fa-edit pointer" onclick="_expenditure(' + full.id + ', \'contigency\')"></i>' : '';
                    return '<span id="expnd_contigency_' + full.id + '">' + (full.contigency_expenditure_amt === null ? '' : full.contigency_expenditure_amt) + '</span>' + expnd;
                }
            },

            {
                targets: 15,
                data: null,
                render: function (data, type, full, meta) {
                    return full.claimed_doc.length > 0 ? '<a href="' + baseURL + '/' + full.claimed_doc + '" target="_blank"><i class="fas fa-file-pdf fa-2x pointer" style="color: red;"></i></a>' : '';
                }
            },
            {
                targets: 16,
                data: null,
                render: function (data, type, full, meta) {
                    return full.approved_doc.length > 0 ? '<a href="' + baseURL + '/' + full.approved_doc + '" target="_blank"><i class="fas fa-file-pdf fa-2x pointer" style="color: red;"></i></a>' : '';
                }
            }
        ],
        buttons: [{
            extend: 'excel',
            text: 'Excel',
            filename: 'ridf_requisition_' + $('#ridf_id').text() + '_' + $.now(),
            title: 'RIDF REQUISITION ' + String(currentdate.getDate()).padStart(2, '0') + '/' + String((currentdate.getMonth() + 1)).padStart(2, '0') + '/' +
                currentdate.getFullYear() + ' ' + String(currentdate.getHours()).padStart(2, '0') + ':' + String(currentdate.getMinutes()).padStart(2, '0'),
            footer: true,
            exportOptions: {
                columns: ':not(.not-export)'
            },
            customize: function (xlsx) {
                var sheet = xlsx.xl.worksheets['sheet1.xml'];
                $('row c', sheet).attr('s', '25');
            }
        }
        ]
    });
    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
    });
}

// function _expenditure(id) {
//     var cost = prompt('Give your expenditure cost:');
//     if (isNaN(cost)) {
//         alert('You must input numbers');
//     } else {
//         $.ajax({
//             url: baseURL + '/ridf/expenditure_save',
//             type: 'get',
//             data: { id: id, expenditure_amt: cost },
//             dataType: 'json',
//             async: false,
//         }).done(function (data) {
//             $('#expnd_' + id).html(cost);
//         });
//     }
// }





function _expenditure(id, type) {
    var cost = prompt('Give your expenditure cost:');
    if (isNaN(cost)) {
        alert('You must input numbers');
        return;
    }

    var payload = { id: id };
    if (type === 'claimed') payload.claimed_expenditure_amt = cost;
    else if (type === 'dpr') payload.dpr_expenditure_amt = cost;
    else if (type === 'contigency') payload.contigency_expenditure_amt = cost;

    $.ajax({
        url: baseURL + '/ridf/expenditure_save',
        type: 'get',
        data: payload,
        dataType: 'json',
        success: function (res) {
            if (res.status === 'success') {
                $('#expnd_' + type + '_' + id).html(cost);
            } else {
                alert('No update made.');
            }
        }
    });
}

