/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {
   $('#tbl').DataTable();
});

function add(id) {
    window.location.href = baseURL + '/notice/entry/' + id;
}
function _document(url) {
    window.open(url, '_blank');
  }
function remove(id) {
    var r = confirm("Do you want to delete this?")
    if (r == true) {
        $.ajax({
            url: baseURL + '/notice/remove',
            type: 'get',
            data: {id: id},
            dataType: 'json',
            async: false
        }).done(function(data) {
            window.location = baseURL + '/notice';
        });  
    }
}
