/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {
    $('input[name=expenditure]').on('change', function (e) {
        e.preventDefault();
        get_against_list();
    });
    $('#against_id').on('change', function (e) {
        e.preventDefault();
        get_against_ref();
        get_pending_amount();
    });
    $('#district_id').on('change', function (e) {
        e.preventDefault();
        //get_against_list();
        get_block_list();
    });
    $('#block_id').on('change', function (e) {
        e.preventDefault();
        get_against_list();
    });
});

function get_against_list() {
    $.ajax({
        url: baseURL + '/fund/get_against_list',
        type: 'get',
        data: {activity_id: $('#activity_id').val(), expenditure: $('input[name=expenditure]:checked').val(),
            district_id: $('#district_id').val(), block_id: $('#block_id').val(), from_agency_id: $('#from_agency_id').val()},
        dataType: 'json',
        async: false
    }).done(function (data) {
        $('#against_id').empty();
        $('#pending_amount').text('0.00');
        $('#against_id').append($('<option>', {value: '', text: '--Select Against Ref--'}));
        if (data.length > 0) {
            $.each(data, function (i, item) {
                $('#against_id').append($('<option>', {value: item.id, text: item.order_no}));
            });
        }
    });
}
function get_against_ref() {
    $.ajax({
        url: baseURL + '/fund/get_against_ref',
        type: 'get',
        data: {against_id: $('#against_id').val()},
        dataType: 'json',
        async: false
    }).done(function (data) {
        $('#against_order_no').text(data.order_no);
        $('#against_order_amount').text(data.amount / 100);
    });
}
function get_pending_amount() {
    $.ajax({
        url: baseURL + '/fund/get_pending_amount',
        type: 'get',
        data: {against_id: $('#against_id').val()},
        dataType: 'json',
        async: false
    }).done(function (data) {
//        var words = numberToWords(data);
        $('#pending_amount').text(data); // + '<br>' + words);
    });
}
function get_block_list() {
    $.ajax({
        url: baseURL + '/project/get_block_list',
        type: 'get',
        data: {district_id: $('#district_id').val()},
        dataType: 'json',
        async: false
    }).done(function (data) {
        $('#block_id').empty();
        if (data.length > 0) {
            $('#block_id').append($('<option>', {value: '0', text: '--All Block--'}));
            $.each(data, function (i, item) {
                $('#block_id').append($('<option>', {value: item.id, text: item.name}));
            });
        } else if ($('#district_id').val() == 0) {
            $('#block_id').append($('<option>', {value: '0', text: '--All Block--'}));
        } else {
            $('#block_id').append($('<option>', {value: '', text: '--Select Block--'}));
        }
    });
}

function numberToWords(number) {
    var digit = ['zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
    var elevenSeries = ['ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'];
    var countingByTens = ['twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];
    var shortScale = ['', 'thousand', 'million', 'billion', 'trillion'];

    number = number.toString();
    number = number.replace(/[\, ]/g, '');
    if (number != parseFloat(number))
        return 'not a number';
    var x = number.indexOf('.');
    if (x == -1)
        x = number.length;
    if (x > 15)
        return 'too big';
    var n = number.split('');
    var str = '';
    var sk = 0;
    for (var i = 0; i < x; i++) {
        if ((x - i) % 3 == 2) {
            if (n[i] == '1') {
                str += elevenSeries[Number(n[i + 1])] + ' ';
                i++;
                sk = 1;
            } else if (n[i] != 0) {
                str += countingByTens[n[i] - 2] + ' ';
                sk = 1;
            }
        } else if (n[i] != 0) {
            str += digit[n[i]] + ' ';
            if ((x - i) % 3 == 0)
                str += 'hundred ';
            sk = 1;
        }
        if ((x - i) % 3 == 1) {
            if (sk)
                str += shortScale[(x - i - 1) / 3] + ' ';
            sk = 0;
        }
    }
    if (x != number.length) {
        var y = number.length;
        str += 'point ';
        for (var i = x + 1; i < y; i++)
            str += digit[n[i]] + ' ';
    }
    str = str.replace(/\number+/g, ' ');
    return str.trim() + ".";

}