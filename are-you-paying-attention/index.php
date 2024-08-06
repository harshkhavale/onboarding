<?php
/*
Plugin Name: Are You Paying Attention Quiz
Description: This plugin provides an interactive quiz block for WordPress.
Version: 1.0
Author: Harsh
Author URI: https://github.com/
*/

if (!defined('ABSPATH')) exit;

class AreYouPayingAttention
{
    public function __construct()
    {
        add_action('init', array($this, 'adminAssets'));
    }

    public function adminAssets()
    {
        wp_enqueue_style('quiz-edit-css', plugin_dir_url(__FILE__) . 'build/index.css');
        wp_enqueue_script('ournewblocktype', plugin_dir_url(__FILE__) . 'build/index.js', array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n'), null, true);
        
        register_block_type(__DIR__, array(
            'render_callback' => array($this, 'theHTML')
        ));
    }

    public function theHTML($attributes)
    {
        if (!is_admin()) {
            wp_enqueue_script('attentionFrontend', plugin_dir_url(__FILE__) . 'build/frontend.js', array('wp-element'), null, true);
        }
        
        ob_start(); ?>
        <div class="paying-attention-update-me">
            <pre style="display:none"><?php echo wp_json_encode($attributes)?></pre>
        </div>
        <?php
        return ob_get_clean();
    }
}

$areYouPayingAttention = new AreYouPayingAttention();
