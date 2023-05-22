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
require_once ABSPATH . 'wp-admin/includes/upgrade.php';
require_once plugin_dir_path(__FILE__) . '/includes/admin.php';

// require the functions file
require_once plugin_dir_path(__FILE__) . '/includes/functions.php';

// require the script file
require_once plugin_dir_path(__FILE__) . '/includes/scripts.php';

/**
 * Activation hook callback.
 */
function dynamic_slider_plugin_activate()
{
    // Create the database table.
    global $wpdb;
    $table_name = $wpdb->prefix . 'dynamic_slider'; // Use the WordPress table prefix

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id INT NOT NULL AUTO_INCREMENT,
        image VARCHAR(255) NOT NULL,
        header VARCHAR(255) NOT NULL,
        paragraph VARCHAR(255) NOT NULL,
        button1_label VARCHAR(255) NOT NULL,
        button1_url VARCHAR(255) NOT NULL,
        button2_label VARCHAR(255) NOT NULL,
        button2_url VARCHAR(255) NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}

/**
 * Deactivation hook callback.
 */
function dynamic_slider_plugin_deactivate()
{
    // No action required for deactivation.
}

/**
 * Uninstall hook callback.
 */
function dynamic_slider_plugin_uninstall()
{
    // Remove the database table.
    global $wpdb;
    $table_name = $wpdb->prefix . 'dynamic_slider';

    $sql = "DROP TABLE IF EXISTS $table_name;";
    $wpdb->query($sql);
}

// Define the admin menu.
add_action('admin_menu', 'dynamic_slider_plugin_add_admin_menu');

/**
 * Add the admin menu.
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
 */
function dynamic_slider_plugin_admin_page()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Process form submission
        if (
            isset($_POST['header']) && isset($_POST['paragraph']) &&
            isset($_POST['button1_label']) && isset($_POST['button1_url']) &&
            isset($_POST['button2_label']) && isset($_POST['button2_url'])
        ) {
            $header = sanitize_text_field($_POST['header']);
            $paragraph = sanitize_text_field($_POST['paragraph']);
            $button1_label = sanitize_text_field($_POST['button1_label']);
            $button1_url = esc_url($_POST['button1_url']);
            $button2_label = sanitize_text_field($_POST['button2_label']);
            $button2_url = esc_url($_POST['button2_url']);
            $image_url = '';

            // Image upload
            if (isset($_FILES['image']) && !empty($_FILES['image']['tmp_name'])) {
                $upload_dir = wp_upload_dir();
                $image_name = sanitize_file_name($_FILES['image']['name']);
                $image_path = $upload_dir['path'] . '/' . $image_name;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
                    $image_url = $upload_dir['url'] . '/' . $image_name;
                }
            }

            if (
                !empty($header) && !empty($paragraph) &&
                !empty($button1_label) && !empty($button1_url) &&
                !empty($button2_label) && !empty($button2_url)
            ) {
                // Add image URL to the database
                add_dynamic_slider_slide(
                    $header,
                    $paragraph,
                    $button1_label,
                    $button1_url,
                    $button2_label,
                    $button2_url,
                    $image_url
                );

                wp_redirect(admin_url('admin.php?page=dynamic_slider&updated=true'));
                exit();
            } else {
                echo '<div class="error"><p>All fields are required!</p></div>';
            }
        }

        if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
            $slide_id = intval($_GET['id']);
            delete_dynamic_slider_slide($slide_id);
            wp_redirect(admin_url('admin.php?page=dynamic_slider&deleted=true'));
            exit();
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

        <form method="post" enctype="multipart/form-data">
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
                <tr>
                    <th scope="row"><label for="image">Image:</label></th>
                    <td><input type="file" id="image" name="image" required></td>
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
                    <th>Image</th>
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
                        <td><img src="<?php echo $slide['image_url']; ?>" alt="Slide Image" style="max-width: 100px;"></td>
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



    /**
     * Unit Test: Verify the functionality of the plugin.
     */
    function dynamic_slider_unit_test()
    {
        // Test adding a slide
        add_dynamic_slider_slide('Test Header', 'Test Paragraph', 'Button 1', 'https://example.com', 'Button 2', 'https://example.com', 'http://bodtops-realty.local/wp-content/uploads/2023/05/nupo-1-1.jpg');
        $slides = get_dynamic_slider_slides();
        $last_slide = end($slides);
        if ($last_slide['header'] != 'Test Header' || $last_slide['paragraph'] != 'Test Paragraph' || $last_slide['button1_label'] != 'Button 1' || $last_slide['button1_url'] != 'https://example.com' || $last_slide['button2_label'] != 'Button 2' || $last_slide['button2_url'] != 'https://example.com' || $last_slide['image'] != 'http://bodtops-realty.local/wp-content/uploads/2023/05/nupo-1-1.jpg') {
            echo 'Adding a slide failed.';
        }

        // Test deleting a slide
        $slide_id = $last_slide['id'];
        delete_dynamic_slider_slide($slide_id);
        $slides = get_dynamic_slider_slides();
        $slide_ids = array_column($slides, 'id');
        if (in_array($slide_id, $slide_ids)) {
            echo 'Deleting a slide failed.';
        }

        // Test updating a slide
        add_dynamic_slider_slide('Test Header', 'Test Paragraph', 'Button 1', 'https://example.com', 'Button 2', 'https://example.com', 'http://bodtops-realty.local/wp-content/uploads/2023/05/nupo-1-1.jpg');
        $slides = get_dynamic_slider_slides();
        $last_slide = end($slides);
        $slide_id = $last_slide['id'];
        update_dynamic_slider_slide($slide_id, 'Updated Header', 'Updated Paragraph', 'Updated Button 1', 'https://example.com/updated', 'Updated Button 2', 'https://example.com/updated', 'http://bodtops-realty.local/wp-content/uploads/2023/05/nupo-1-1.jpg');
        $slides = get_dynamic_slider_slides();
        $updated_slide = get_dynamic_slider_slide($slide_id);
        if ($updated_slide['header'] != 'Updated Header' || $updated_slide['paragraph'] != 'Updated Paragraph' || $updated_slide['button1_label'] != 'Updated Button 1' || $updated_slide['button1_url'] != 'https://example.com/updated' || $updated_slide['button2_label'] != 'Updated Button 2' || $updated_slide['button2_url'] != 'https://example.com/updated' || $updated_slide['image'] != 'http://bodtops-realty.local/wp-content/uploads/2023/05/nupo-1-1.jpg') {
            echo 'Updating a slide failed.';
        }

        // Test deleting all slides
        delete_all_dynamic_slider_slides();
        $slides = get_dynamic_slider_slides();
        if (!empty($slides)) {
            echo 'Deleting all slides failed.';
        }

        echo 'Unit test completed.';
    }

    // Uncomment the line below to run the unit test
    // dynamic_slider_unit_test();
}
?>