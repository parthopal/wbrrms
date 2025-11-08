$(document).ready(function () {
//    const basePath = window.location.origin;
//    console.log('basepath: ' + basePath);
    $(".btn-captcha").on("click", function () {
        $.get("login/refresh", function (data) {
            $("#image").html(data);
        });
    });

    $(".btn-submit").on("click", function () {
        var password = sha256($("#password").val());
        hash = password + rand;
        password = sha256(hash);
        $.ajax({
            url: "login/validate",
            type: "post",
            data: {captcha: $("#captcha").val(), username: $("#username").val(), password: password},
            dataType: "json",
            async: false
        }).done(function (data) {
            if (data.length > 0) {
                alert(data);
            } else {
                window.location.href = baseURL + "/dashboard";
            }
        });
    });
});
