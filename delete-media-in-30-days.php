<?php
/*
Plugin Name: Delete Media in 30 Days
Plugin URI: https://github.com/connectpritam/schedule-delete-wp-media/
Description: Adds an option to delete uploaded media after 30 days.
Version: 1.0.3
Author: Pritam Mullick
Author URI: https://www.linkedin.com/in/connectpritam/
*/

// Hook into the WordPress media upload process to add a custom checkbox
add_filter('attachment_fields_to_edit', 'dmid_add_delete_checkbox', 10, 2);
function dmid_add_delete_checkbox($form_fields, $post) {
    // Define the custom checkbox HTML
// Define the custom checkbox HTML, enclosed in a div with a specific size
    $form_fields['delete_in_30_days'] = array(
        'label' => 'Delete this clean file from server in 30 Days?',
        'input' => 'html',
        'html' => '<div style="width: 10px;"><input type="checkbox" name="attachments[' . $post->ID . '][delete_in_30_days]" id="attachments[' . $post->ID . '][delete_in_30_days]" /></div>',
        'helps' => 'If checked, this file will be deleted after 30 days. Applicable to clean files only.',
    );

    return $form_fields;
}

// Save the checkbox value when the media is uploaded or edited
add_filter('attachment_fields_to_save', 'dmid_save_delete_option', 10, 2);
function dmid_save_delete_option($post, $attachment) {
    if (isset($attachment['delete_in_30_days'])) {
        // Set post meta to mark the file for deletion
        update_post_meta($post['ID'], 'delete_in_30_days', 'yes');
        // Schedule the deletion event 10 minutes from now - TESTING MODE
        wp_schedule_single_event(time() + (10 * MINUTE_IN_SECONDS), 'dmid_scheduled_deletion', array($post['ID']));
        // Schedule the deletion event 30 days from now - PRODUCTION MODE
        // wp_schedule_single_event(time() + (30 * DAY_IN_SECONDS), 'dmid_scheduled_deletion', array($post['ID']));
    } else {
        // If the checkbox was not checked, ensure no deletion is scheduled
        delete_post_meta($post['ID'], 'delete_in_30_days');
    }

    return $post;
}

// Hook into WP Cron to handle the scheduled deletion
add_action('dmid_scheduled_deletion', 'dmid_handle_scheduled_deletion');
function dmid_handle_scheduled_deletion($post_id) {
    // Check if the post meta indicates the file should be deleted
    $should_delete = get_post_meta($post_id, 'delete_in_30_days', true);

    if ($should_delete == 'yes') {
        // Delete the attachment/file
        wp_delete_attachment($post_id, true);
    }
}

// Optional: Flush the scheduled events on plugin deactivation
register_deactivation_hook(__FILE__, 'dmid_deactivate');
function dmid_deactivate() {
    // Get all attachments
    $args = array(
        'post_type' => 'attachment',
        'posts_per_page' => -1,
        'fields' => 'ids',
    );
    $attachments = get_posts($args);

    // Loop through attachments and clear scheduled events
    foreach ($attachments as $attachment_id) {
        wp_clear_scheduled_hook('dmid_scheduled_deletion', array($attachment_id));
    }
}
