<?php
/*
Plugin Name: Are you paying attention Quiz
Description: This plugin provides information 
Version: 1.0
Author: Harsh
Author URI: https://github.com/
*/

if (!defined('ABSPATH')) exit;

class AreYouPayingAttention
{
    function __construct()
    {
        add_action('init', array($this, 'adminAssets'));
    }

    function adminAssets()
    {
        wp_register_script('ournewblocktype', plugin_dir_url(__FILE__) . 'build/index.js', array('wp-blocks'));
        register_block_type('ourplugin/are-you-paying-attention', array(
            'editor_scripts' => 'ournewblocktype',
            'render_callback' => array($this, 'theHTML')
        ));
    }
    function theHTML($attributes)
    {
        ob_start();?>
        <h3>
            Today the sky is <?php $attributes['skyColor']?> and the grass is <?php $attributes['grassColor'] ?>
        </h3>
        <?php
        ob_get_clean();
    }
}

$areYouPayingAttention = new AreYouPayingAttention();
