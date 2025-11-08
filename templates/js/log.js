$(document).ready(function () {
    $("#issue_type_id").on("change", function (e) {
        e.preventDefault();
        get_issue_list();
    });
});
function get_issue_list() {
    console.log("issue_type_id: " + $("#issue_type_id").val());
    $.ajax({
        url: baseURL + "/log/get_issue_list",
        type: "get",
        data: {issue_type_id: $("#issue_type_id").val()},
        dataType: "json",
        async: false,
    }).done(function (data) {
        console.log(data);
        $("#issue_id").empty();
        if (data.length > 0) {
            $("#issue_id").append($("<option>", {value: "", text: "--Select Issue--"}));
            $.each(data, function (i, item) {
                $("#issue_id").append($("<option>", {value: item.id, text: item.name}));
            });
        }
    });
}
