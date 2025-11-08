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
        url: baseURL + '/roads/create_lot_no',
        type: 'post',
        data: formData,
        dataType: 'json',
        processData: false,
        contentType: false,
        async: false,
      }).done(function (data) {
        alert('Your lot no is ' + data);
        get_survey_pending_list();
        setTimeout(function () {
          location.reload();
        }, 500);
      });
    } else {
      // alert('Please choose scheme.');
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
      url: baseURL + '/roads/forwarded',
      type: 'post',
      data: formData,
      dataType: 'json',
      processData: false,
      contentType: false,
      async: false,
    }).done(function (data) {
      alert('Successfully forwarded');
      window.location.href = baseURL + '/roads/lot';
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
        url: baseURL + '/roads/create_lot_no',
        type: 'post',
        data: formData,
        dataType: 'json',
        processData: false,
        contentType: false,
        async: false,
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
        url: baseURL + '/roads/admin_approval',
        type: 'post',
        data: formData,
        dataType: 'json',
        processData: false,
        contentType: false,
        async: false,
      }).done(function (data) {
        alert('saved successfully');
        window.location.href = baseURL + '/roads/lot';
      });
    } else {
      alert('Please choose lot no.');
    }
  });
  $('#backward').on('click', function (e) {
    e.preventDefault();
    var msg = prompt(
      'Do you want to return the selected scheme(s) to previous level?',
      ''
    );
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
          url: baseURL + '/roads/return_to_prev',
          type: 'post',
          data: {
            district_id: $('#district_id').val(),
            block_id: $('#block_id').val(),
            arr: arr,
            msg: msg,
          },
          dataType: 'json',
          async: false,
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
    url: baseURL + '/roads/get_lotno_list',
    type: 'get',
    data: { district_id: $('#district_id').val() },
    dataType: 'json',
    async: false,
  }).done(function (data) {
    $('#lotno').empty();
    if (data.length > 0) {
      $('#lotno').append(
        $('<option>', { value: '0', text: '--Select Lot No--' })
      );
      $.each(data, function (i, item) {
        $('#lotno').append(
          $('<option>', { value: item.lotno, text: item.lotno })
        );
      });
    }
  });
}
function get_block_list() {
  $.ajax({
    url: baseURL + '/roads/get_block_list',
    type: 'get',
    data: { district_id: $('#district_id').val() },
    dataType: 'json',
    async: false,
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

function get_assembly_list() {
  $.ajax({
    url: baseURL + '/roads/get_assembly_list',
    type: 'get',
    data: { district_id: $('#district_id').val() },
    dataType: 'json',
    async: false,
  }).done(function (data) {
    console.log(data);
    $('#ac_id').empty();
    if (data.length > 0) {
      $('#ac_id').append(
        $('<option>', { value: '0', text: '--All Assembly--' })
      );
      $.each(data, function (i, item) {
        $('#ac_id').append($('<option>', { value: item.id, text: item.name }));
      });
    }
  });
}
function get_gp_list() {
  console.log('block_id: ' + $('#block_id').val());
  $.ajax({
    url: baseURL + '/roads/get_gp_list',
    type: 'get',
    data: { block_id: $('#block_id').val() },
    dataType: 'json',
    async: false,
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
function get_survey_list() {
  if ($('#district_id').val() > 0) {
    $.ajax({
      url: baseURL + '/roads/get_survey_list',
      type: 'get',
      data: {
        district_id: $('#district_id').val(),
        status: $('input[name="status"]:checked').val(),
      },
      dataType: 'json',
      async: false,
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
      right: 2,
    },
    buttons: [
      {
        extend: 'excel',
        text: 'Excel',
        filename: 'rural_roads(2025)_master_' + $.now(),
        title:
          'RURAL ROADS(2025) MASTER ON ' +
          String(currentdate.getDate()).padStart(2, '0') +
          '/' +
          String(currentdate.getMonth() + 1).padStart(2, '0') +
          '/' +
          currentdate.getFullYear() +
          ' ' +
          String(currentdate.getHours()).padStart(2, '0') +
          ':' +
          String(currentdate.getMinutes()).padStart(2, '0'),
        footer: true,
        exportOptions: {
          columns: ':not(.not-export)',
        },
        customize: function (xlsx) {
          var sheet = xlsx.xl.worksheets['sheet1.xml'];
          $('row c', sheet).attr('s', '25');
        },
      },
      {
        extend: 'print',
        text: 'Print',
        title: 'RURAL ROADS(2025) MASTER',
        footer: true,
        exportOptions: {
          columns: ':not(.not-export)',
        },
        customize: function (win) {
          $(win.document.body)
            .find('h1')
            .css('text-align', 'center')
            .css('font-size', '10pt')
            .prepend(
              '<img src="' +
                baseURL +
                '/templates/img/pathashree.jpg" style="position:absolute; top:0; left:0;" />'
            );
          $(win.document.body)
            .find('table')
            .addClass('compact')
            .css('font-size', 'inherit')
            .css('margin', '50px auto');
        },
      },
    ],
    columnDefs: [
      {
        targets: 0,
        defaultContent: '',
        render: function (data, type, full, meta) {
          return i++;
        },
      },
      {
        targets: 1,
        defaultContent: '',
        render: function (data, type, full, meta) {
          return (
            '<p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' +
            full.name +
            '">' +
            full.name +
            '</p>'
          );
        },
      },
      { targets: 2, data: 'ac' },
      { targets: 3, data: 'district' },

      { targets: 4, data: 'block' },
      {
        targets: 5,
        defaultContent: '',
        render: function (data, type, full, meta) {
          return full.gp;
        },
      },
      { targets: 6, data: 'ref_no' },
      { targets: 7, data: 'agency' },
      // { targets: 8, data: 'proposed_length' },
      {
        targets: 8,
        data: 'proposed_length',
        defaultContent: '',
        render: function (data) {
          return data;
        },
        createdCell: function (td, cellData, rowData) {
          if (role_id < 3) {
            $(td).dblclick(function () {
              const currentValue = $(this).text();
              const rowId = rowData.id;
              $(this).html(
                `<input type="text" value="${currentValue}" style="width: 100%;">`
              );
              const input = $(this).find('input');
              input.focus();
              input.on('blur keyup', function (e) {
                if (e.type === 'blur' || e.key === 'Enter') {
                  const newValue = $(this).val();
                  $(td).text(newValue);
                  $.post(baseURL + '/roads/update_proposed_length', {
                    id: rowId,
                    proposed_length: newValue,
                  })
                    .done(() => console.log('Updated successfully'))
                    .fail((xhr, status, error) => {
                      alert('Failed to update: ' + error);
                      $(td).text(currentValue);
                    });
                }
              });
            });
          }
        },
      },
      { targets: 9, data: 'length' },
      { targets: 10, data: 'work_type' },
      { targets: 11, data: 'road_type' },
      {
        targets: 12,
        data: 'bt_length',
        render: function (data) {
          return parseFloat(data).toFixed(3) || 0;
        },
      },
      {
        targets: 13,
        data: 'cc_length',
        render: function (data) {
          return parseFloat(data).toFixed(3) || 0;
        },
      },
      {
        targets: 14,
        data: null,
        render: function (data, type, row) {
          const bt = parseFloat(row.bt_length) || 0;
          const cc = parseFloat(row.cc_length) || 0;
          return (bt + cc).toFixed(3);
        },
      },
      { targets: 15, data: 'new_road_type' },
      { targets: 16, data: 'new_length' },
      {
        targets: 17,
        defaultContent: '',
        render: function (data, type, full, meta) {
          return (
            '<div style="text-align:right; font-weight:bold;">' +
            parseFloat(full.cost || 0).toFixed(2) +
            '</div>'
          );
        },
      },
      {
        targets: 18,
        defaultContent: '',
        render: function (data, type, full, meta) {
          return (
            '<div style="text-align:right; font-weight:bold;">' +
            parseFloat(full.gst || 0).toFixed(2) +
            '</div>'
          );
        },
      },
      {
        targets: 19,
        defaultContent: '',
        render: function (data, type, full, meta) {
          return (
            '<div style="text-align:right; font-weight:bold;">' +
            parseFloat(full.cess || 0).toFixed(2) +
            '</div>'
          );
        },
      },
      {
        targets: 20,
        data: null,
        render: function (data, type, row) {
          let vetted = parseFloat(row.cost) || 0;
          let gst = parseFloat(row.gst) || 0;
          let cess = parseFloat(row.cess) || 0;
          let total_estimated = vetted + gst + cess;
          return (
            '<div style="text-align:right; font-weight:bold;">' +
            total_estimated.toFixed(2) +
            '</div>'
          );
        },
      },
      {
        targets: 21,
        data: null,
        render: function (data, type, row) {
          // Safely parse numeric values
          let cost = parseFloat(row.cost) || 0;
          let gst = parseFloat(row.gst) || 0;
          let cess = parseFloat(row.cess) || 0;
          let bt = parseFloat(row.bt_length) || 0;
          let cc = parseFloat(row.cc_length) || 0;
          let length = bt + cc || 1; // avoid division by 0

          // Calculate estimated value in lakh per length
          let estimated = cost + gst + cess;
          let valueInLakh = estimated / length / 100000;

          // Return formatted value aligned right with 2 decimals
          return (
            '<div style="text-align:right; font-weight:bold;">' +
            valueInLakh.toFixed(3) +
            '</div>'
          );
        },
      },
      {
        targets: 22,
        defaultContent: '',
        render: function (data, type, full, meta) {
          return (
            '<div style="text-align:right; font-weight:bold;">' +
            parseFloat(full.contigency_amt || 0).toFixed(2) +
            '</div>'
          );
        },
      },
      {
        targets: 23,
        defaultContent: '',
        render: function (data, type, full, meta) {
          return (
            '<div style="text-align:right; font-weight:bold;">' +
            parseFloat(full.estimated_amt || 0).toFixed(2) +
            '</div>'
          );
        },
      },

      {
        targets: 24,
        data: null,
        render: function (data, type, row, meta) {
          var html =
            '<div style="display:inline-block; text-align:center; min-width:120px;">';

          if (row.survey_estimated_doc) {
            html +=
              '<a href="' +
              row.survey_estimated_doc +
              '" target="_blank" ' +
              'style="display:inline-block; margin-top:5px; padding:5px 10px; font-size:12px; font-weight:500; color:#fff; background:#4a90e2; border-radius:6px; text-decoration:none; transition:0.3s;">' +
              '<i class="fa fa-file-alt"></i> View Doc' +
              '</a>';
          }

          html += '</div>';
          return html;
        },
      },
      {
        targets: 25,
        data: null,
        render: function (data, type, row, meta) {
          var lotNo = row.survey_lot_no ? row.survey_lot_no : 'N/A';
          var html =
            '<div style="display:inline-block; text-align:center; min-width:120px;">';

          if (lotNo) {
            html +=
              '<div style="font-size:14px; font-weight:600; color:#333;">Lot No: ' +
              lotNo +
              '</div>';
          }

          if (row.survey_lot_doc) {
            html +=
              '<a href="' +
              row.survey_lot_doc +
              '" target="_blank" ' +
              'style="display:inline-block; margin-top:5px; padding:5px 10px; font-size:12px; font-weight:500; color:#fff; background:#4a90e2; border-radius:6px; text-decoration:none; transition:0.3s;">' +
              '<i class="fa fa-file-alt"></i> View Doc' +
              '</a>';
          }

          html += '</div>';
          return html;
        },
      },
      {
        targets: 26,
        data: null,
        render: function (data, type, row, meta) {
          var lotNo = row.dm_lot_no ? row.dm_lot_no : 'N/A';
          var html =
            '<div style="display:inline-block; text-align:center; min-width:120px;">';

          if (lotNo) {
            html +=
              '<div style="font-size:14px; font-weight:600; color:#333;">Lot No: ' +
              lotNo +
              '</div>';
          }

          if ([1, 2, 3, 7, 12].includes(role_id) && row.dm_lot_doc) {
            html +=
              '<a href="' +
              row.dm_lot_doc +
              '" target="_blank" ' +
              'style="display:inline-block; margin-top:5px; padding:5px 10px; font-size:12px; font-weight:500; color:#fff; background:#4a90e2; border-radius:6px; text-decoration:none; transition:0.3s;">' +
              '<i class="fa fa-file-alt"></i> View Doc' +
              '</a>';
          }

          html += '</div>';
          return html;
        },
      },
      {
        targets: 27,
        data: null,
        render: function (data, type, row, meta) {
          var lotNo = row.se_lot_no ? row.se_lot_no : 'N/A';
          var html =
            '<div style="display:inline-block; text-align:center; min-width:120px;">';

          if (lotNo) {
            html +=
              '<div style="font-size:14px; font-weight:600; color:#333;">Lot No: ' +
              lotNo +
              '</div>';
          }

          if ([1, 2, 3, 7].includes(role_id) && row.se_lot_doc) {
            html +=
              '<a href="' +
              row.se_lot_doc +
              '" target="_blank" ' +
              'style="display:inline-block; margin-top:5px; padding:5px 10px; font-size:12px; font-weight:500; color:#fff; background:#4a90e2; border-radius:6px; text-decoration:none; transition:0.3s;">' +
              '<i class="fa fa-file-alt"></i> View Doc' +
              '</a>';
          }

          html += '</div>';
          return html;
        },
      },
      {
        targets: 28,
        data: null,
        render: function (data, type, row, meta) {
          var lotNo = row.sa_lot_no ? row.sa_lot_no : 'N/A';
          var html =
            '<div style="display:inline-block; text-align:center; min-width:120px;">';

          if (lotNo) {
            html +=
              '<div style="font-size:14px; font-weight:600; color:#333;">Lot No: ' +
              lotNo +
              '</div>';
          }

          if ([1, 2, 3].includes(role_id) && row.admin_approval_doc) {
            html +=
              '<a href="' +
              row.admin_approval_doc +
              '" target="_blank" ' +
              'style="display:inline-block; margin-top:5px; padding:5px 10px; font-size:12px; font-weight:500; color:#fff; background:#4a90e2; border-radius:6px; text-decoration:none; transition:0.3s;">' +
              '<i class="fa fa-file-alt"></i> View Doc' +
              '</a>';
          }

          html += '</div>';
          return html;
        },
      },
      {
        targets: 29,
        data: 'tender_status',
        className: 'text-center',
        render: function (data, type, row, meta) {
          let label = '';
          switch (data) {
            case '0':
              label =
                '<span style="padding:4px 8px; font-size:12px; font-weight:600; color:#fff; background:#e74c3c; border-radius:6px;">Not Started</span>';
              break;
            case '1':
              label =
                '<span style="padding:4px 8px; font-size:12px; font-weight:600; color:#fff; background:#f39c12; border-radius:6px;">On Progress</span>';
              break;
            case '2':
              label =
                '<span style="padding:4px 8px; font-size:12px; font-weight:600; color:#fff; background:#27ae60; border-radius:6px;">Completed</span>';
              break;
            default:
              label =
                '<span style="padding:4px 8px; font-size:12px; font-weight:600; color:#7f8c8d; background:#ecf0f1; border-radius:6px;">N/A</span>';
          }
          return label;
        },
      },
      {
        targets: 30,
        data: 'wo_status',
        className: 'text-center',
        render: function (data, type, row, meta) {
          let label = '';
          switch (data) {
            case '0':
              label =
                '<span style="padding:4px 8px; font-size:12px; font-weight:600; color:#fff; background:#e74c3c; border-radius:6px;">Not Started</span>';
              break;
            case '1':
              label =
                '<span style="padding:4px 8px; font-size:12px; font-weight:600; color:#fff; background:#f39c12; border-radius:6px;">On Progress</span>';
              break;
            case '2':
              label =
                '<span style="padding:4px 8px; font-size:12px; font-weight:600; color:#fff; background:#27ae60; border-radius:6px;">Completed</span>';
              break;
            default:
              label =
                '<span style="padding:4px 8px; font-size:12px; font-weight:600; color:#7f8c8d; background:#ecf0f1; border-radius:6px;">N/A</span>';
          }
          return label;
        },
      },
      {
        targets: 31,
        data: 'id',
        className: 'text-center',
        render: function (data, type, row, meta) {
          if (row.pp_status > 0) {
            return (
              '<span onclick="wp_image_view(' +
              data +
              ')" ' +
              'style="cursor:pointer; padding:6px 14px; font-size:12px; font-weight:600; ' +
              'color:#fff; background:linear-gradient(135deg,#4a90e2,#357ABD); ' +
              'border-radius:30px; display:inline-flex; align-items:center; gap:6px; ' +
              'box-shadow:0 3px 6px rgba(0,0,0,0.2); transition:all 0.3s ease;" ' +
              'onmouseover="this.style.transform=\'scale(1.05)\'" ' +
              'onmouseout="this.style.transform=\'scale(1)\'">' +
              '<i class="fa fa-image"></i> View Images</span>'
            );
          } else {
            return (
              '<span style="padding:6px 14px; font-size:12px; font-weight:600; ' +
              'color:#fff; background:linear-gradient(135deg,#95a5a6,#7f8c8d); ' +
              'border-radius:30px; display:inline-flex; align-items:center; gap:6px; ' +
              'box-shadow:0 3px 6px rgba(0,0,0,0.15);">' +
              '<i class="fa fa-ban"></i> Not Started</span>'
            );
          }
        },
      },
      {
        targets: 32,
        defaultContent: '',
        render: function (data, type, full, meta) {
          var status =
            full.isactive === '-1'
              ? '<span class="badge btn-danger">Not Implemented</span>'
              : '';
          if (full.isactive === '1') {
            status =
              full.survey_status === '6'
                ? '<span class="badge btn-success">Approved</span>'
                : full.survey_status === '5'
                ? '<span class="badge btn-info">State Admin Level </span>'
                : full.survey_status === '4'
                ? '<span class="badge btn-info">SE Level </span>'
                : full.survey_status === '3'
                ? '<span class="badge btn-info">DM Level </span>'
                : full.survey_status === '2'
                ? '<span class="badge btn-info">Survey Completed</span>'
                : full.survey_status === '1'
                ? '<span class="badge btn-info">Ongoing Survey</span>'
                : full.survey_status === '0'
                ? '<span class="badge btn-warning">Survey Not Started</span>'
                : '';
          }
          return '<div style="text-align:center;">' + status + '</div>';
        },
      },

      {
        targets: 33,
        defaultContent: '',
        render: function (data, type, full, meta) {
          if ($('input[name="status"]:checked').val() > -1) {
            var editBtn = '';
            if (!(full.survey_status >= 2 && role_id > 3)) {
              editBtn =
                '<button class="btn btn-icon btn-round btn-sm btn-primary" onclick="add(' +
                full.id +
                ')" title="Edit"><i class="fas fa-pen pointer"></i></button>';
            }

            // Other buttons with conditions
            var not_implemented =
              role_id < 3
                ? '&nbsp;<button title="Mark scheme as not implemented" class="btn btn-icon btn-round btn-sm btn-danger" onclick="mark_not_traceable(' +
                  full.id +
                  ')"><i class="fas fa-minus-circle"></i></button>'
                : '';

            var sent_to_inbox =
              role_id < 3 &&
              full.survey_status == 0 &&
              full.survey_lot_no &&
              full.survey_lot_no.length > 0 &&
              !full.dm_lot_no &&
              !full.se_lot_no
                ? '&nbsp;<button title="Back to scheme inbox" class="btn btn-icon btn-round btn-sm btn-warning" onclick="back_to_inbox(' +
                  full.id +
                  ')"><i class="fa fa-undo"></i></button>'
                : '';

            return editBtn + not_implemented + sent_to_inbox;
          } else {
            return (
              '<p class="truncate_text" data-toggle="tooltip" title="' +
              full.remarks +
              '">' +
              full.remarks +
              '</p>'
            );
          }
        },
      },
    ],
  });
}

function wp_image_view(id) {
  // replace base_url with your actual CI base url helper output
  window.open(baseURL + '/roads/wp_image_view/' + id, '_blank');
}

function get_rpt_qm_list() {
  $.ajax({
    url: baseURL + '/roads/get_rpt_qm_list',
    type: 'get',
    data: {
      start_date: $('#start_date').val(),
      end_date: $('#end_date').val(),
    },
    dataType: 'json',
    async: false,
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
        $('#' + 'grand_total_' + old_id).text(
          parseInt(s_total + sri_total + u_total)
        );
        total = 0;
        s_total = 0;
        sri_total = 0;
        u_total = 0;
      } else if (old_agency != new_agency) {
        //console.log('agency: ' + total);
        $('#' + old_agency.toLowerCase() + '_total_' + old_id).text(total);
        total = 0;
      }
      $(
        '#' +
          item.agency.toLowerCase() +
          '_' +
          item.overall_grade.toLowerCase() +
          '_' +
          item.id
      ).text(item.cnt);
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
    $('#' + 'grand_total_' + old_id).text(
      parseInt(s_total + sri_total + u_total)
    );
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
      url: baseURL + '/roads/remove_survey_list',
      type: 'get',
      data: {
        id: id,
        district_id: $('#district_id').val(),
        block_id: $('#block_id').val(),
      },
      dataType: 'json',
      async: false,
    }).done(function (data) {
      _load_survey_list(data);
    });
  }
}
function add(id) {
  window.location.href = baseURL + '/roads/entry/' + id;
}
function get_survey_pending_list() {
  if ($('#district_id').val() > 0 && $('#block_id').val() > 0) {
    $.ajax({
      url: baseURL + '/roads/get_survey_pending_list',
      type: 'get',
      data: {
        district_id: $('#district_id').val(),
        block_id: $('#block_id').val(),
        ac_id: $('#ac_id').val(),
      },
      dataType: 'json',
      async: false,
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
      left: 2,
      right: 2,
    },
    buttons: [
      {
        extend: 'excel',
        text: 'Excel',
        filename: 'survey_pending_list_' + $.now(),
        title:
          'SURVEY PENDING LIST ON ' +
          String(currentdate.getDate()).padStart(2, '0') +
          '/' +
          String(currentdate.getMonth() + 1).padStart(2, '0') +
          '/' +
          currentdate.getFullYear() +
          ' ' +
          String(currentdate.getHours()).padStart(2, '0') +
          ':' +
          String(currentdate.getMinutes()).padStart(2, '0'),
        footer: true,
        exportOptions: {
          columns: ':not(.not-export)',
        },
        customize: function (xlsx) {
          var sheet = xlsx.xl.worksheets['sheet1.xml'];
          $('row c', sheet).attr('s', '25');
        },
      },
      {
        extend: 'print',
        text: 'Print',
        title: 'SURVEY PENDING LIST',
        footer: true,
        exportOptions: {
          columns: ':not(.not-export)',
        },
        customize: function (win) {
          $(win.document.body)
            .find('h1')
            .css('text-align', 'center')
            .css('font-size', '10pt')
            .prepend(
              '<img src="' +
                baseURL +
                '/templates/img/pathashree.jpg" style="position:absolute; top:0; left:0;" />'
            );
          $(win.document.body)
            .find('table')
            .addClass('compact')
            .css('font-size', 'inherit')
            .css('margin', '50px auto');
        },
      },
    ],
    columnDefs: [
      {
        targets: 0,
        defaultContent: '',
        render: function (data, type, full, meta) {
          var disabled =
            parseFloat(full.cost) > 0 && parseInt(full.status) > 1
              ? ''
              : 'disabled';
          return (
            '<input type="checkbox" name="chk[' +
            full.id +
            ']" id="chk_' +
            full.id +
            '" class="chk" value="" ' +
            disabled +
            '>'
          );
        },
      },
      { targets: 1, data: 'ac' },
      { targets: 2, data: 'district' },
      { targets: 3, data: 'block' },
      { targets: 4, data: 'gp' },
      { targets: 5, data: 'ref_no' },
      { targets: 6, data: 'agency' },
      {
        targets: 7,
        defaultContent: '',
        render: function (data, type, full, meta) {
          return (
            '<p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' +
            full.name +
            '">' +
            full.name +
            '</p>'
          );
        },
      },
      { targets: 8, data: 'proposed_length' },
      { targets: 9, data: 'length' },

      { targets: 10, data: 'road_type' },
      { targets: 11, data: 'work_type' },
      {
        targets: 12,
        data: 'bt_length',
        render: function (data) {
          return parseFloat(data).toFixed(3) || 0;
        },
      },
      {
        targets: 13,
        data: 'cc_length',
        render: function (data) {
          return parseFloat(data).toFixed(3) || 0;
        },
      },
      {
        targets: 14,
        data: null,
        render: function (data, type, row) {
          const bt = parseFloat(row.bt_length) || 0;
          const cc = parseFloat(row.cc_length) || 0;
          return (bt + cc).toFixed(3);
        },
      },
      { targets: 15, data: 'new_road_type' },
      { targets: 16, data: 'new_length' },
      {
        targets: 17,
        defaultContent: '',
        render: function (data, type, full, meta) {
          return full.status === '0'
            ? 'Not Started'
            : full.status === '1'
            ? 'On Going'
            : 'Completed';
        },
      },
      {
        targets: 18,
        defaultContent: '',
        render: function (data, type, full, meta) {
          var vetted = parseFloat(full.cost || 0).toFixed(2);
          var editIcon =
            full.status < 2
              ? ''
              : ' <i class="fa fa-edit pointer text-primary edit-vetted" data-id="' +
                full.id +
                '"></i>';
          return (
            '<span id="cost_' +
            full.id +
            '" class="fw-bold">' +
            vetted +
            '</span>' +
            editIcon
          );
        },
      },
      {
        targets: 19,
        defaultContent: '',
        render: function (data, type, full, meta) {
          var gst = parseFloat(full.gst || 0).toFixed(2);
          return (
            '<span id="gst_' + full.id + '" class="fw-bold">' + gst + '</span>'
          );
        },
      },
      {
        targets: 20,
        defaultContent: '',
        render: function (data, type, full, meta) {
          var cess = parseFloat(full.cess || 0).toFixed(2);
          return (
            '<span id="cess_' +
            full.id +
            '" class="fw-bold">' +
            cess +
            '</span>'
          );
        },
      },
      {
        targets: 21,
        data: null,
        render: function (data, type, row) {
          let vetted = parseFloat(row.cost) || 0;
          let gst = parseFloat(row.gst) || 0;
          let cess = parseFloat(row.cess) || 0;
          let total_estimated = vetted + gst + cess;
          return (
            '<span id="total_' +
            row.id +
            '" class="fw-bold">' +
            total_estimated.toFixed(2) +
            '</span>'
          );
        },
      },
      {
        targets: 22,
        data: null,
        render: function (data, type, row) {
          // Safely parse numeric values
          let cost = parseFloat(row.cost) || 0;
          let gst = parseFloat(row.gst) || 0;
          let cess = parseFloat(row.cess) || 0;
          let length = parseFloat(row.length) || 1; // avoid division by 0

          // Calculate estimated value in lakh per length
          let estimated = cost + gst + cess;
          let valueInLakh = estimated / length / 100000;

          // Return formatted value aligned right
          return (
            '<div style="text-align:right; font-weight:bold;">' +
            valueInLakh.toFixed(2) +
            '</div>'
          );
        },
      },
      {
        targets: 23,
        defaultContent: '',
        render: function (data, type, full, meta) {
          let contigency = parseFloat(full.contigency_amt || 0).toFixed(2);
          return (
            '<span id="contigency_' +
            full.id +
            '" class="fw-bold">' +
            contigency +
            '</span>'
          );
        },
      },
      {
        targets: 24,
        defaultContent: '',
        render: function (data, type, full, meta) {
          var estimated = parseFloat(full.estimated_amt || 0).toFixed(2);
          return (
            '<span id="estimated_' +
            full.id +
            '" class="fw-bold">' +
            estimated +
            '</span>'
          );
        },
      },

      { targets: 25, data: 'return_cause' },
      {
        targets: 26,
        defaultContent: '',
        render: function (data, type, full, meta) {
          var document =
            full.survey_estimated_doc !== null &&
            full.survey_estimated_doc.length > 0
              ? '<button class="btn btn-icon btn-round btn-sm btn-primary" onclick="_servey_document(\'' +
                baseURL +
                '/' +
                full.survey_estimated_doc +
                '\')"  title="Document"><i class="fas fa-file-pdf"></i></button>'
              : '';
          return document;
        },
      },
      {
        targets: 27,
        defaultContent: '',
        render: function (data, type, full, meta) {
          // Disable edit button if status == 2
          var disabled =
            full.status == 2 ? 'disabled style="pointer-events:none;"' : '';

          return (
            '<p style="margin:0; width: 90px;" ondblclick="enableEdit(' +
            full.id +
            ')">' +
            '<button id="editBtn_' +
            full.id +
            '" ' +
            'class="btn btn-icon btn-round btn-sm btn-primary" ' +
            'onclick="add_survey(' +
            full.id +
            ')" ' +
            'title="Edit" ' +
            disabled +
            '>' +
            '<i class="fas fa-plus pointer"></i>' +
            '</button>&nbsp;' +
            '<button data-toggle="tooltip" data-placement="bottom" ' +
            'title="Mark Scheme Not Implemented" ' +
            'class="btn btn-danger btn-icon btn-round btn-sm" ' +
            'onclick="mark_not_traceable(' +
            full.id +
            ')">' +
            '<i class="fas fa-minus-circle"></i>' +
            '</button>' +
            '</p>'
          );
        },
      },
    ],
  });
}
function enableEdit(id) {
  const btn = document.getElementById('editBtn_' + id);
  if (btn && btn.disabled) {
    btn.disabled = false; // enable button
    btn.style.pointerEvents = 'auto'; // restore click
    alert('Edit button enabled!'); // optional feedback
  }
}
function _servey_document(url) {
  window.open(url, '_blank');
}

function back_to_inbox(id) {
  window.location.href = baseURL + '/roads/back_to_inbox/' + id;
}

function mark_not_traceable(id) {
  window.location.href = baseURL + '/roads/not_traceable/' + id;
}

function add_survey(id) {
  window.location.href = baseURL + '/roads/survey_entry/' + id;
}

// --- Edit vetted (cost) ---
$(document).on('click', '.edit-vetted', function () {
  var id = $(this).data('id');
  editVetted(id);
});

function editVetted(id) {
  var vetted = prompt('Enter vetted estimated cost:');
  if (vetted === null) return;

  if (isNaN(vetted) || vetted.trim() === '') {
    alert('Please input a valid number.');
    return;
  }

  vetted = parseFloat(vetted);

  // Step 2: GST = cost * 18%
  var gst = vetted * 0.18;

  // Step 3: Cess = (cost + gst) * 1%
  var cess = (vetted + gst) * 0.01;

  // Step 4: Total estimated = cost + gst + cess
  var total_estimated = vetted + gst + cess;

  // Step 5: Contingency = (cost + gst) * 3%
  var contigency = (vetted + gst) * 0.03;

  // Step 6: Final estimated = total_estimated + contigency
  var estimated = total_estimated + contigency;

  // --- Update table fields ---
  $('#cost_' + id).text(vetted.toFixed(2));
  $('#gst_' + id).text(gst.toFixed(2));
  $('#cess_' + id).text(cess.toFixed(2));
  $('#total_' + id).text(total_estimated.toFixed(2));
  $('#contigency_' + id).text(contigency.toFixed(2));
  $('#estimated_' + id).text(estimated.toFixed(2));

  // --- Save to database via AJAX ---
  saveSurveyData(id, vetted, gst, cess, contigency, estimated);
}

// --- Common AJAX Save ---
function saveSurveyData(id, cost, gst, cess, contigency, estimated) {
  $.ajax({
    url: baseURL + '/roads/survey_vec_save',
    type: 'GET',
    data: {
      id: id,
      cost: cost.toFixed(2),
      gst: gst.toFixed(2),
      cess: cess.toFixed(2),
      contigency_amt: contigency.toFixed(2),
      estimated_amt: estimated.toFixed(2),
    },
    dataType: 'json',
    success: function (data) {
      $('#chk_' + id).prop('disabled', false);
      setTimeout(() => location.reload(), 300);
    },
    error: function () {
      alert('Error saving data to server.');
    },
  });
}

// Attach once, works for all future table redraws
// $(document).on('click', '.edit-vetted', function () {
//   var id = $(this).data('id');
//   editVetted(id);
// });

// function editVetted(id) {
//   var vetted = prompt('Enter vetted estimated cost:');
//   if (vetted === null) return;

//   if (isNaN(vetted) || vetted.trim() === '') {
//     alert('Please input a valid number.');
//     return;
//   }

//   vetted = parseFloat(vetted);
//   var contigency = vetted * 0.03;
//   var estimated = vetted + contigency;

//   $('#cost_' + id).text(vetted.toFixed(2));
//   $('#contigency_' + id).text(contigency.toFixed(2));
//   $('#estimated_' + id).text(estimated.toFixed(2));

//   $.ajax({
//     url: baseURL + '/roads/survey_vec_save',
//     type: 'GET',
//     data: {
//       id: id,
//       cost: vetted.toFixed(2),
//       contigency_amt: contigency.toFixed(2),
//       estimated_amt: estimated.toFixed(2),
//     },
//     dataType: 'json',
//     success: function (data) {
//       $('#chk_' + id).prop('disabled', false);
//       setTimeout(function () {
//         location.reload();
//       }, 300);
//     },
//     error: function () {
//       alert('Error saving data to server.');
//     },
//   });
// }

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
  window.open(
    baseURL +
      '/roads/print_lot/' +
      $('#district_id').val() +
      '/' +
      $('#lotno').val(),
    '_blank'
  );
}
function get_lot_list() {
  if ($('#district_id').val() > 0 && $('#lotno').val().length > 0) {
    $.ajax({
      url: baseURL + '/roads/get_lot_list',
      type: 'get',
      data: { district_id: $('#district_id').val(), lotno: $('#lotno').val() },
      dataType: 'json',
      async: false,
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
      right: 0,
    },
    buttons: [
      {
        extend: 'excel',
        text: 'Excel',
        filename: 'lot_' + $.now(),
        title:
          'LOT ON ' +
          String(currentdate.getDate()).padStart(2, '0') +
          '/' +
          String(currentdate.getMonth() + 1).padStart(2, '0') +
          '/' +
          currentdate.getFullYear() +
          ' ' +
          String(currentdate.getHours()).padStart(2, '0') +
          ':' +
          String(currentdate.getMinutes()).padStart(2, '0'),
        footer: true,
        exportOptions: {
          columns: ':not(.not-export)',
        },
        customize: function (xlsx) {
          var sheet = xlsx.xl.worksheets['sheet1.xml'];
          $('row c', sheet).attr('s', '25');
        },
      },
      {
        extend: 'print',
        text: 'Print',
        title: 'LOT',
        footer: true,
        exportOptions: {
          columns: ':not(.not-export)',
        },
        customize: function (win) {
          $(win.document.body)
            .find('h1')
            .css('text-align', 'center')
            .css('font-size', '10pt')
            .prepend(
              '<img src="' +
                baseURL +
                '/templates/img/pathashree.jpg" style="position:absolute; top:0; left:0;" />'
            );
          $(win.document.body)
            .find('table')
            .addClass('compact')
            .css('font-size', 'inherit')
            .css('margin', '50px auto');
        },
      },
    ],
    columnDefs: [
      {
        targets: 0,
        defaultContent: '',
        render: function (data, type, full, meta) {
          return i++;
        },
      },
      { targets: 1, data: 'district' },
      { targets: 2, data: 'ac' },
      { targets: 3, data: 'block' },
      { targets: 4, data: 'gp' },
      {
        targets: 5,
        defaultContent: '',
        render: function (data, type, full, meta) {
          return (
            '<p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' +
            full.name +
            '">' +
            full.name +
            '</p>'
          );
        },
      },
      { targets: 6, data: 'ref_no' },
      { targets: 7, data: 'agency' },
      { targets: 8, data: 'lotno' },

      { targets: 9, data: 'proposed_length' },
      { targets: 10, data: 'length' },
      { targets: 11, data: 'road_type' },
      { targets: 12, data: 'work_type' },
      {
        targets: 13,
        data: 'bt_length',
        render: function (data) {
          return parseFloat(data).toFixed(3) || 0;
        },
      },
      {
        targets: 14,
        data: 'cc_length',
        render: function (data) {
          return parseFloat(data).toFixed(3) || 0;
        },
      },
      {
        targets: 15,
        data: null,
        render: function (data, type, row) {
          const bt = parseFloat(row.bt_length) || 0;
          const cc = parseFloat(row.cc_length) || 0;
          return (bt + cc).toFixed(3);
        },
      },
      { targets: 16, data: 'new_road_type' },
      { targets: 17, data: 'new_length' },
      {
        targets: 18,
        defaultContent: '',
        render: function (data, type, full, meta) {
          return full.status === '0'
            ? 'Not Started'
            : full.status === '1'
            ? 'On Going'
            : 'Completed';
        },
      },
      {
        targets: 19,
        defaultContent: '',
        render: function (data, type, full, meta) {
          return (
            '<div style="text-align:right; font-weight:bold;">' +
            parseFloat(full.cost || 0).toFixed(2) +
            '</div>'
          );
        },
      },
      {
        targets: 20,
        defaultContent: '',
        render: function (data, type, full, meta) {
          return (
            '<div style="text-align:right; font-weight:bold;">' +
            parseFloat(full.gst || 0).toFixed(2) +
            '</div>'
          );
        },
      },
      {
        targets: 21,
        defaultContent: '',
        render: function (data, type, full, meta) {
          return (
            '<div style="text-align:right; font-weight:bold;">' +
            parseFloat(full.cess || 0).toFixed(2) +
            '</div>'
          );
        },
      },
      {
        targets: 22,
        data: null,
        render: function (data, type, row) {
          let vetted = parseFloat(row.cost) || 0;
          let gst = parseFloat(row.gst) || 0;
          let cess = parseFloat(row.cess) || 0;
          let total_estimated = vetted + gst + cess;
          return (
            '<div style="text-align:right; font-weight:bold;">' +
            total_estimated.toFixed(2) +
            '</div>'
          );
        },
      },

      {
        targets: 23,
        data: null,
        render: function (data, type, row) {
          // Safely parse numeric values
          let cost = parseFloat(row.cost) || 0;
          let gst = parseFloat(row.gst) || 0;
          let cess = parseFloat(row.cess) || 0;
          let bt = parseFloat(row.bt_length) || 0;
          let cc = parseFloat(row.cc_length) || 0;
          let length = bt + cc || 1;

          // Calculate estimated value in lakh per length
          let estimated = cost + gst + cess;
          let valueInLakh = estimated / length / 100000;

          return (
            '<div style="text-align:right; font-weight:bold;">' +
            valueInLakh.toFixed(3) +
            '</div>'
          );
        },
      },
      {
        targets: 24,
        defaultContent: '',
        render: function (data, type, full, meta) {
          return (
            '<div style="text-align:right; font-weight:bold;">' +
            parseFloat(full.contigency_amt || 0).toFixed(2) +
            '</div>'
          );
        },
      },
      {
        targets: 25,
        defaultContent: '',
        render: function (data, type, full, meta) {
          return (
            '<div style="text-align:right; font-weight:bold;">' +
            parseFloat(full.estimated_amt || 0).toFixed(2) +
            '</div>'
          );
        },
      },
    ],
  });
}

function get_approval_list() {
  if ($('#district_id').val() > 0) {
    $.ajax({
      url: baseURL + '/roads/get_approval_list',
      type: 'get',
      data: {
        district_id: $('#district_id').val(),
        block_id: $('#block_id').val(),
      },
      dataType: 'json',
      async: false,
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
      right: 2,
    },
    buttons: [
      {
        extend: 'excel',
        text: 'Excel',
        filename: 'lot_' + $.now(),
        title:
          'LOT ON ' +
          String(currentdate.getDate()).padStart(2, '0') +
          '/' +
          String(currentdate.getMonth() + 1).padStart(2, '0') +
          '/' +
          currentdate.getFullYear() +
          ' ' +
          String(currentdate.getHours()).padStart(2, '0') +
          ':' +
          String(currentdate.getMinutes()).padStart(2, '0'),
        footer: true,
        exportOptions: {
          columns: ':not(.not-export)',
        },
        customize: function (xlsx) {
          var sheet = xlsx.xl.worksheets['sheet1.xml'];
          $('row c', sheet).attr('s', '25');
        },
      },
      {
        extend: 'print',
        text: 'Print',
        title: 'LOT',
        footer: true,
        exportOptions: {
          columns: ':not(.not-export)',
        },
        customize: function (win) {
          $(win.document.body)
            .find('h1')
            .css('text-align', 'center')
            .css('font-size', '10pt')
            .prepend(
              '<img src="' +
                baseURL +
                '/templates/img/pathashree.jpg" style="position:absolute; top:0; left:0;" />'
            );
          $(win.document.body)
            .find('table')
            .addClass('compact')
            .css('font-size', 'inherit')
            .css('margin', '50px auto');
        },
      },
    ],
    columnDefs: [
      {
        targets: 0,
        defaultContent: '',
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
      { targets: 1, data: 'lot_no' },
      { targets: 2, data: 'district' },
      { targets: 3, data: 'ac' },
      { targets: 4, data: 'block' },
      { targets: 5, data: 'gp' },
      { targets: 6, data: 'ref_no' },
      {
        targets: 7,
        defaultContent: '',
        render: function (data, type, full, meta) {
          return (
            '<p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' +
            full.name +
            '">' +
            full.name +
            '</p>'
          );
        },
      },

      { targets: 8, data: 'agency' },
      { targets: 9, data: 'proposed_length' },
      { targets: 10, data: 'length' },
      { targets: 11, data: 'work_type' },
      { targets: 12, data: 'road_type' },
      {
        targets: 13,
        data: 'bt_length',
        render: function (data) {
          return parseFloat(data).toFixed(3) || 0;
        },
      },
      {
        targets: 14,
        data: 'cc_length',
        render: function (data) {
          return parseFloat(data).toFixed(3) || 0;
        },
      },
      {
        targets: 15,
        data: null,
        render: function (data, type, row) {
          const bt = parseFloat(row.bt_length) || 0;
          const cc = parseFloat(row.cc_length) || 0;
          return (bt + cc).toFixed(3);
        },
      },
      { targets: 16, data: 'new_road_type' },
      { targets: 17, data: 'new_length' },
      {
        targets: 18,
        defaultContent: '',
        render: function (data, type, full, meta) {
          return full.status === '0'
            ? 'Not Started'
            : full.status === '1'
            ? 'On Going'
            : 'Completed';
        },
      },
      {
        targets: 19,
        defaultContent: '',
        render: function (data, type, full, meta) {
          var vetted = parseFloat(full.cost || 0).toFixed(2);
          var editIcon =
            full.status < 2
              ? ''
              : ' <i class="fa fa-edit pointer text-primary edit-vetted" data-id="' +
                full.id +
                '"></i>';
          return (
            '<span id="cost_' +
            full.id +
            '" class="fw-bold">' +
            vetted +
            '</span>' +
            editIcon
          );
        },
      },
      {
        targets: 20,
        defaultContent: '',
        render: function (data, type, full, meta) {
          var gst = parseFloat(full.gst || 0).toFixed(2);
          return (
            '<span id="gst_' + full.id + '" class="fw-bold">' + gst + '</span>'
          );
        },
      },
      {
        targets: 21,
        defaultContent: '',
        render: function (data, type, full, meta) {
          var cess = parseFloat(full.cess || 0).toFixed(2);
          return (
            '<span id="cess_' +
            full.id +
            '" class="fw-bold">' +
            cess +
            '</span>'
          );
        },
      },
      {
        targets: 22,
        data: null,
        render: function (data, type, row) {
          let vetted = parseFloat(row.cost) || 0;
          let gst = parseFloat(row.gst) || 0;
          let cess = parseFloat(row.cess) || 0;
          let total_estimated = vetted + gst + cess;
          return (
            '<span id="total_' +
            row.id +
            '" class="fw-bold">' +
            total_estimated.toFixed(2) +
            '</span>'
          );
        },
      },
      {
        targets: 23,
        data: null,
        render: function (data, type, row) {
          // Safely parse numeric values
          let cost = parseFloat(row.cost) || 0;
          let gst = parseFloat(row.gst) || 0;
          let cess = parseFloat(row.cess) || 0;
          let bt = parseFloat(row.bt_length) || 0;
          let cc = parseFloat(row.cc_length) || 0;
          let length = bt + cc || 1; // avoid division by 0

          // Calculate estimated value in lakh per length
          let estimated = cost + gst + cess;
          let valueInLakh = estimated / length / 100000;

          // Return formatted value aligned right with 2 decimals
          return (
            '<div style="text-align:left; font-weight:bold;">' +
            valueInLakh.toFixed(3) +
            '</div>'
          );
        },
      },
      {
        targets: 24,
        defaultContent: '',
        render: function (data, type, full, meta) {
          let contigency = parseFloat(full.contigency_amt || 0).toFixed(2);
          return (
            '<span id="contigency_' +
            full.id +
            '" class="fw-bold">' +
            contigency +
            '</span>'
          );
        },
      },
      {
        targets: 25,
        defaultContent: '',
        render: function (data, type, full, meta) {
          var estimated = parseFloat(full.estimated_amt || 0).toFixed(2);
          return (
            '<span id="estimated_' +
            full.id +
            '" class="fw-bold">' +
            estimated +
            '</span>'
          );
        },
      },

      { targets: 26, data: 'return_cause' },
      {
        targets: 27,
        data: null,
        render: function (data, type, full, meta) {
          var html =
            '<div style="display:inline-block; text-align:center; min-width:120px;">';
          if (full.survey_estimated_doc) {
            html +=
              '<a href="' +
              baseURL +
              '/' +
              full.survey_estimated_doc +
              '" target="_blank" ' +
              'style="display:inline-block; margin-top:5px; padding:5px 10px; font-size:12px; font-weight:500; color:#fff; background:#4a90e2; border-radius:6px; text-decoration:none; transition:0.3s;">' +
              '<i class="fa fa-file-alt"></i> View Doc' +
              '</a>';
          }
          html += '</div>';
          return html;
        },
      },
      {
        targets: 28,
        defaultContent: '',
        render: function (data, type, full, meta) {
          var doc =
            full.lot_doc.length > 0
              ? '<a target="_blank" class="btn btn-sm btn-success" href="' +
                baseURL +
                '/' +
                full.lot_doc +
                '"><i class="fas fa-file-pdf"></i></a>'
              : '';
          return doc;
        },
      },
    ],
  });
}
function get_approved_list() {
  if ($('#district_id').val() > 0) {
    $.ajax({
      url: baseURL + '/roads/get_approved_list',
      type: 'get',
      data: {
        district_id: $('#district_id').val(),
        block_id: $('#block_id').val(),
        ac_id: $('#ac_id').val(),
      },
      dataType: 'json',
      async: false,
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
      right: 1,
    },
    buttons: [
      {
        extend: 'excel',
        text: 'Excel',
        filename: 'approved_list_' + $.now(),
        title:
          'APPROVED LIST ON ' +
          String(currentdate.getDate()).padStart(2, '0') +
          '/' +
          String(currentdate.getMonth() + 1).padStart(2, '0') +
          '/' +
          currentdate.getFullYear() +
          ' ' +
          String(currentdate.getHours()).padStart(2, '0') +
          ':' +
          String(currentdate.getMinutes()).padStart(2, '0'),
        footer: true,
        exportOptions: {
          columns: ':not(.not-export)',
        },
        customize: function (xlsx) {
          var sheet = xlsx.xl.worksheets['sheet1.xml'];
          $('row c', sheet).attr('s', '25');
        },
      },
      {
        extend: 'print',
        text: 'Print',
        title: 'APPROVED LIST',
        footer: true,
        exportOptions: {
          columns: ':not(.not-export)',
        },
        customize: function (win) {
          $(win.document.body)
            .find('h1')
            .css('text-align', 'center')
            .css('font-size', '10pt')
            .prepend(
              '<img src="' +
                baseURL +
                '/templates/img/pathashree.jpg" style="position:absolute; top:0; left:0;" />'
            );
          $(win.document.body)
            .find('table')
            .addClass('compact')
            .css('font-size', 'inherit')
            .css('margin', '50px auto');
        },
      },
    ],
    columnDefs: [
      {
        targets: 0,
        defaultContent: '',
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
      { targets: 1, data: 'district' },
      { targets: 2, data: 'ac' },
      { targets: 3, data: 'block' },
      { targets: 4, data: 'gp' },
      { targets: 5, data: 'ref_no' },
      {
        targets: 6,
        defaultContent: '',
        render: function (data, type, full, meta) {
          return (
            '<p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' +
            full.name +
            '">' +
            full.name +
            '</p>'
          );
        },
      },
      { targets: 7, data: 'agency' },
      { targets: 8, data: 'proposed_length' },
      { targets: 9, data: 'length' },
      { targets: 10, data: 'road_type' },
      { targets: 11, data: 'work_type' },
      { targets: 12, data: 'bt_length' },
      { targets: 13, data: 'cc_length' },
      {
        targets: 14,
        data: null, // or can be 'cc_length' but better null
        render: function (data, type, row) {
          // Sum the two fields
          let cc = parseFloat(row.cc_length) || 0;
          let bt = parseFloat(row.bt_length) || 0;
          let sum = cc + bt;
          return sum.toFixed(3);
        },
      },
      { targets: 15, data: 'new_road_type' },
      { targets: 16, data: 'new_length' },
      {
        targets: 17,
        defaultContent: '',
        render: function (data, type, full, meta) {
          return full.status === '0'
            ? 'Not Started'
            : full.status === '1'
            ? 'On Going'
            : 'Completed';
        },
      },
      {
        targets: 18,
        defaultContent: '',
        render: function (data, type, full, meta) {
          return (
            '<div style="text-align:right; font-weight:bold;">' +
            parseFloat(full.cost || 0).toFixed(2) +
            '</div>'
          );
        },
      },
      {
        targets: 19,
        defaultContent: '',
        render: function (data, type, full, meta) {
          return (
            '<div style="text-align:right; font-weight:bold;">' +
            parseFloat(full.gst || 0).toFixed(2) +
            '</div>'
          );
        },
      },
      {
        targets: 20,
        defaultContent: '',
        render: function (data, type, full, meta) {
          return (
            '<div style="text-align:right; font-weight:bold;">' +
            parseFloat(full.cess || 0).toFixed(2) +
            '</div>'
          );
        },
      },
      {
        targets: 21,
        data: null,
        render: function (data, type, row) {
          let vetted = parseFloat(row.cost) || 0;
          let gst = parseFloat(row.gst) || 0;
          let cess = parseFloat(row.cess) || 0;
          let total_estimated = vetted + gst + cess;
          return (
            '<div style="text-align:right; font-weight:bold;">' +
            total_estimated.toFixed(2) +
            '</div>'
          );
        },
      },
      {
        targets: 22,
        data: null,
        render: function (data, type, row) {
          // Safely parse numeric values
          let cost = parseFloat(row.cost) || 0;
          let gst = parseFloat(row.gst) || 0;
          let cess = parseFloat(row.cess) || 0;
          let length = parseFloat(row.length) || 1; // avoid division by 0

          // Calculate estimated value in lakh per length
          let estimated = cost + gst + cess;
          let valueInLakh = estimated / length / 100000;

          // Return formatted value aligned right
          return (
            '<div style="text-align:right; font-weight:bold;">' +
            valueInLakh.toFixed(2) +
            '</div>'
          );
        },
      },
      {
        targets: 23,
        defaultContent: '',
        render: function (data, type, full, meta) {
          return (
            '<div style="text-align:right; font-weight:bold;">' +
            parseFloat(full.contigency_amt || 0).toFixed(2) +
            '</div>'
          );
        },
      },
      {
        targets: 24,
        defaultContent: '',
        render: function (data, type, full, meta) {
          return (
            '<div style="text-align:right; font-weight:bold;">' +
            parseFloat(full.estimated_amt || 0).toFixed(2) +
            '</div>'
          );
        },
      },
      {
        targets: 25,
        defaultContent: '',
        render: function (data, type, full, meta) {
          var doc =
            full.lot_doc.length > 0
              ? '<a target="_blank" class="btn btn-sm btn-success" href="' +
                baseURL +
                '/' +
                full.lot_doc +
                '"><i class="fas fa-file-pdf"></i></a>'
              : '';
          return doc;
        },
      },
    ],
  });
}

function get_state_approval_list() {
  if ($('#district_id').val() > 0 && $('#block_id').val() > 0) {
    $.ajax({
      url: baseURL + '/roads/get_state_approval_list',
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
    url: baseURL + '/roads/get_not_imp_list',
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
      right: 1,
    },
    buttons: [
      {
        extend: 'excel',
        text: 'Excel',
        filename: 'not_implemented_list_' + $.now(),
        title:
          'NOT IMPLEMENTED LIST ON ' +
          String(currentdate.getDate()).padStart(2, '0') +
          '/' +
          String(currentdate.getMonth() + 1).padStart(2, '0') +
          '/' +
          currentdate.getFullYear() +
          ' ' +
          String(currentdate.getHours()).padStart(2, '0') +
          ':' +
          String(currentdate.getMinutes()).padStart(2, '0'),
        footer: true,
        exportOptions: {
          columns: ':not(.not-export)',
        },
        customize: function (xlsx) {
          var sheet = xlsx.xl.worksheets['sheet1.xml'];
          $('row c', sheet).attr('s', '25');
        },
      },
      {
        extend: 'print',
        text: 'Print',
        title: 'NOT IMPLEMENTED LIST',
        footer: true,
        exportOptions: {
          columns: ':not(.not-export)',
        },
        customize: function (win) {
          $(win.document.body)
            .find('h1')
            .css('text-align', 'center')
            .css('font-size', '10pt')
            .prepend(
              '<img src="' +
                baseURL +
                '/templates/img/pathashree.jpg" style="position:absolute; top:0; left:0;" />'
            );
          $(win.document.body)
            .find('table')
            .addClass('compact')
            .css('font-size', 'inherit')
            .css('margin', '50px auto');
        },
      },
    ],
    columnDefs: [
      {
        targets: 0,
        defaultContent: '',
        render: function (data, type, full, meta) {
          return i++;
        },
      },
      { targets: 1, data: 'created' },
      { targets: 2, data: 'district' },
      { targets: 3, data: 'block' },
      { targets: 4, data: 'gp' },
      {
        targets: 5,
        defaultContent: '',
        render: function (data, type, full, meta) {
          return (
            '<p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' +
            full.name +
            '">' +
            full.name +
            '</p>'
          );
        },
      },
      { targets: 6, data: 'proposed_length' },
      { targets: 7, data: 'road_type' },
      { targets: 8, data: 'work_type' },
      { targets: 9, data: 'agency' },
      {
        targets: 10,
        defaultContent: '',
        render: function (data, type, full, meta) {
          return full.status === '0'
            ? 'Not Traceable'
            : full.status === '1'
            ? 'Taken up with Other Scheme/Fund'
            : 'Others';
        },
      },
      { targets: 11, data: 'remarks' },
      {
        targets: 12,
        defaultContent: '',
        render: function (data, type, full, meta) {
          return (
            '<td><p style="margin:0px; width: 80px"><button class="btn btn-icon btn-round btn-sm btn-danger" onclick="delete_not_traceable(' +
            full.id +
            ')"  title="Delete"><i class="fas fa-trash pointer"></i></button></p></td>'
          );
        },
      },
    ],
  });
}

function delete_not_traceable(id) {
  var r = confirm('Do you want to delete this?');
  if (r === true) {
    window.location.href = baseURL + '/roads/delete_not_traceable/' + id;
  }
}

$('#search_tender').on('click', function (e) {
  e.preventDefault();
  get_tender_list();
});

function tender_entry(id) {
  window.location.href = baseURL + '/roads/tender_entry/' + id;
}

function get_tender_list() {
  if ($('#district_id').val() > 0) {
    $.ajax({
      url: baseURL + '/roads/get_tender_list',
      type: 'get',
      data: {
        district_id: $('#district_id').val(),
        block_id: $('#block_id').val(),
        ac_id: $('#ac_id').val(),
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
      right: 1,
    },
    buttons: [
      {
        extend: 'excel',
        text: 'Excel',
        filename: 'tender_list_' + $.now(),
        title:
          'TENDER LIST ON ' +
          String(currentdate.getDate()).padStart(2, '0') +
          '/' +
          String(currentdate.getMonth() + 1).padStart(2, '0') +
          '/' +
          currentdate.getFullYear() +
          ' ' +
          String(currentdate.getHours()).padStart(2, '0') +
          ':' +
          String(currentdate.getMinutes()).padStart(2, '0'),
        footer: true,
        exportOptions: {
          columns: ':not(.not-export)',
        },
        customize: function (xlsx) {
          var sheet = xlsx.xl.worksheets['sheet1.xml'];
          $('row c', sheet).attr('s', '25');
        },
      },
      {
        extend: 'print',
        text: 'Print',
        title: 'TENDER LIST',
        footer: true,
        exportOptions: {
          columns: ':not(.not-export)',
        },
        customize: function (win) {
          $(win.document.body)
            .find('h1')
            .css('text-align', 'center')
            .css('font-size', '10pt')
            .prepend(
              '<img src="' +
                baseURL +
                '/templates/img/pathashree.jpg" style="position:absolute; top:0; left:0;" />'
            );
          $(win.document.body)
            .find('table')
            .addClass('compact')
            .css('font-size', 'inherit')
            .css('margin', '50px auto');
        },
      },
    ],
    columnDefs: [
      {
        targets: 0,
        defaultContent: '',
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
      { targets: 1, data: 'district' },
      { targets: 2, data: 'ac' },
      { targets: 3, data: 'block' },
      { targets: 4, data: 'gp' },
      { targets: 5, data: 'ref_no' },
      {
        targets: 6,
        defaultContent: '',
        render: function (data, type, full, meta) {
          return (
            '<p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' +
            full.name +
            '">' +
            full.name +
            '</p>'
          );
        },
      },
      { targets: 7, data: 'length' },
      { targets: 8, data: 'agency' },
      { targets: 9, data: 'cost' },
      { targets: 10, data: 'tender_number' },
      { targets: 11, data: 'tender_publication_date' },
      {
        targets: 12,
        defaultContent: '',
        render: function (data, type, full, meta) {
          return full.tender_status === '0'
            ? 'Not Started'
            : full.tender_status === '1'
            ? 'On Progress'
            : full.tender_status === '2'
            ? 'Completed'
            : 'Retendering';
        },
      },
      { targets: 13, data: 'bid_closing_date' },
      { targets: 14, data: 'bid_opeaning_date' },
      {
        targets: 15,
        defaultContent: '',
        render: function (data, type, full, meta) {
          return full.evaluation_status === null
            ? ''
            : full.evaluation_status === '0'
            ? 'No'
            : 'Yes';
        },
      },
      {
        targets: 16,
        defaultContent: '',
        render: function (data, type, full, meta) {
          return full.bid_opening_status === null
            ? ''
            : full.bid_opening_status === '0'
            ? 'No'
            : 'Yes';
        },
      },
      {
        targets: 17,
        defaultContent: '',
        render: function (data, type, full, meta) {
          return full.bid_matured_status === null
            ? ''
            : full.bid_matured_status === '0'
            ? 'No'
            : 'Yes';
        },
      },
      {
        targets: 18,
        defaultContent: '',
        render: function (data, type, full, meta) {
          return (
            '<p style="margin:0px; width: 80px"><button class="btn btn-icon btn-round btn-sm btn-primary" onclick="_edit_tender(' +
            full.id +
            ',' +
            full.tender_status +
            ')"  title="Edit"><i class="fas fa-pen pointer"></i></button></p>'
          );
        },
      },
    ],
  });
}

function get_wo_list() {
  $.ajax({
    url: baseURL + '/roads/get_wo_list',
    type: 'get',
    data: {
      district_id: $('#district_id').val(),
      block_id: $('#block_id').val(),
      ac_id: $('#ac_id').val(),
    },
    dataType: 'json',
    async: false,
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
      right: 1,
    },
    buttons: [
      {
        extend: 'excel',
        text: 'Excel',
        filename: 'wo_list_' + $.now(),
        title:
          'WORK ORDER LIST ON ' +
          String(currentdate.getDate()).padStart(2, '0') +
          '/' +
          String(currentdate.getMonth() + 1).padStart(2, '0') +
          '/' +
          currentdate.getFullYear() +
          ' ' +
          String(currentdate.getHours()).padStart(2, '0') +
          ':' +
          String(currentdate.getMinutes()).padStart(2, '0'),
        footer: true,
        exportOptions: {
          columns: ':not(.not-export)',
        },
        customize: function (xlsx) {
          var sheet = xlsx.xl.worksheets['sheet1.xml'];
          $('row c', sheet).attr('s', '25');
        },
      },
      {
        extend: 'print',
        text: 'Print',
        title: 'WORK ORDER LIST',
        footer: true,
        exportOptions: {
          columns: ':not(.not-export)',
        },
        customize: function (win) {
          $(win.document.body)
            .find('h1')
            .css('text-align', 'center')
            .css('font-size', '10pt')
            .prepend(
              '<img src="' +
                baseURL +
                '/templates/img/pathashree.jpg" style="position:absolute; top:0; left:0;" />'
            );
          $(win.document.body)
            .find('table')
            .addClass('compact')
            .css('font-size', 'inherit')
            .css('margin', '50px auto');
        },
      },
    ],
    columnDefs: [
      {
        targets: 0,
        defaultContent: '',
        render: function (data, type, full, meta) {
          return i++;
        },
      },
      { targets: 1, data: 'district' },
      { targets: 2, data: 'ac' },
      { targets: 3, data: 'block' },
      {
        targets: 4,
        defaultContent: '',
        render: function (data, type, full, meta) {
          return (
            '<p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' +
            full.name +
            '">' +
            full.name +
            '</p>'
          );
        },
      },
      { targets: 5, data: 'agency' },
      { targets: 6, data: 'wo_no' },
      { targets: 7, data: 'wo_date' },
      { targets: 8, data: 'contractor' },
      { targets: 9, data: 'completion_date' },
      { targets: 10, data: 'assigned_engineer' },
      {
        targets: 11,
        defaultContent: '',
        render: function (data, type, full, meta) {
          var document =
            full.document !== null && full.document.length > 0
              ? '<button class="btn btn-icon btn-round btn-sm btn-primary" onclick="_document(\'' +
                baseURL +
                '/' +
                full.document +
                '\')"  title="Document"><i class="fas fa-file-pdf"></i></button>'
              : '';
          return document;
        },
      },
      {
        targets: 12,
        defaultContent: '',
        render: function (data, type, full, meta) {
          return (
            '<button class="btn btn-icon btn-round btn-sm btn-primary" onclick="wo_add(' +
            full.roads_id +
            ')"  title="Edit"><i class="fas fa-pen pointer"></i></button>'
          );
        },
      },
    ],
  });
}

function _document(url) {
  window.open(url, '_blank');
}

function wo_add(id) {
  window.location.href = baseURL + '/roads/wo_entry/' + id;
}
function wo_remove(id) {
  var r = confirm('Do you want to delete this?');
  if (r === true) {
    $.ajax({
      url: baseURL + '/roads/wo_remove',
      type: 'get',
      data: { id: id },
      dataType: 'json',
      async: false,
    }).done(function (data) {
      get_wo_list();
    });
  }
}
