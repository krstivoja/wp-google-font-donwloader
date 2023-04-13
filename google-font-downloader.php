<?php

/*
Plugin Name: Google Fonts Downloader
Plugin URI: https://example.com/plugins/google-fonts-downloader
Description: This plugin allows you to download Google Fonts for offline use.
Version: 1.0
Author: Your Name
Author URI: https://example.com/
License: GPL2
*/


function gfd_add_admin_menu() {
    add_menu_page(
        'Google Fonts Downloader',
        'Google Fonts Downloader',
        'manage_options',
        'google-fonts-downloader',
        'gfd_options_page',
        'dashicons-download',
        90
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

    $argc = 2;
    $argv = [
        'download.php',
        'https://fonts.googleapis.com/css?family=Akronim|Fira+Sans:100,400,400i,700|Lacquer|Odibee+Sans&display=swap',
    ];

    include 'vendor/autoload.php';
    include_once 'download.php';

    

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

