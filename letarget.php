<?php
/*
Plugin Name: Le Target Custom Title Prefix
Plugin URI: https://github.com/bodyan/LeTarget-Title-Prefix
Description: This awesome plugin allows you to add custom prefix to your post title 
Author: bodyan
Version: 1.0
Author URI: https://github.com/bodyan/LeTarget-Title-Prefix
 */

defined('ABSPATH') || die('Direct access denied!');

require_once (plugin_dir_path(__FILE__) . 'classes/class-letarget.php');

add_action('init', array('LeTargetTitlePrefix', 'init'));



