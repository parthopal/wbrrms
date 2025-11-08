/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var i;
$(document).ready(function () {
    if (i === 4) {
        $('#add').removeAttr('disabled');
        $('#remove').attr('disabled', 'disabled');
    }
    $("#add").on("click", function (e) {
        e.preventDefault();
        if(i == 8){
            alert('You already add 8 image')
        }else{
        if (i < 8) {
            alert('You add Max 8 image');
            var html = '<div id="div_' + i + '" class="col-md-6"><div class="column">';
            html += '<div class="form-group">';
            html += '<label>Description *</label>';
            html += '<textarea class="form-control" name="desc[' + i + ']" id="desc_' + i + '" rows="1" required></textarea>';
            html += '</div>';
            html += '<div class="form-group">';
            html += '<label>Upload Image * <br><span style="font-size: 10px; color: red;">( Maximum 2Mb, Uploaded Format - jpeg/jpg/png ) </span></label></label>';
            html += '<div class="multiselect_div">';
            html += '<input type="file" name="image[' + i + ']" data-default-file="" class="dropify" data-max-file-size="2048K" accept="image/*">';
            html += '</div></div></div></div>';
            $('#div_image').append(html);
            $('.dropify').dropify();
            $('#remove').removeAttr('disabled');
            i++;
        } else {
            $('#add').attr('disabled', 'disabled');
            $('#remove').removeAttr('disabled');
        }
    }
    });
    $("#remove").on("click", function (e) {
        if (i > 4) {
            i--;
            $('#div_' + i).remove();
            $('#div_' + i).remove();
            $('#add').removeAttr('disabled');
            i === 4 ? $('#remove').attr('disabled', 'disabled') : '';
        } else {
            $('#remove').attr('disabled', 'disabled');
        }
    });
    if (i === 10) {
        $('#qm_add').removeAttr('disabled');
        $('#qm_remove').attr('disabled', 'disabled');
    }
    $("#qm_add").on("click", function (e) {
        e.preventDefault();
        if(i === 15){
            alert('You already added max 15 images')
        }else{
        if (i < 15) {
            var html = '<div id="div_' + i + '" class="col-md-6"><div class="column">';
            html += '<div class="form-group">';
            html += '<label>Description *</label>';
            html += '<textarea class="form-control" name="desc[' + i + ']" id="desc_' + i + '" rows="1" required></textarea>';
            html += '</div>';
            html += '<div class="form-group">';
            html += '<label>Upload Image * <br><span style="font-size: 10px; color: red;">( Maximum 2Mb, Uploaded Format - jpeg/jpg/png ) </span></label></label>';
            html += '<div class="multiselect_div">';
            html += '<input type="file" name="image[' + i + ']" data-default-file="" class="dropify" data-max-file-size="2048K" accept="image/*">';
            html += '</div></div></div></div>';
            $('#div_image').append(html);
            $('.dropify').dropify();
            $('#qm_remove').removeAttr('disabled');
            i++;
        } else {
            $('#qm_add').attr('disabled', 'disabled');
            $('#qm_remove').removeAttr('disabled');
        }
    }
    });
    $("#qm_remove").on("click", function (e) {
        if (i > 10) {
            i--;
            $('#div_' + i).remove();
            $('#div_' + i).remove();
            $('#qm_add').removeAttr('disabled');
            i === 10 ? $('#qm_remove').attr('disabled', 'disabled') : '';
        } else {
            $('#qm_remove').attr('disabled', 'disabled');
        }
    });
});

function back() {
    window.location.href = baseURL + '/ridf/bridge_inspection/';
}
function qm_back() {
    window.location.href = baseURL + '/ridf/bridge_qm/';
}

//function calc_overall_grade() {
//    var rb = $('input:checked');
//    var overallGrade = '';
//    var flag = true;
//    rb.each(function (index) {
//        var arr = $(this).val().split('_');
//        var id = parseInt(arr[0]);
//        var val = arr[1];
//        if (val !== 'na') {
//            if (val === 'u' && (id === 1 || id === 2)) {
//                overallGrade = 'SRI';
//            } else if (id > 2 && val === 'u') {
//                overallGrade = 'U';
//            } else if (val === 's' && overallGrade === '') {
//                overallGrade = 'S';
//            }
//        } else if (overallGrade === '') {
//            overallGrade = 'NA';
//        }
//    });
//    $('#span_grade').text(overallGrade);
//    $('#overall_grade').val(overallGrade);
//}