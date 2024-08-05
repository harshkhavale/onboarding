<?php
/*
Plugin Name: Our Test Plugin
Description: This plugin tests the functionality.
Version: 1.0
Author: Harsh Khavale
Text Domain: wcpdomain
Domain Path: /languages
*/

class WordCountAndTimePlugin
{
    function __construct()
    {
        add_action('admin_menu', array($this, 'adminPage'));
        add_action('admin_init', array($this, 'settings'));
        add_filter('the_content', array($this, 'ifWrap'));
        add_action('init', array($this, 'languages'));
    }

    function languages()
    {
        load_plugin_textdomain('wcpdomain', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    function settings()
    {
        add_settings_section('wpc_first_section', null, null, 'word-count-settings-page');

        add_settings_field('wpc_location', 'Display Location', array($this, 'locationHTML'), 'word-count-settings-page', 'wpc_first_section');
        register_setting('wordcountplugin', 'wpc_location', array('sanitize_callback' => array($this, 'sanitizeLocation'), 'default' => '0'));

        add_settings_field('wpc_headline', 'Headline Text', array($this, 'headlineHTML'), 'word-count-settings-page', 'wpc_first_section');
        register_setting('wordcountplugin', 'wpc_headline', array('sanitize_callback' => 'sanitize_text_field', 'default' => 'Post Statistics'));

        add_settings_field('wpc_wordcount', 'Word Count', array($this, 'checkboxHTML'), 'word-count-settings-page', 'wpc_first_section', array('theName' => 'wpc_wordcount'));
        register_setting('wordcountplugin', 'wpc_wordcount', array('sanitize_callback' => 'intval', 'default' => '1'));

        add_settings_field('wpc_charcount', 'Character Count', array($this, 'checkboxHTML'), 'word-count-settings-page', 'wpc_first_section', array('theName' => 'wpc_charcount'));
        register_setting('wordcountplugin', 'wpc_charcount', array('sanitize_callback' => 'intval', 'default' => '1'));

        add_settings_field('wpc_readtime', 'Read Time', array($this, 'checkboxHTML'), 'word-count-settings-page', 'wpc_first_section', array('theName' => 'wpc_readtime'));
        register_setting('wordcountplugin', 'wpc_readtime', array('sanitize_callback' => 'intval', 'default' => '1'));
    }

    function ifWrap($content)
    {
        if (is_main_query() && is_single() && (
            get_option('wpc_wordcount', '1') ||
            get_option('wpc_charcount', '1') ||
            get_option('wpc_readtime', '1'))) {
            return $this->createHtml($content);
        }
        return $content;
    }

    function sanitizeLocation($input)
    {
        if ($input != '0' && $input != '1') {
            add_settings_error('wpc_location', 'wpc_location_error', 'Display location must be either beginning or end.');
            return get_option('wpc_location');
        }
        return $input;
    }

    function checkboxHTML($args)
    {
    ?>
        <input type="checkbox" name="<?php echo esc_attr($args['theName']); ?>" value="1" <?php checked(get_option($args['theName']), '1'); ?>>
    <?php
    }

    function headlineHTML()
    {
    ?>
        <input type="text" name="wpc_headline" value="<?php echo esc_attr(get_option('wpc_headline')); ?>">
    <?php
    }

    function locationHTML()
    {
    ?>
        <select name="wpc_location">
            <option value="0" <?php selected(get_option('wpc_location'), '0'); ?>>Beginning of post</option>
            <option value="1" <?php selected(get_option('wpc_location'), '1'); ?>>End of post</option>
        </select>
    <?php
    }

    function adminPage()
    {
        add_options_page('Word Count Settings',esc_html__('Word Count', 'wcpdomain'), 'manage_options', 'word-count-settings-page', array($this, 'ourHTML'));
    }

    function ourHTML()
    {
    ?>
        <div class="wrap">
            <h1><?php esc_html__('Word Count Settings', 'wcpdomain'); ?></h1>
            <form action="options.php" method="POST">
                <?php
                settings_fields('wordcountplugin');
                do_settings_sections('word-count-settings-page');
                submit_button();
                ?>
            </form>
        </div>
    <?php
    }

    function createHtml($content)
    {
        $html = '<h3>' . esc_html(get_option('wpc_headline', 'Post Statistics')) . '</h3><p>';
        $wordCount = str_word_count(strip_tags($content));
        $charCount = strlen(strip_tags($content));
        $readTime = round($wordCount / 225);

        if (get_option('wpc_wordcount', '1')) {
            $html .= sprintf(esc_html__('This post has %s words', 'wcpdomain'), $wordCount) . '<br>';
        }
        if (get_option('wpc_charcount', '1')) {
            $html .= sprintf(esc_html__('This post has %s characters', 'wcpdomain'), $charCount) . '<br>';
        }
        if (get_option('wpc_readtime', '1')) {
            $html .= sprintf(esc_html__('This post will take about %s minute(s) to read', 'wcpdomain'), $readTime) . '<br>';
        }

        $html .= '</p>';

        if (get_option('wpc_location', '0') == '0') {
            return $html . $content;
        }
        return $content . $html;
    }
}

$wordCountAndTimePlugin = new WordCountAndTimePlugin();
?>
