<?php
function review_gate_register_settings_page() {
  add_options_page(
    'DK Review Gate', // Page title
    'DK Review Gate', // Menu title
    'manage_options', // Capability
    'dk-review-gate', // Menu slug
    'review_gate_settings_page_init'
  );

  $settings = array(
    'review_gate_link',
    'review_gate_platform',
    'review_gate_logo',
    'review_gate_company',
    'review_gate_shortcode'
  );

  // Register settings
  foreach ($settings as $setting) {
    register_setting('review-gate-settings-group', $setting);
  }
}
add_action('admin_menu', 'review_gate_register_settings_page');

function review_gate_settings_page_init() {
?>
  <div class="wrap">
    <h1>Review Gate Settings</h1>
    <p>By: <a href="https://davekim.io" target="_blank">David K</a></p>

    <form method="post" action="options.php">
      <?php
      settings_fields('review-gate-settings-group');
      do_settings_sections('review-gate-settings-group');
      ?>
      <style>
        .rg-table input[type="text"] {
          width: 100%;
        }
      </style>
      <table class="form-table rg-table">
        <tr valign="top">
          <th scope="row">Review Platform Name</th>
          <td>
            <input
              type="text"
              placeholder="Yelp, Google, etc"
              name="review_gate_platform"
              value="<?php echo esc_html(get_option('review_gate_platform')); ?>" />
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">Review Platform URL</th>
          <td>
            <input
              type="text"
              name="review_gate_link"
              placeholder="https://yelp.com/yourBusinessId"
              value="<?php echo esc_url(get_option('review_gate_link')); ?>" />
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">Logo URL</th>
          <td>
            <input
              type="text"
              name="review_gate_logo"
              value="<?php echo esc_url(get_option('review_gate_logo')); ?>" />
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">Form Shortcode (to capture reviews that are less than 5 star)</th>
          <td>
            <input
              type="text"
              name="review_gate_shortcode"
              placeholder='[contact-form-7 id="2707" title="Feedback Form"]'
              value="<?php echo esc_html(get_option('review_gate_shortcode')); ?>" />
          </td>
        </tr>
      </table>

      <div style="margin-bottom: 20px;">
        <h2>Use the following shortcode to generate a review gate:</h2>
        <code>
          [dk_review_gate]
        </code>
      </div>

      <?php submit_button(); ?>
    </form>
  </div>
<?php
}
