<?php


function dynamic_slider_scripts()
{
    wp_enqueue_style('dynamic-slider-style', plugins_url() . '/css/dynamic-slider.css');

    // the main.js files handles the ajax request for submitting feedback forms
    wp_enqueue_script('dynamic-slider-scripts', plugins_url() . '/js/dynamic-slider.js', array('jquery'));
}
add_action('wp_enqueue_scripts', 'dynamic_slider_scripts');
