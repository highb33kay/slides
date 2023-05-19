<?php

/**
 * Plugin Name: Dynamic Slider
 * Description: This plugin creates a dynamic slider that can be updated from the admin dashboard.
 * Author: HighB33Kay
 * Author URI: github.com/HighB33Kay
 */

// Define the plugin constants.
define('DYNAMIC_SLIDER_PLUGIN_FILE', __FILE__);
define('DYNAMIC_SLIDER_PLUGIN_URL', plugins_url('/', DYNAMIC_SLIDER_PLUGIN_FILE));

// Register the activation hook.
register_activation_hook(DYNAMIC_SLIDER_PLUGIN_FILE, 'dynamic_slider_plugin_activate');

// Register the deactivation hook.
register_deactivation_hook(DYNAMIC_SLIDER_PLUGIN_FILE, 'dynamic_slider_plugin_deactivate');

// Register the uninstall hook.
register_uninstall_hook(DYNAMIC_SLIDER_PLUGIN_FILE, 'dynamic_slider_plugin_uninstall');

// Load the plugin files.
require_once DYNAMIC_SLIDER_PLUGIN_URL . '/includes/functions.php';
require_once DYNAMIC_SLIDER_PLUGIN_URL . '/includes/admin.php';

/**
 * Activation hook callback.
 *
 * @return void
 */
function dynamic_slider_plugin_activate()
{
    // Create the database table.
    $sql = "CREATE TABLE IF NOT EXISTS dynamic_slider (
    id INT NOT NULL AUTO_INCREMENT,
    header VARCHAR(255) NOT NULL,
    paragraph VARCHAR(255) NOT NULL,
    button1_label VARCHAR(255) NOT NULL,
    button1_url VARCHAR(255) NOT NULL,
    button2_label VARCHAR(255) NOT NULL,
    button2_url VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
  );";

    dbDelta($sql);
}

/**
 * Deactivation hook callback.
 *
 * @return void
 */
function dynamic_slider_plugin_deactivate()
{
    // Drop the database table.
    $sql = "DROP TABLE IF EXISTS dynamic_slider;";

    dbDelta($sql);
}

/**
 * Uninstall hook callback.
 *
 * @return void
 */
function dynamic_slider_plugin_uninstall()
{
    // Remove the database table.
    $sql = "DROP TABLE IF EXISTS dynamic_slider;";

    dbDelta($sql);
}

// Define the admin menu.
add_action('admin_menu', 'dynamic_slider_plugin_add_admin_menu');

/**
 * Add the admin menu.
 *
 * @return void
 */
function dynamic_slider_plugin_add_admin_menu()
{
    add_menu_page(
        'Dynamic Slider',
        'Dynamic Slider',
        'manage_options',
        'dynamic_slider',
        'dynamic_slider_plugin_admin_page'
    );
}

/**
 * Render the admin page.
 *
 * @return void
 */
function dynamic_slider_plugin_admin_page()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Process form submission
        if (
            isset($_POST['header']) && isset($_POST['paragraph']) && isset($_POST['button1_label']) &&
            isset($_POST['button1_url']) && isset($_POST['button2_label']) && isset($_POST['button2_url'])
        ) {

            $header = sanitize_text_field($_POST['header']);
            $paragraph = sanitize_text_field($_POST['paragraph']);
            $button1_label = sanitize_text_field($_POST['button1_label']);
            $button1_url = esc_url($_POST['button1_url']);
            $button2_label = sanitize_text_field($_POST['button2_label']);
            $button2_url = esc_url($_POST['button2_url']);

            if (
                !empty($header) && !empty($paragraph) && !empty($button1_label) && !empty($button1_url)
                && !empty($button2_label) && !empty($button2_url)
            ) {

                add_dynamic_slider_slide($header, $paragraph, $button1_label, $button1_url, $button2_label, $button2_url);

                wp_redirect(admin_url('admin.php?page=dynamic_slider&updated=true'));
                exit();
            } else {
                echo '<div class="error"><p>All fields are required!</p></div>';
            }
        }
    }

?>
    <div class="wrap">
        <h2>Dynamic Slider</h2>

        <?php if (isset($_GET['updated']) && $_GET['updated'] == 'true') { ?>
            <div class="updated">
                <p>Slider updated successfully!</p>
            </div>
        <?php } ?>

        <form method="post">
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="header">Header:</label></th>
                    <td><input type="text" id="header" name="header" required></td>
                </tr>
                <tr>
                    <th scope="row"><label for="paragraph">Paragraph:</label></th>
                    <td><input type="text" id="paragraph" name="paragraph" required></td>
                </tr>
                <tr>
                    <th scope="row"><label for="button1_label">Button 1 Label:</label></th>
                    <td><input type="text" id="button1_label" name="button1_label" required></td>
                </tr>
                <tr>
                    <th scope="row"><label for="button1_url">Button 1 URL:</label></th>
                    <td><input type="text" id="button1_url" name="button1_url" required></td>
                </tr>
                <tr>
                    <th scope="row"><label for="button2_label">Button 2 Label:</label></th>
                    <td><input type="text" id="button2_label" name="button2_label" required></td>
                </tr>
                <tr>
                    <th scope="row"><label for="button2_url">Button 2 URL:</label></th>
                    <td><input type="text" id="button2_url" name="button2_url" required></td>
                </tr>
            </table>
            <p class="submit"><input type="submit" class="button-primary" value="Add Slide"></p>
        </form>

        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Header</th>
                    <th>Paragraph</th>
                    <th>Button 1 Label</th>
                    <th>Button 1 URL</th>
                    <th>Button 2 Label</th>
                    <th>Button 2 URL</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $slides = get_dynamic_slider_slides();
                foreach ($slides as $slide) {
                ?>
                    <tr>
                        <td><?php echo $slide['header']; ?></td>
                        <td><?php echo $slide['paragraph']; ?></td>
                        <td><?php echo $slide['button1_label']; ?></td>
                        <td><?php echo $slide['button1_url']; ?></td>
                        <td><?php echo $slide['button2_label']; ?></td>
                        <td><?php echo $slide['button2_url']; ?></td>
                        <td>
                            <a href="?page=dynamic_slider&action=edit&id=<?php echo $slide['id']; ?>">Edit</a>
                            <a href="?page=dynamic_slider&action=delete&id=<?php echo $slide['id']; ?>">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
<?php
}

/**
 * Add a slide to the dynamic slider.
 *
 * @param string $header
 * @param string $paragraph
 * @param string $button1_label
 * @param string $button1_url
 * @param string $button2_label
 * @param string $button2_url
 * @return void
 */
function add_dynamic_slider_slide($header, $paragraph, $button1_label, $button1_url, $button2_label, $button2_url)
{
    global $wpdb;

    $wpdb->insert(
        'dynamic_slider',
        array(
            'header' => $header,
            'paragraph' => $paragraph,
            'button1_label' => $button1_label,
            'button1_url' => $button1_url,
            'button2_label' => $button2_label,
            'button2_url' => $button2_url,
        ),
        array(
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
        )
    );
}

/**
 * Get all the slides.
 *
 * @return array
 */
function get_dynamic_slider_slides()
{
    global $wpdb;

    $sql = "SELECT * FROM dynamic_slider;";

    $results = $wpdb->get_results($sql, ARRAY_A);

    return $results;
}

/**
 * Enqueue the styles.
 */
function dynamic_slider_enqueue_styles()
{
    wp_enqueue_style('dynamic-slider-style', plugins_url('/css/dynamic-slider.css', __FILE__));
}

/**
 * Enqueue the scripts.
 */
function dynamic_slider_enqueue_scripts()
{
    wp_enqueue_script('dynamic-slider-script', plugins_url('/js/dynamic-slider.js', __FILE__));
}

add_action('wp_enqueue_scripts', 'dynamic_slider_enqueue_styles');
add_action('wp_enqueue_scripts', 'dynamic_slider_enqueue_scripts');

/**
 * Display the slider.
 */
function dynamic_slider_display()
{
?>
    <div class="slider">
        <ul class="slides">
            <?php
            $slides = get_dynamic_slider_slides();

            foreach ($slides as $index => $slide) {
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

/**
 * Unit Test: Verify the functionality of the plugin.
 */
function dynamic_slider_unit_test()
{
    // Test adding a slide
    add_dynamic_slider_slide('Test Header', 'Test Paragraph', 'Button 1', 'https://example.com', 'Button 2', 'https://example.com');

    // Get all slides
    $slides = get_dynamic_slider_slides();

    // Check if the slide was added
    if (count($slides) > 0) {
        echo 'Slide added successfully.' . PHP_EOL;
    } else {
        echo 'Failed to add slide.' . PHP_EOL;
    }

    // Test shortcode
    ob_start();
    dynamic_slider_display();
    $output = ob_get_clean();

    // Check if the shortcode output contains the slide content
    if (strpos($output, 'Test Header') !== false && strpos($output, 'Test Paragraph') !== false) {
        echo 'Shortcode output is correct.' . PHP_EOL;
    } else {
        echo 'Shortcode output is incorrect.' . PHP_EOL;
    }
}

// Run the unit test
dynamic_slider_unit_test();
