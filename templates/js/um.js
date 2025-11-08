/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {
    $("#district_id").trigger("change");
    $("#block_id").trigger("change");
    $('#tbl').DataTable({
        dom: 'lBfrtip',
        processing: true,
        scrollY: '450px',
        scrollX: true,
        scrollCollapse: true,
        paging: false,
        responsive: true,
        stateSave: true,
        colReorder: true,
//        language: {
//            search: '_INPUT_',
//            searchPlaceholder: 'Search records'
//        },
        'columns': [null, null, null, null, null, {'searchable': false}, {'searchable': false}, {'searchable': false}],
        fixedColumns: {
            left: 1,
            right: 1
        },
        buttons: [
            {
                extend: 'excel',
                text: 'Excel',
                filename: 'user_information_' + $.now(),
                title: 'User Information',
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
                title: 'User Information',
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
        ]
    });
    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
    });
//    $('#tbl').DataTable({
//        dom: 'Bfrtip',
//        buttons: ['excel'],
//    });
//    $('[data-toggle="tooltip"]').tooltip();
    var tw;
    $('#role_id').on('change', function () {
        if ($('#role_id').val() > 0) {
            get_menu_list();
        } else {
            $('#menu').empty();
        }
    });
    $("#district_id").on("change", function (e) {
        e.preventDefault();
        get_block_list();
    });
    $("#menu").on('click', function () {
        var data = get_tree_set();
        $('#treeview').val(JSON.stringify(data));
    });
    $("#username").on('change', function () {
        $.ajax({
            url: baseURL + '/um/check_username',
            type: 'get',
            data: {username: $('#username').val()},
            dataType: 'json',
            async: false
        }).done(function (data) {
            console.log(data);
            if (data) {
                $('#chk').html('<span style="color: red;">Username already exists</span>');
                $('button[type="submit"]').attr('disabled', 'disabled');
            } else {
                $('#chk').html('<span style="color: green;">Username is available</span>');
                // $('#chk').text('Username is available');
            }
        });
    });


});
function edit(id) {
    if (confirm('Do you want to edit this user?') === true) {
        window.location = baseURL + '/um/entry/' + id;
    }
}
function get_block_list() {
    $.ajax({
        url: baseURL + '/um/get_block_list',
        type: 'get',
        data: {district_id: $('#district_id').val()},
        dataType: 'json',
        async: false,
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

function reset(id) {
    if (confirm('Do you want to reset this user?') === true) {
        $.ajax({
            url: baseURL + '/um/reset',
            type: 'get',
            data: {id: id},
            dataType: 'json',
            async: false
        }).done(function (data) {
            alert('Password reset successfully.');
        });
    }
}

function get_tree_set() {
    var data = [];
    var $children = $(tw.root).find(">.group");
    for (var i = 0; i < $children.length; i++) {
        var child = tw.save("tree", $children[i]);
        if (child.checked > 0) {
            data.push({id: child.id, level: child.level, onlyview: child.onlyView});
            for (var j = 0; j < child.children.length; j++) {
                var child1 = child.children[j];
                if (child1.checked > 0) {
                    data.push({id: child1.id, level: child1.level, onlyview: child1.onlyView});
                    for (var k = 0; k < child1.children.length; k++) {
                        var child2 = child1.children[k];
                        if (child2.checked > 0) {
                            data.push({id: child2.id, level: child2.level, onlyview: child2.onlyView});
                        }
                    }
                }
            }
        }
    }
    return data;
}
function get_menu_list() {
    $.ajax({
        type: 'GET',
        url: baseURL + '/um/get_menu_list',
        data: {role_id: $('#role_id').val()},
        dataType: 'json',
        success: function (data) {
            $('#menu').empty();
            tw = new TreeView(data, {showAlwaysCheckBox: true, fold: true, openAllFold: true});
            $('#menu').append(tw.root);
            $('#menu_settings').show();
            $('#menu').show();
        },
        error: function (response) {
            console.log('error: ' + response);
        }
    });
}
function get_user_list() {
    $.ajax({
        url: baseURL + '/um/get_user_list',
        type: 'get',
        data: {},
        dataType: 'json',
        async: false
    }).done(function (data) {
        _load_user_list(data);
    });
}
function _load_user_list(data) {
    console.log(data);
    var i = 1;
    $('#tbl').dataTable().fnDestroy();
    $('#tbl').DataTable({
        data: data,
        columnDefs: [
            {targets: 0, render: function (data, type, full, meta) {
                    return i++;
                }
            },
            {targets: 1, data: 'role'},
            {targets: 2, data: 'name'},
            {targets: 3, data: 'district'},
            {targets: 4, data: 'block'},
            {targets: 5, render: function (data, type, full, meta) {
                    return full.mobile + '<br>' + full.email;
                }
            },
            {targets: 6, render: function (data, type, full, meta) {
                    return full.image !== null ? '<div class="avatar"><img src="' + full.image + '" class="avatar-img rounded-circle"></div>' : ''
                }},
            {targets: 7, render: function (data, type, full, meta) {
                    return '<button class="btn btn-sm btn-icon btn-round btn-warning" onclick="reset(' + full.id + ')" title="Reset password"><i class="fas fa-user-cog pointer"></i></button>'
                }},
            {targets: 8, render: function (data, type, full, meta) {
                    return '<button class="btn btn-sm btn-icon btn-round btn-secondary" onclick="edit(' + full.id + ')" title="Edit"><i class="fas fa-pen pointer"></i></button>&nbsp;<button onclick="remove(' + full.id + ')" title="Remove" class="btn btn-sm btn-icon btn-round btn-danger"><i class="fas fa-trash pointer"></i></button>'
                }}
        ]
    });
}
function remove(id) {
    if (confirm('Do you want to remove this user?') === true) {
        $.ajax({
            url: baseURL + '/um/remove',
            type: 'get',
            data: {id: id},
            dataType: 'json',
            async: false
        }).done(function (data) {
            alert('User removed successfully.');
            get_user_list();
        });
    }
}
