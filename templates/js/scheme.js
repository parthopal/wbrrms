/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {
//    $('#type_id').on('change', function (e) {
//        e.preventDefault();
//        get_block_list();
//    });
    $('#district_id').on('change', function (e) {
        e.preventDefault();
        get_block_list();
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
    $('#islocked').on('change', function (e) {
        e.preventDefault();
        if ($('#islocked').is(':checked')) {
            $('.lblreqd').text('*');
            $('.reqd').attr('required', 'required');
            $('#islocked').val(1);
        } else {
            $('.lblreqd').text('');
            $('.reqd').removeAttr('required');
            $('#islocked').val(0);
        }
    });
    $('#next_call').on('click', function (e) {
        e.preventDefault();
        next_call();
    });
    $('#retender').on('click', function (e) {
        e.preventDefault();
        retender();
    });
});

function get_block_list() {
    $.ajax({
        url: baseURL + '/scheme/get_block_list',
        type: 'get',
        data: {district_id: $('#district_id').val()},
        dataType: 'json',
        async: false
    }).done(function (data) {
        $('#block_id').empty();
        if (data.length > 0) {
            $('#block_id').append($('<option>', {value: '', text: '--Select Block--'}));
            $.each(data, function (i, item) {
                $('#block_id').append($('<option>', {value: item.id, text: item.name}));
            });
        } else {
            $('#block_id').append($('<option>', {value: '', text: '--Select Block--'}));
        }
    });
}
function next_call() {
    let remarks = prompt("Please enter reason");
    if (remarks !== null) {
        $.ajax({
            url: baseURL + '/scheme/next_call',
            type: 'get',
            data: {id: $('#id').val(), scheme_id: $('#scheme_id').val(), remarks: remarks},
            dataType: 'json',
            async: false
        }).done(function (data) {
            window.location.href = baseURL + '/scheme/tender_entry/' + $('#sc').val() + '/' + $('#scheme_id').val();
        });
    }
}
function retender() {
    let remarks = prompt("Please enter reason");
    if (remarks !== null) {
        $.ajax({
            url: baseURL + '/scheme/retender',
            type: 'get',
            data: {id: $('#id').val(), scheme_id: $('#scheme_id').val(), remarks: remarks},
            dataType: 'json',
            async: false
        }).done(function (data) {
            window.location.href = baseURL + '/scheme/tender_entry/' + $('#sc').val() + '/' + $('#scheme_id').val();
        });
    }
}