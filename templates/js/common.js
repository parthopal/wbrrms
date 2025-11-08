/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var baseURL = window.location.origin;

function _back() {
    history.back();
}
$(document).ready(function () {
//    $('#tbl').clear().destroy();
//    if ($.fn.DataTable.isDataTable('#tbl')) {
//        $('#tbl').DataTable().destroy();
//    }
//    $('#tbl tbody').empty();
//
//    $('#tbl').DataTable({
//        dom: 'Bfrtip',
//        buttons: ['excel'],
//        paging: true
//    });
//    $('[data-toggle="tooltip"]').tooltip();
});

$('.dropdown').select2({
    theme: 'bootstrap',
});

$('.datepicker').datetimepicker({
    format: 'DD/MM/YYYY',
});
