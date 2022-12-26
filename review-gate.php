<?php
/*
Plugin Name: DK Review Gate
Description: Business review gate. Help capture more 5 star leads!
Version: 1.0
Author: David K
Author URI: https://davekim.io
Text Domain: wp-review-gate-plugin

License: MIT
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Requires PHP: 7.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require "review-gate-script-loader.php";
require "review-gate-admin-settings.php";

function review_gate_shortcode( $atts ) {
  // Extract shortcode attributes from WP plugin settings
  extract( shortcode_atts( array(
    'link' => get_option( 'review_gate_link' ),
    'platform' => get_option( 'review_gate_platform' ),
    'logo' => get_option( 'review_gate_logo' ),
    'company' => get_option( 'review_gate_company' ),
    'shortcode' => get_option( 'review_gate_shortcode' ),
  ), $atts ) );

    // Sanitize user input
  $link = esc_url( $link );

  // Render shortcode
  $form_shortcode = do_shortcode( $shortcode );

  $template = <<<HTML
    <div class="dk-review-gate-wrapper">
      <nav class="review-gate-navbar" style="display: none">
        <button class="review-gate-navbar__btn">&#10094;</button>
        <div class="review-gate-navbar__logo-wrapper">
          <img class="review-gate-navbar__logo" src="$logo" alt="logo" />
        </div>
      </nav>
      <div id="review-gate">
        <section class="review-step">
          <h2 class="review-title">Rate Your Recent Experience</h2>
        </section>
        <section class="review-step">
          <h2 class="review-title">Please Leave Us A Review!</h2>
          <p>
            We are very happy to hear you had a positive experience with $company. Please take a second to leave us
            a review on $platform.
          </p>
          <a class="btn btn-color-primary btn-style-default btn-size-default leave-review-btn" href="$link">Leave
            Review</a>
        </section>
        <section class="review-step">
          <h2 class="review-title">Please Leave Us Some Feedback</h2>
          <p>
            We are sorry to hear you did not have a 5-Star experience. Please
            take a moment to leave us feedback on how we can improve.
            <br />
            <br />
            $form_shortcode
          </p>
        </section>
      </div>
    </div>
  HTML;

  return $template;
}


add_shortcode( 'dk_review_gate', 'review_gate_shortcode' );
