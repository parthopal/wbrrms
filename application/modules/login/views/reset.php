<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="container">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold">Change Password</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <?php echo form_open('login/save'); ?>
                                <div class="form-group">
                                    <label>Current Password</label>
                                    <input type="password" name="current_password" class="form-control" id="password" placeholder="Current password..." aria-describedby="basic-addon3" required>
                                </div>
                                <div class="form-group">
                                    <label>New Password</label>
                                    <input type="password" name="password" class="form-control" id="password1" placeholder="New password..." aria-describedby="basic-addon3" required>
                                </div>
                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    <input name="password_confirmation" type="text" class="form-control" id="password2" placeholder="Confirm password..." aria-describedby="basic-addon3" required>
                                </div>
                                <div class="col-md-4">
                                    <div class="progress" style="visibility:hidden" style="height: 10px">
                                        <div class="progress-bar" id="progress" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <p class="test-result"></p>
                                </div>
                                <div class="form-group text-right">
                                    <button type="submit" id="change_pass" name="submit" value="Submit" class="btn btn-primary" disabled> Change Password </button>
                                </div>
                                <?php
                                echo form_close();
                                ?>
                            </div>
                            <div class="col-md-4">
                                <div class="pass_criteria">
                                    <h4>Password Criteria</h4>
                                    <ul>
                                        <li class="test-lowercase"><i id="lowercase" class="fas fa-times"></i> &nbsp; Lowercase</li>
                                        <li class="test-uppercase"><i id="uppercase" class="fas fa-times"></i> &nbsp; Uppercase</li>
                                        <li class="test-digits"><i id="digits" class="fas fa-times"></i> &nbsp; Digits</li>
                                        <li class="test-special-char"><i id="special_char" class="fas fa-times"></i> &nbsp; Special characters </li>
                                        <li class="test-characters"><i id="characters" class="fas fa-times"></i> &nbsp; 6 Characters</li>
                                        <li class="test-match"><i id="match" class="fas fa-times"></i> &nbsp; Confirm password</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        let points = 0;
        let resultColor = '';
        let resultText = '';
        let password = $('#password1').val();
        let password2 = $('#password2').val();
        $('#password1').on('keyup', function () {
            password = $('#password1').val();
            if (password.match(/[a-z]+/)) {
                $('.test-lowercase').css('color', '#0dbe0d');
                $('#lowercase').attr('class', 'fas fa-check');
                points++;
            } else {
                $('.test-lowercase').css('color', '#db2f2f');
                $('#lowercase').attr('class', 'fas fa-times');
            }

            if (password.match(/[A-Z]+/)) {
                $('.test-uppercase').css('color', '#0dbe0d');
                $('#uppercase').attr('class', 'fas fa-check');
                points++;
            } else {
                $('.test-uppercase').css('color', '#db2f2f');
                $('#uppercase').attr('class', 'fas fa-times');

            }

            if (password.match(/[0-9]+/)) {
                $('.test-digits').css('color', '#0dbe0d');
                $('#digits').attr('class', 'fas fa-check');
                points++;
            } else {
                $('.test-digits').css('color', '#db2f2f');
                $('#digits').attr('class', 'fas fa-times');
            }

            if (password.match(/[!@#$%^&*]+/)) {
                $('.test-special-char').css('color', '#0dbe0d');
                $('#special_char').attr('class', 'fas fa-check');
                points++;
            } else {
                $('.test-special-char').css('color', '#db2f2f');
                $('#special_char').attr('class', 'fas fa-times');
            }

            if (password.length >= 6) {
                $('.test-characters').css('color', '#0dbe0d');
                $('#characters').attr('class', 'fas fa-check');
                points++;
            } else {
                $('.test-characters').css('color', '#db2f2f');
                $('#characters').attr('class', 'fas fa-times');
            }            
        });
        
        console.log("Scored:", points);

        $('#password2').on('keyup', function () {
            password = $('#password1').val();
            password2 = $('#password2').val();
            if (password != '' && password2 != password) {
                $('#password2').attr("style", "border:1px solid #db2f2f;");
                $('.test-match').css('color', '#db2f2f');
                $('#match').attr('class', 'fas fa-times');
                $('#change_pass').attr("disabled", "");

            } else {
                $('#password2').removeAttr("style", "border:1px solid #db2f2f;")
                $('.test-match').css('color', '#0dbe0d');
                $('#match').attr('class', 'fas fa-check');
                $('#change_pass').removeAttr("disabled", "");
            }
        });
        if (points < 2 && points > 0) {
            resultColor = '#db2f2f';
            resultText = 'Password too weak';
            $('.progress').attr('style', 'visibility:visible');
            $('.progress-bar').attr('style', 'width:25%; background-color:#db2f2f; transition:0.5s ease all');
        }
        if (points == 3) {
            resultColor = 'orange';
            resultText = 'Average password';
            $('.progress').attr('style', 'visibility:visible');
            $('.progress-bar').attr('style', 'width:50%; background-color:#f5a523;transition:0.5s ease all');

        } else if (points >= 4) {
            resultColor = '#0dbe0d';
            resultText = 'Strong password';
            $('.progress').attr('style', 'visibility:visible');
            $('.progress-bar').attr('style', 'width:100%; background-color:#12bd0c;transition:0.5s ease all');
        }

        $('.test-result').css('color', resultColor);
        $('.test-result').html(resultText);
    });
</script>