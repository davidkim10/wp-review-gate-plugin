<?php

/**
 * rg_asset_loader()
 * Loads an asset from a CDN with a backup to a local file.
 *
 * @param string $type      The type of the asset. Can be 'css' or 'js'.
 * @param string $handle    A unique handle or name for the asset.
 * @param string $cdn_url   The CDN URL for the asset.
 * @param string $local_file The local file path for the asset.
 */

 function rg_asset_loader($type, $handle, $cdn_url, $local_file, $deps = array(), $ver = false, $media = 'all') {
    // Try to load the asset from the CDN
    $result = @file_get_contents($cdn_url);
  
    // Set the URL to the local file if the CDN load fails, or to the CDN URL otherwise
    $url = ($result === false) ? $local_file : $cdn_url;
  
    // Enqueue the asset
    switch ($type) {
      case 'css':
        wp_enqueue_style($handle, $url, $deps, $ver, $media);
        break;
      case 'js':
        wp_enqueue_script($handle, $url, $deps, $ver, true);
        break;
    }
  }
  
  function rg_load_styles() {
    rg_asset_loader(
      'css',
      'dk-review-gate-css',
      'https://cdn.jsdelivr.net/gh/davidkim10/wp-review-gate-plugin@main/css/review-gate.min.css',
      plugins_url('/css/review-gate.min.css', __FILE__),
      array(),
      '1.0',
      'all'
    );
  }
  
  function rg_load_js() {
    // Check if jQuery is already loaded
    if ( ! wp_script_is( 'jquery', 'enqueued' ) ) {
      wp_enqueue_script( 'jquery' );
    }
  
    rg_asset_loader(
      'js',
      'emoji-star-rating-js',
      'https://cdn.jsdelivr.net/gh/davidkim10/wp-review-gate-plugin@main/js/jquery.emojiRatings.min.js',
      plugins_url('/js/jquery.emojiRatings.min.js', __FILE__),
      array('jquery'),
      '1.0',
      true
    );
  
    rg_asset_loader(
      'js',
      'dk-review-gate-js',
      'https://cdn.jsdelivr.net/gh/davidkim10/wp-review-gate-plugin@main/js/review-gate.jquery.min.js',
      plugins_url('/js/review-gate.jquery.min.js', __FILE__),
      array('jquery'),
      '1.0',
      true
    );
  }
   
  // The PHP_INT_MAX constant tells WordPress to load this plugin last
  add_action( 'wp_enqueue_scripts', 'rg_load_styles');
  add_action( 'wp_enqueue_scripts', 'rg_load_js', PHP_INT_MAX );

