<?php

/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2018, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2018, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter CAPTCHA Helper
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/helpers/captcha_helper.html
 */
// ------------------------------------------------------------------------

if (!function_exists('create_captcha')) {

    /**
     * Create CAPTCHA
     *
     * @param	array	$data		Data for the CAPTCHA
     * @param	string	$img_path	Path to create the image in (deprecated)
     * @param	string	$img_url	URL to the CAPTCHA image folder (deprecated)
     * @param	string	$font_path	Server path to font (deprecated)
     * @return	string
     */
    function create_captcha($data = '', $img_path = '', $img_url = '', $font_path = '') {
        $defaults = array('word' => '', 'img_path' => '', 'img_url' => '', 'img_width' => '150', 'img_height' => '30', 'font_path' => '', 'expiration' => 7200);
        foreach ($defaults as $key => $val) {
            if (!is_array($data)) {
                if (!isset($$key) OR $ $key == '') {
                    $$key = $val;
                }
            } else {
                $$key = (!isset($data[$key])) ? $val : $data[$key];
            }
        }
        if ($img_path == '' OR $img_url == '') {
            return FALSE;
        }
        if (!@is_dir($img_path)) {
            return FALSE;
        }
        if (!is_writable($img_path)) {
            return FALSE;
        }
        if (!extension_loaded('gd')) {
            return FALSE;
        }
        // -----------------------------------
        // Remove old images
        // -----------------------------------
        list($usec, $sec) = explode(" ", microtime());
        $now = ((float) $usec + (float) $sec);
        $current_dir = @opendir($img_path);
        while ($filename = @readdir($current_dir)) {
            if ($filename != "." and $filename != ".." and $filename != "index.html") {
                $name = str_replace(".jpg", "", $filename);
                if (($name + $expiration) < $now) {
                    @unlink($img_path . $filename);
                }
            }
        }
        @closedir($current_dir);
        // -----------------------------------
        // Do we have a "word" yet?
        // -----------------------------------
        if ($word == '') {
            $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $str = '';
            for ($i = 0; $i < 8; $i++) {
                $str .= substr($pool, mt_rand(0, strlen($pool) - 1), 1);
            }
            $word = $str;
        }
        // -----------------------------------
        // Determine angle and position
        // -----------------------------------
        $length = strlen($word);
        $angle = ($length >= 6) ? rand(-($length - 6), ($length - 6)) : 0;
        $x_axis = rand(6, (360 / $length) - 16);
        $y_axis = ($angle >= 0 ) ? rand($img_height, $img_width) : rand(6, $img_height);
        // -----------------------------------
        // Create image
        // -----------------------------------
        // PHP.net recommends imagecreatetruecolor(), but it isn't always available
        if (function_exists('imagecreatetruecolor')) {
            $im = imagecreatetruecolor($img_width, $img_height);
        } else {
            $im = imagecreate($img_width, $img_height);
        }
        // -----------------------------------
        //  Assign colors
        // -----------------------------------
        $bg_color = imagecolorallocate($im, 255, 255, 255);
        $border_color = imagecolorallocate($im, 255, 255, 255);
        $text_color = imagecolorallocate($im, 0, 0, 0);
        $grid_color = imagecolorallocate($im, 255, 190, 190);
        $shadow_color = imagecolorallocate($im, 255, 240, 240);
        // -----------------------------------
        //  Create the rectangle
        // -----------------------------------
        ImageFilledRectangle($im, 0, 0, $img_width, $img_height, $bg_color);
        // -----------------------------------
        //  Create the spiral pattern
        // -----------------------------------
        $theta = 1;
        $thetac = 7;
        $radius = 16;
        $circles = 20;
        $points = 32;
        for ($i = 0; $i < ($circles * $points) - 1; $i++) {
            $theta = $theta + $thetac;
            $rad = $radius * ($i / $points );
            $x = ($rad * cos($theta)) + $x_axis;
            $y = ($rad * sin($theta)) + $y_axis;
            $theta = $theta + $thetac;
            $rad1 = $radius * (($i + 1) / $points);
            $x1 = ($rad1 * cos($theta)) + $x_axis;
            $y1 = ($rad1 * sin($theta)) + $y_axis;
            imageline($im, $x, $y, $x1, $y1, $grid_color);
            $theta = $theta - $thetac;
        }
        // -----------------------------------
        //  Write the text
        // -----------------------------------
        $use_font = ($font_path != '' AND file_exists($font_path) AND function_exists('imagettftext')) ? TRUE : FALSE;
        if ($use_font == FALSE) {
            $font_size = 18;
            $x = rand(0, $img_width / ($length / 1));
            $y = 0;
        } else {
            $font_size = 35;
            $x = rand(0, $img_width / ($length / 1));
            $y = $font_size + 2;
        }
        for ($i = 0; $i < strlen($word); $i++) {
            if ($use_font == FALSE) {
                $y = rand(0, $img_height / 2);
                imagestring($im, $font_size, $x, $y, substr($word, $i, 1), $text_color);
                $x += ($font_size * 2);
            } else {
                $y = rand($img_height / 2, $img_height - 3);
                imagettftext($im, $font_size, $angle, $x, $y, $text_color, $font_path, substr($word, $i, 1));
                $x += $font_size;
            }
        }
        // -----------------------------------
        //  Create the border
        // -----------------------------------
        imagerectangle($im, 0, 0, $img_width - 1, $img_height - 1, $border_color);
        // -----------------------------------
        //  Generate the image
        // -----------------------------------
        $img_name = $now . '.jpg';
        ImageJPEG($im, $img_path . $img_name);
        $img_url = $img_url . '/';
        $img = "<img src=\"$img_url$img_name\" width=\"$img_width\" height=\"$img_height\" style=\"border:0;\" alt=\" \" />";
        ImageDestroy($im);
        return array('word' => $word, 'time' => $now, 'image' => $img);
    }

}
