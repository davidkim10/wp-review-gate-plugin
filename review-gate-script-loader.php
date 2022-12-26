<?php

function review_gate_load_styles() {
    wp_enqueue_style( 'dk-review-gate-css', plugins_url( '/css/review-gate.css', __FILE__ ), array(), '1.0', 'all' );
}

function review_gate_load_js() {
    // Check if jQuery is already loaded
    if ( ! wp_script_is( 'jquery', 'enqueued' ) ) {
        wp_enqueue_script( 'jquery' );
    }
    wp_enqueue_script( 'emoji-star-rating-js', plugins_url( '/js/jquery.emojiRatings.min.js', __FILE__ ), array( 'jquery' ), '1.0', true );
    wp_enqueue_script( 'dk-review-gate-js', plugins_url( '/js/review-gate.jquery.min.js', __FILE__ ), array( 'jquery' ), '1.0', true );
}
 
// The PHP_INT_MAX constant tells WordPress to load this plugin last
add_action( 'wp_enqueue_scripts', 'review_gate_load_styles');
add_action( 'wp_enqueue_scripts', 'review_gate_load_js', PHP_INT_MAX );
