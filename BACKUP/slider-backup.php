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
?>
    <div class="wrap">
        <h2>Dynamic Slider</h2>

        <?php if (isset($_GET['updated']) && $_GET['updated'] == 'true') { ?>
            <div class="updated">
                <p>Slider updated successfully!</p>
            </div>
        <?php } ?>

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

        <div class="add-new-slide">
            <h2>Add New Slide</h2>
            <form action="?page=dynamic_slider&action=add" method="post">
                <input type="text" name="header" placeholder="Header">
                <input type="text" name="paragraph" placeholder="Paragraph">
                <input type="text" name="button1_label" placeholder="Button 1 Label">
                <input type="text" name="button1_url" placeholder="Button 1 URL">
                <input type="text" name="button2_label" placeholder="Button 2 Label">
                <input type="text" name="button2_url" placeholder="Button 2 URL">
                <input type="submit" value="Add Slide">
            </form>
        </div>
    </div>
<?php
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

    $results = $wpdb->get_results($sql);

    $slides = array();

    foreach ($results as $result) {
        $slides[] = $result;
    }

    return $slides;
}

/**
 * Add a slide.
 *
 * @param array $data
 */
function add_dynamic_slider_slide($data)
{
    global $wpdb;

    $sql = "INSERT INTO dynamic_slider (
    header,
    paragraph,
    button1_label,
    button1_url,
    button2_label,
    button2_url
  ) VALUES (
    '{$data['header']}',
    '{$data['paragraph']}',
    '{$data['button1_label']}',
    '{$data['button1_url']}',
    '{$data['button2_label']}',
    '{$data['button2_url']}'
  );";

    $wpdb->query($sql);
}

/**
 * Edit a slide.
 *
 * @param int $id
 * @param array $data
 */
function edit_dynamic_slider_slide($id, $data)
{
    global $wpdb;

    $sql = "UPDATE dynamic_slider SET
    header = '{$data['header']}',
    paragraph = '{$data['paragraph']}',
    button1_label = '{$data['button1_label']}',
    button1_url = '{$data['button1_url']}',
    button2_label = '{$data['button2_label']}',
    button2_url = '{$data['button2_url']}'
    WHERE id = {$id};";

    $wpdb->query($sql);
}

/**
 * Delete a slide.
 *
 * @param int $id
 */
function delete_dynamic_slider_slide($id)
{
    global $wpdb;

    $sql = "DELETE FROM dynamic_slider WHERE id = {$id};";

    $wpdb->query($sql);
}
