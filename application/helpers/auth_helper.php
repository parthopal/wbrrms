<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('checkAdminAuthSession')) {

    function checkAdminAuthSession() {
        $CI = & get_instance();
        $user = $CI->session->userdata('user');
        if (!isset($user) && !$user) {
            echo "<script type='text/javascript'>window.top.location='" . base_url('admin_login') . "';</script>";
            exit;
        }
    }

}

if (!function_exists('checkAuthSession')) {

    function checkAuthSession() {
        $CI = & get_instance();
//        $userid = $CI->session->userdata('userid');
//        $role_id = $CI->session->userdata('role_id');
        $user = $CI->session->userdata('user');
        if (!isset($user) && !$user) {
            echo "<script type='text/javascript'>window.top.location='" . base_url('/') . "';</script>";
            exit;
        }
//        $timeout = 12;
//        $timeout = $timeout * 60;
//        if ($CI->session->userdata('start_time') != '') {
//            $elapsed_time = time() - $CI->session->userdata('start_time');
//
//            if ($elapsed_time >= $timeout) {
//                //$CI->session->sess_destroy();
//                redirect(base_url('login/sign_out'));
//            }
//        }
//
//
//        $CI->session->set_userdata('start_time', time());
//        if ($userid == '') {
//            redirect(base_url('login/sign_out'));
//        }
//        if ($role_id == '') {
//            redirect(base_url('login/sign_out'));
//        }
//        if ($user == '') {
//            redirect(base_url('login/sign_out'));
//        }
    }

}
if (!function_exists('csrf_generate')) {

    function csrf_generate() {
        $CI = & get_instance();
        $randm = md5(rand(0, 65337) . time());
        $CI->session->set_userdata('randm', $randm);
        echo '<input type="hidden" name="csrf_rand" id="csrf_rand"  value="' . $randm . '">';
    }

}

if (!function_exists('csrf_check')) {

    function csrf_check() {
        $CI = & get_instance();
        $randm = $CI->session->userdata('randm');
        if ($_POST['csrf_rand']) {
            if ($CI->input->post('csrf_rand') != $randm) {
                ?>
                <script>
                    var ch = "<?php echo $dis[$j]; ?>";
                    alert("Something went wrong");
                    location.href = '<?php echo base_url('dashboard'); ?>';</script>
                <?php
                exit();
            }
        }
    }

}

if (!function_exists('is_admin')) {

    function is_admin() {
        $CI = & get_instance();
        $key = $CI->session->userdata('user');
        $k = json_decode($CI->encryption->decrypt($key), true);
        if ($k['role_id'] != 1) {
            ?>
            <script>
                alert("you are not authorized to view this page");
                location.href = '<?php echo base_url(); ?>dashboard';</script>
            <?php
            exit();
        }
    }

}

if (!function_exists('is_head')) {

    function is_head() {
        $CI = & get_instance();
        $key = $CI->session->userdata('user');
        $k = json_decode($CI->encryption->decrypt($key), true);
//        $roleid = $CI->session->userdata('role_id');
        if ($k['role_id'] != 1 && ($k['role_id'] != 2 && $k['type_id'] != 1)) {
            ?>
            <script>
                alert("you are not authorized to view this page");
                location.href = '<?php echo base_url(); ?>dashboard';</script>
            <?php
            exit();
        }
    }

}
if (!function_exists('session_logs')) {

    function session_logs($username, $action) {
        $CI = & get_instance();
        $ip = getenv('HTTP_CLIENT_IP') ?:
                getenv('HTTP_X_FORWARDED_FOR') ?:
                getenv('HTTP_X_FORWARDED') ?:
                getenv('HTTP_FORWARDED_FOR') ?:
                getenv('HTTP_FORWARDED') ?:
                getenv('REMOTE_ADDR');
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $user_referer = $_SERVER['REQUEST_URI'];
        $input = array('username' => $username, 'ip' => $ip, 'user_agent' => $user_agent, 'user_referer' => $user_referer, 'action' => $action);
        $CI->db->insert(SESSION_LOGS, $input);
    }

}

if (!function_exists('redirect')) {

    function redirect($url) {
        echo "<script type='text/javascript'>window.top.location='" . base_url($url) . "';</script>";
        exit;
    }

}

if (!function_exists('session_logs_app')) {

    function session_logs_app($username, $ip, $user_referer, $action) {
        $CI = & get_instance();
        $user_agent = 'mobile_apps';
        $input = array('username' => $username, 'ip' => $ip, 'user_agent' => $user_agent, 'user_referer' => $user_referer, 'action' => $action);
        $CI->db->insert(SESSION_LOGS, $input);
    }

}

if (!function_exists('generate_auth_token')) {

    function generate_auth_token($user_id, $imei) {
        $CI = & get_instance();
        $time = time();
        $authtoken_key = hash('sha256', $time);
        $input = array('user_id' => $user_id, 'imei' => $imei, 'authtoken_key' => $authtoken_key, 'generated_timestamp' => date('Y-m-d H:i:s', $time), 'last_access' => date('Y-m-d H:i:s', $time));
        $CI->db->insert(AUTHTOKEN_LOGS, $input);
        return $authtoken_key;
    }

}

if (!function_exists('validate_auth_token')) {

    function validate_auth_token($user_id, $imei, $token) {
        if ($token != '') {
            $CI = & get_instance();
            $CI->db->select('MAX(IFNULL(id, 0)) as id, last_access');
            $CI->db->where(array(
                'user_id' => $user_id,
                'imei' => $imei,
                'authtoken_key' => $token,
                'logout' => 0
            ));
            $CI->db->group_by('last_access');
            $query = $CI->db->get(AUTHTOKEN_LOGS);
            $row = $query->row();
            if ($query->num_rows() > 0) {
                $last_access = $row->last_access;
                $time = date('Y-m-d H:i:s');
                if ((strtotime($time) - strtotime($last_access)) < (60 * 60)) {
                    $input = array('last_access' => $time);
                    $CI->db->where('id', $row->id);
                    $CI->db->update(AUTHTOKEN_LOGS, $input);
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

}