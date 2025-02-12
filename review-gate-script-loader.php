<?php

/**
 * rg_asset_loader()
 * Loads an asset from a CDN with a backup to a local file.
 *
 * @param string $type       The type of the asset. Can be 'css' or 'js'.
 * @param string $handle     A unique handle or name for the asset.
 * @param string $cdn_url    The CDN URL for the asset.
 * @param string $local_file The local file path for the asset.
 * @param array  $deps       Optional. An array of dependencies.
 * @param mixed  $ver        Optional. String specifying the asset version.
 * @param string $media      Optional. The media for which this stylesheet has been defined.
 */
function rg_asset_loader($type, $handle, $cdn_url, $local_file, $deps = array(), $ver = false, $media = 'all') {
  // Try to load the asset from the CDN
  $result = @file_get_contents($cdn_url);

  // Use the local file URL if the CDN load fails, or the CDN URL otherwise
  $url = ($result === false) ? $local_file : $cdn_url;

  // Enqueue the asset based on type
  switch ($type) {
    case 'css':
      wp_enqueue_style($handle, $url, $deps, $ver, $media);
      break;
    case 'js':
      wp_enqueue_script($handle, $url, $deps, $ver, true);
      break;
  }
}

/**
 * rg_should_load_assets()
 *
 * Determines if the assets should be loaded.
 *
 * You can modify this function based on your needs.
 * Below are two options:
 *
 * Option 1: Only load on pages using a specific page template.
 * Option 2: Only load on pages where the [dk_review_gate] shortcode is present.
 *
 * In this example, if either condition is true, the assets are loaded.
 *
 * @return bool True if assets should be loaded; otherwise, false.
 */
function rg_should_load_assets() {
  // Option 1: Check for a specific page template.
  // if (is_page_template('template-html.php')) {
  //   return true;
  // }

  // Option 2: Check if the current singular post/page contains the shortcode.
  if (is_singular() && isset($GLOBALS['post']) && has_shortcode($GLOBALS['post']->post_content, 'dk_review_gate')) {
    return true;
  }

  return false;
}

function rg_load_styles() {
  // Only enqueue styles if the conditions are met.
  if (! rg_should_load_assets()) {
    return;
  }

  rg_asset_loader(
    'css',
    'dk-review-gate-css',
    'https://cdn.jsdelivr.net/npm/jquery-review-gate@1.0.3/dist/css/review-gate.min.css',
    plugins_url('/css/review-gate.min.css', __FILE__),
    array(),
    '1.0.3',
    'all'
  );
}

function rg_load_js() {
  // Only enqueue scripts if the conditions are met.
  if (! rg_should_load_assets()) {
    return;
  }

  // Ensure jQuery is enqueued.
  if (! wp_script_is('jquery', 'enqueued')) {
    wp_enqueue_script('jquery');
  }

  rg_asset_loader(
    'js',
    'dk-review-gate-js',
    'https://cdn.jsdelivr.net/npm/jquery-review-gate@1.0.3/dist/js/review-gate.jquery.min.js',
    plugins_url('/js/review-gate.jquery.min.js', __FILE__),
    array('jquery'),
    '1.0.3',
    true
  );
}

// Using PHP_INT_MAX for JS to ensure it loads last.
add_action('wp_enqueue_scripts', 'rg_load_styles');
add_action('wp_enqueue_scripts', 'rg_load_js', PHP_INT_MAX);
