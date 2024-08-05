<?php
/*
Plugin Name: Our Word Filter Plugin
Description: Replaces a list of words.
Version: 1.0
Author: Harsh Khavale
Author URI: https://www.linkedin.com/in/harsh-khavale-622a841b6/
*/

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class WordFilterPlugin
{
    function __construct()
    {
        add_action('admin_menu', array($this, 'ourMenu'));
        add_action('admin_init', array($this, 'ourSettings'));

        if (get_option('plugin_words_to_filter')) {
            add_filter('the_content', array($this, 'filterLogic'));
        }
    }
    function filterLogic($content)
    {
        $badWords = explode(',', get_option('plugin_words_to_filter'));
        $badWordsTrimmed = array_map('trim', $badWords);
        return str_ireplace($badWordsTrimmed, esc_html(get_option('replacementText')), $content);
    }
    function ourSettings()
    {
        add_settings_section(
            'replacement-text-section',
            null,
            null,
            'word-filter-options'
        );
        register_setting('replacementFields','replacementText');
        add_settings_field('replacement-text','Filtered Text',array($this,'replacementFieldHTML'),'word-filter-options','replacement-text-section');

    }
    function replacementFieldHTML(){
        ?>
        <input type="text" name="replacementText" value="<?php echo esc_attr(get_option('replacementText','****'))?>">
        <p class="description">Leave blank to simply remove the filtered words.</p>
        <?php
    }
    function ourMenu()
    {
        $mainPageHook = add_menu_page(
            'Words To Filter',
            'Word Filter',
            'manage_options',
            'ourwordfilter',
            array($this, 'wordFilterPage'),
            'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IS0tIFVwbG9hZGVkIHRvOiBTVkcgUmVwbywgd3d3LnN2Z3JlcG8uY29tLCBHZW5lcmF0b3I6IFNWRyBSZXBvIE1peGVyIFRvb2xzIC0tPg0KPHN2ZyB3aWR0aD0iODAwcHgiIGhlaWdodD0iODAwcHgiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4NCjxwYXRoIGQ9Ik0zIDE4SDdNMTAgMThIMjFNNSAyMUgxMk0xNiAyMUgxOU04LjggMTVDNi4xNDkwMyAxNSA0IDEyLjk0NjYgNCAxMC40MTM3QzQgOC4zMTQzNSA1LjYgNi4zNzUgOCA2QzguNzUyODMgNC4yNzQwMyAxMC41MzQ2IDMgMTIuNjEyNyAzQzE1LjI3NDcgMyAxNy40NTA0IDQuOTkwNzIgMTcuNiA3LjVDMTkuMDEyNyA4LjA5NTYxIDIwIDkuNTU3NDEgMjAgMTEuMTQwMkMyMCAxMy4yNzE5IDE4LjIwOTEgMTUgMTYgMTVMOC44IDE1WiIgc3Ryb2tlPSIjMDAwMDAwIiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPg0KPC9zdmc+',
            100
        );

        add_submenu_page(
            'ourwordfilter',
            'Words To Filter',
            'Word List',
            'manage_options',
            'ourwordfilter',
            array($this, 'wordFilterPage')
        );

        add_submenu_page(
            'ourwordfilter',
            'Word Filter Options',
            'Options',
            'manage_options',
            'word-filter-options',
            array($this, 'optionsSubPage')
        );

        add_action("load-{$mainPageHook}", array($this, 'mainPageAssets'));
    }

    function mainPageAssets()
    {
        wp_enqueue_style('filterAdminCss', plugin_dir_url(__FILE__) . 'styles.css');
    }

    function handleForm()
    {
        if (wp_verify_nonce($_POST['ourNonce'], 'saveFilterWords') and current_user_can('manage_options')) {
            update_option('plugin_words_to_filter', sanitize_text_field($_POST['plugin_words_to_filter']));
?>
            <div class="updated">
                <p>Your filtered words were saved.</p>
            </div>
        <?php
        } else {
        ?>
            <div class="error">
                <p>Sorry, you do not have permission to perform that action.</p>
            </div>
        <?php
        }
    }

    function wordFilterPage()
    {
        ?>
        <div class="wrap">
            <h1>Word Filter</h1>
            <?php
            if (isset($_POST['justsubmitted']) && $_POST['justsubmitted'] == "true") {
                $this->handleForm();
            }
            ?>
            <form method="POST">
                <input type="hidden" name="justsubmitted" value="true">
                <?php wp_nonce_field('saveFilterWords', 'ourNonce') ?>
                <label for="plugin_words_to_filter">Enter a <strong>comma-separated</strong> list of words to filter from your site's content.</label>
                <div class="word-filter__flex-container">
                    <textarea name="plugin_words_to_filter" id="plugin_words_to_filter" placeholder="bad, horrible, disaster"><?php echo esc_textarea(get_option('plugin_words_to_filter')) ?></textarea>
                </div>
                <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
            </form>
        </div>
    <?php
    }

    function optionsSubPage()
    {
    ?>
        <div class="wrap">
            <h1>Word Filter Options</h1>
            <form action="options.php" method="POST">
                <?php
                settings_errors();
                settings_fields('replacementFields');
                do_settings_sections('word-filter-options');
                submit_button();
                ?>
            </form>
        </div>
<?php
    }
}

$wordFilterPlugin = new WordFilterPlugin();
?>