<?php

/**
 * Enqueue the styles.
 */
// function dynamic_slider_enqueue_styles()
// {
//     wp_enqueue_style('dynamic-slider-style', plugins_url('/css/dynamic-slider.css', __FILE__));
// }


/**
 * Enqueue the scripts.
 */
// function dynamic_slider_enqueue_scripts()
// {
//     wp_enqueue_script('dynamic-slider-script', plugins_url('/js/dynamic-slider.js', __FILE__));
// }

// add_action('wp_enqueue_scripts', 'dynamic_slider_enqueue_styles');
// add_action('wp_enqueue_scripts', 'dynamic_slider_enqueue_scripts');

function dynamic_slider_display()
{
?>
    <div class="slider">
        <ul class="slides">
            <?php
            $slides = get_dynamic_slider_slides();

            foreach ($slides as $slide) {
                echo '<li class="slide">';
                echo '<img src="' . esc_url($slide['image']) . '">';
                echo '<div class="overlay"></div>';
                echo '<div class="content">';
                echo '<h2>' . esc_html($slide['header']) . '</h2>';
                echo '<p>' . esc_html($slide['paragraph']) . '</p>';
                echo '<div class="buttons">';
                echo '<a href="' . esc_url($slide['button1_url']) . '">' . esc_html($slide['button1_label']) . '</a>';
                echo '<a href="' . esc_url($slide['button2_url']) . '">' . esc_html($slide['button2_label']) . '</a>';
                echo '</div>';
                echo '</div>';
                echo '</li>';
            }
            ?>
        </ul>
    </div>

<?php
}

add_shortcode('dynamic_slider', 'dynamic_slider_display');

?>