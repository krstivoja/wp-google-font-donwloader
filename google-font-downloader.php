<?php
/**
 * Plugin Name: Google Fonts Downloader
 * Description: Download Google Fonts and create a SCSS file.
 * Version: 1.0
 * Author: Your Name
 */

// $argc = 2;
// $argv = [
//     'download.php',
//     'https://fonts.googleapis.com/css?family=Roboto',
// ];

// include 'vendor/autoload.php';
// include_once 'download.php';

// function gfd_add_admin_menu()
// {
//     add_submenu_page(
//         'options-general.php',
//         'Google Fonts Downloader',
//         'Google Fonts Downloader',
//         'manage_options',
//         'google-fonts-downloader',
//         'gfd_options_page'
//     );
// }
// add_action('admin_menu', 'gfd_add_admin_menu');





$argc = 2;
$argv = [
    'download.php',
    'https://fonts.googleapis.com/css?family=Akronim|Fira+Sans:100,400,400i,700|Lacquer|Odibee+Sans&display=swap',
];

include 'vendor/autoload.php';
include_once 'download.php';

function gfd_add_admin_menu()
{
    add_submenu_page(
        'options-general.php',
        'Google Fonts Downloader',
        'Google Fonts Downloader',
        'manage_options',
        'google-fonts-downloader',
        'gfd_options_page'
    );
}
add_action('admin_menu', 'gfd_add_admin_menu');

/**
 * Display the options page
 */
function gfd_options_page()
{
    // Check if the form was submitted
    if (isset($_POST['font_url'])) {
        // Get the font URL from the form data
        $fontUrl = $_POST['font_url'];

        // Call the download function with the font URL
        $result = gfd_download_fonts($fontUrl);

        // Display the result message
        if ($result === true) {
            echo '<div class="notice notice-success"><p>Fonts downloaded successfully.</p></div>';
        } else {
            echo '<div class="notice notice-error"><p>Failed to download fonts: ' . $result . '</p></div>';
        }
    }

    // Display the options page form
    ?>
    <div class="wrap">
        <h1>Google Fonts Downloader</h1>
        <form method="POST">
            <label for="font_url">Google Fonts CSS URL:</label>
            <input type="text" id="font_url" name="font_url" required>
            <?php wp_nonce_field('gfd_download_fonts', 'gfd_download_fonts_nonce'); ?>
            <?php submit_button('Download Fonts'); ?>
        </form>

        <p>https://fonts.googleapis.com/css?family=Roboto</p>
    </div>
    <?php
}


/**
 * Download the fonts from the specified URL
 *
 * @return bool|string True on success, or an error message on failure
 */
function gfd_download_fonts()
{
    // Check the nonce
    if (!isset($_POST['gfd_download_fonts_nonce']) || !wp_verify_nonce($_POST['gfd_download_fonts_nonce'], 'gfd_download_fonts')) {
        return 'Invalid nonce';
    }

    // Get the font URL from the form data
    $fontUrl = isset($_POST['font_url']) ? trim($_POST['font_url']) : '';

    // Check if the font URL is valid
    $components = parse_url($fontUrl);
    if ($components === false || !isset($components['host']) || $components['host'] !== 'fonts.googleapis.com') {
        return 'Invalid font URL';
    }

    // Call the download function with the font URL
    $argc = 2;
    $argv = [
        'download.php',
        $fontUrl,
    ];
    try {
        gfd_download($argv);
        return true;
    } catch (Exception $e) {
        return $e->getMessage();
    }

    // Include the required files
    require_once 'vendor/autoload.php';
    require_once 'download.php';
}
