<?php
defined('BASEPATH') or exit('No direct script access allowed');

$nonce = md5(rand(0, 65337) . time()); //generates the salt
$this->session->set_userdata('nonce', $nonce);
?>
<style>
    .btn:hover,
    .btn:focus {
        opacity: 0.9;
        transition: all .3s;
        color: #fff;
    }
</style>
<div class="wrapper wrapper-login wrapper-login-full p-0">
    <div class="login-aside w-50 d-flex flex-column align-items-center justify-content-center text-center login_aside_bg">

    </div>
    <div class="login-aside w-50 d-flex align-items-center justify-content-center bg-white">
        <div class="container container-login container-transparent animated fadeIn">
            <h3 class="text-center">Sign In</h3>
            <div class="login-form">
                <div class="form-group">
                    <label for="username" class="placeholder"><b>Username</b></label>
                    <input id="username" name="username" type="text" class="form-control" autocomplete="off" required>
                </div>
                <div class="form-group">
                    <label for="password" class="placeholder"><b>Password</b></label>
                    <div class="position-relative">
                        <input id="password" name="password" type="password" class="form-control" autocomplete="off" required>
                        <div class="show-password">
                            <i class="icon-eye"></i>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-6 col-10 mb-3">
                        <span id="image"><?php if (isset($captcha)) echo $captcha; ?> </span>
                    </div>
                    <div class="col-md-1 col-2 mb-3">
                        <a class="btn btn-primary btn-captcha" href="javascript:void(0);" title="Refresh" style="margin-top: 1px;margin-left: -7px;padding: 9px 6px;"><i class="fas fa-redo-alt fa-lg"></i></a>
                    </div>
                    <div class="col-md-5 col-12 mb-3">
                        <input type="text" name="captcha" id="captcha" class="form-control" oncopy="return false" onpaste="return false" value="" autocomplete="off" placeholder="Type Captcha" required style="border-color: #3c73e9;">
                    </div>
                </div>
                <div class="form-group">
                    <div style="color:red;" id="err"></div>
                </div>
                <div class="form-group form-action-d-flex mb-3">
                    <a href="#" class="btn btn-submit btn-block btn-secondary mt-3 mt-sm-0 fw-bold">Sign In</a>
                </div>
            </div>
        </div>

    </div>
</div>
<script src="<?= base_url('templates/assets/js/core/jquery.3.2.1.min.js') ?>"></script>
<script src="<?= base_url('templates/js/login.js') ?>"></script>
<script>
    var rand = '<?php echo $nonce; ?>';    
</script>