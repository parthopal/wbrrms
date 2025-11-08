<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
    <?php include './templates/common/head.php'; ?>
    <body>
        <div class="wrapper">
            <div class="main-header">
                <?php include './templates/common/logo.php'; ?>
                <?php include './templates/common/navbar.php'; ?>
            </div>
            <?php include './templates/common/sidebar.php'; ?>
            <div class="main-panel">
                <?= $content ?>
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="copyright ml-auto">
                            © 2022 Panchayat & Rural Development Department, Govt. of West Bengal</a>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <?php include './templates/common/notification.php' ?>
        <?php include './templates/common/plugins.php' ?>
    </body>
</html>