<?php
defined('BASEPATH') or exit('No direct script access allowed');

$district = json_decode($district);
?>
<div class="header sticky">
    <!-- topbar -->
    <section class="topsection">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-2 col-12 p-0">
                    <!-- Left menu grid section -->
                    <div class="menu-button-grid">
                        <div class="menu-grid-button" id="main" onclick="openNav()">
                            <p>Menu<i class="fa fa-bars"></i></p>
                        </div>
                        <div class="leftsidenav sidebar" id="mySidebar">
                            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
                            <a class="dropdown-item" href="organogram.html">Organogram</a>
                            <a class="dropdown-item" href="organization-offices.html">Organisations &amp;
                                Offices</a>
                            <a class="dropdown-item" href="acts-and-rules.html">Acts &amp; Rules</a>
                            <a class="dropdown-item" href="rti-acts.html">RTI Acts</a>
                            <a class="dropdown-item" href="news.html">News &amp; Media</a>
                            <a class="dropdown-item" href="panchayati-raj.html">Panchayati Raj - Samachar Patrika</a>
                            <a class="dropdown-item" href="image-galleries.html">Image Galleries</a>
                            <a class="dropdown-item" href="video-galleries.html">Video Galleries</a>
                            <a class="dropdown-item" href="tenders/list.html" target="_blank">P&RD Tenders</a>
                        </div>
                    </div>
                    <!-- /end -->
                </div>
                <div class="col-md-2" style="display: none;">
                    <audio controls autoplay id="audio" style="height: 20px; margin-top: 10px;">
                        <source src="<?= base_url('web/audio/Pathashree_Rastashree.mp3') ?>" type="audio/ogg">
                        <source src="<?= base_url('web/audio/Pathashree_Rastashree.mp3') ?>" type="audio/mpeg">
                    </audio>
                </div>
                <div class="col-lg-8 col-12">
                    <!-- Right Menu grid section -->
                    <div class="menu-grid-search-button">
                        <!-- /search bar -->
                        <div class="menu-grid-search">
                            <form class="form">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="" />
                                    <div class="menu-search-sraerchbx">
                                        <i class="fas fa-search"></i>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- /end -->
                        <!-- increment decrement button -->
                        <div class="menu-grid-button-group">
                            <ul class="menu-right-group-button">
                                <li><a href="help.html#screen" class="btn-access">Screen Reader Access</a></li>
                                <li class="readeroption">
                                    <a href="javascript:decreaseFontSize();" title="Decrease Font">A<sup>-</sup></a>
                                </li>
                                <li class="readeroption">
                                    <a href="javascript:defaultFontSize();" title="Default Font">A</a>
                                </li>
                                <li class="readeroption">
                                    <a href="javascript:increaseFontSize();" title="Increase Font">A<sup>+</sup></a>
                                </li>
                                <li class="readeroption colorchangebg">
                                    <a href="javascript:void(0);" title="Colour Change">A</a>
                                </li>
                            </ul>
                        </div>
                        <!-- /end -->
                        <!-- Google translator -->
                        <div class="menu-grid-translate">
                            <div>
                                <div id="google_translate_element"></div>
                            </div>
                        </div>
                        <!-- /end -->
                    </div>
                    <!-- /end -->
                </div>
            </div>
        </div>
    </section>
    <!-- /end -->
    <!-- header -->
    <section class="main-header">
        <div class="site-wrapper">
            <div class="row">
                <div class="site-inner-wrapper">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-lg-4 col-md-4 col-12">
                            <div class="logobx">
                                <a href="index.html">
                                    <img src="<?= base_url('web/img/logo_bg_white.jpg') ?>" alt="logo" width="100%" />
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-8 col-12">
                            <nav class="navbar navbar-expand-lg navbar-light menuprtl">
                                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                        aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="navbar-toggler-icon"></span>
                                </button>
                                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                    <ul class="navbar-nav">
										<li class="nav-item"><img src="web/images/helpline.png" alt="" class="img-fluid" style="width: 300px;" /></li>
                                        <li class="nav-item active">
                                            <a class="nav-link" href="index.html">Home</a>
                                        </li>
                                        <li class="nav-item">
                                            <a target="_blank" href="/login" class="nav-link"
                                               style="background: #58a817;color: #fff;border: 1px solid #51a310;">Login</a>
                                        </li>
                                        <li class="nav-item" style="margin-left: 20px;">
                                            <a href="https://play.google.com/store/apps/details?id=in.wbrrms" target="_blank"><img src="<?= base_url('web/img/download_pathashree_app.png') ?>" alt="" class="img-fluid" style="width: 110px;" /></a>
                                        </li>
                                    </ul>
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /end -->
</div>
<!-- Announcement section -->
<!-- /end -->
<!-- banner section -->
<section class="main-banner">
    <div class="bannr-slide">
        <div class="bannerimage">
            <img src="<?= base_url('web/services/resources/Banners/pathashree_banner_1.jpg') ?>" alt="banner image" class="img-fluid" />
        </div>
        <div class="bannerimage">
            <img src="<?= base_url('web/services/resources/Banners/pathashree_banner_2.jpg') ?>" alt="banner image" class="img-fluid" />
        </div>
        <div class="bannerimage">
            <img src="<?= base_url('web/services/resources/Banners/pathashree_banner_3.jpg') ?>" alt="banner image" class="img-fluid" />
        </div>
        <div class="bannerimage">
            <img src="<?= base_url('web/services/resources/Banners/pathashree_banner_4.jpg') ?>" alt="banner image" class="img-fluid" />
        </div>
    </div>
</section>
<!-- /end -->
<section class="site-wrapper" style="background: #f8f8f8; padding: 40px 0 40px 0;">
    <div class="site-inner-wrapper">
        <h3 class="text-center">List of Roads</h3>
        <form>
            <div class="row mb-5">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Select District</label>
                        <select id="district_id" name="district_id" class="form-control dropdown">
                            <?php
                            echo '<option value="">--Select District--</option>';
                            foreach ($district as $row) {
                                echo '<option value="' . $row->id . '">' . $row->name . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Select Block</label>
                        <select id="block_id" name="block_id" class="form-control dropdown">
                            <option value="">--Select Block--</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Select GP</label>
                        <select id="gp_id" name="gp_id" class="form-control dropdown">
                            <option value="">--Select GP--</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label></label><br />
                        <button id="search" class="btn btn-primary btn-block">Search</button>
                    </div>
                </div>
            </div>
        </form>
        <div class="col-md-12 mb-5">
            <div class="table-responsive">
                <table class="table table-responsive table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>District</th>
                            <th>Block</th>
                            <th>GP</th>
                            <th>Road Name</th>
                            <th>Road Length<br><i>(in KM)</i></th>
                        </tr>
                    </thead>
                    <tbody id="tbd"></tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<!-- footer section -->
<section class="footer">
    <div class="site-wrapper">
        <div class="row">
            <div class="site-inner-wrapper">
                <div class="row">
                    <div class="col-lg-10 col-md-10 col-12 offset-md-1 offset-lg-1">
                        <div class="othersportal">
                            <div class="portalslogo">
                                <div>
                                    <div class="logoimgportal">
                                        <a href="https://india.gov.in/" target="_blank"><img src="https://prd.wb.gov.in/img/india-gov-logo.png" alt="" class="img-fluid" /></a>
                                    </div>
                                </div>
                                <div>
                                    <div class="logoimgportal">
                                        <a href="http://mygov.in/" target="_blank"><img src="https://prd.wb.gov.in/img/my_gov_logo.png" alt="" class="img-fluid" /></a>
                                    </div>
                                </div>
                                <div>
                                    <div class="logoimgportal">
                                        <a href="http://evisitors.nic.in/public/Home.aspx" target="_blank"><img src="https://prd.wb.gov.in/img/my_visit.png" alt="" class="img-fluid" /></a>
                                    </div>
                                </div>
                                <div>
                                    <div class="logoimgportal mt-2">
                                        <a href="https://www.indianrail.gov.in/enquiry/StaticPages/StaticEnquiry.jsp?StaticPage=index.html" target="_blank"><img src="https://prd.wb.gov.in/img/footer-logo05.jpg" alt="" class="img-fluid" /></a>
                                    </div>
                                </div>
                                <div>
                                    <div class="logoimgportal">
                                        <a href="http://digitalindia.gov.in/" target="_blank"><img src="https://prd.wb.gov.in/img/digital_india.png" alt="" class="img-fluid" /></a>
                                    </div>
                                </div>
                                <div>
                                    <div class="logoimgportal">
                                        <a href="http://mhrd.gov.in/rti_he" target="_blank"><img src="https://prd.wb.gov.in/img/rti_logo.png" alt="" class="img-fluid" /></a>
                                    </div>
                                </div>
                                <div>
                                    <div class="logoimgportal mt-2">
                                        <a href="https://goidirectory.gov.in/" target="_blank"><img src="https://prd.wb.gov.in/img/footer-logo07.jpg" alt="" class="img-fluid" /></a>
                                    </div>
                                </div>
                                <div>
                                    <div class="logoimgportal">
                                        <a href="https://data.gov.in/" target="_blank"><img
                                                src="https://prd.wb.gov.in/img/datagov_logo.png" alt=""
                                                class="img-fluid" /></a>
                                    </div>
                                </div>
                                <div>
                                    <div class="logoimgportal">
                                        <a href="http://www.nvsp.in/" target="_blank"><img src="https://prd.wb.gov.in/img/onlineservice_logo.png" alt="" class="img-fluid" /></a>
                                    </div>
                                </div>
                                <div>
                                    <div class="logoimgportal">
                                        <a href="http://dial.gov.in/" target="_blank"><img src="https://prd.wb.gov.in/img/dialgov-logo.png" alt="" class="img-fluid" /></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /end -->
<section class="main-footer">
    <div class="mainftrbgimg">
        <img src="https://prd.wb.gov.in/img/mainfooterbg.png" alt="" class="img-fluid" />
    </div>
    <div class="site-wrapper">
        <div class="row">
            <div class="site-inner-wrapper">
                <div class="footnav">
                    <ul class="footer-menu">
                        <li><a href="https://prd.wb.gov.in/contact-us">Contact Us</a></li>
                        <li><a href="https://prd.wb.gov.in/help">Help</a></li>
                        <li><a href="https://prd.wb.gov.in/feed-back">Feedback</a></li>
                        <li><a href="https://prd.wb.gov.in/website-policies">Website Policy</a></li>
                        <li><a href="https://prd.wb.gov.in/rti-acts">RTI</a></li>
                        <li><a href="https://prd.wb.gov.in/site-map">Site Map</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- copyright section -->
<div class="copyright">
    <div class="site-wrapper">
        <div class="row">
            <div class="site-inner-wrapper">
                <div class="copyinner">
                    <div class="innercopytext">
                        <p>
                            Panchayat & Rural Development Department, Govt. of West Bengal © 2022
                            Site best viewed with 1920x1080 resolution in Google
                            Chrome 31.0.1650.63, Firefox 55.0.2, Safari 5.1.7 & IE 11.0 and above
                        </p>
                    </div>
                    <!-- <div class="visitor-count">
                            <h4>Current Visitors : 5638</h4>
                            <h4>Total Visitors : 281893</h4>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /end -->
<script src="<?= base_url('templates/assets/js/core/jquery.3.2.1.min.js') ?>"></script>
<script src="<?= base_url('web/js/web.js') ?>"></script>
