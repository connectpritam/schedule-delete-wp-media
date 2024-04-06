Delete Media in 30 Days
=======================

Delete Media in 30 Days is a WordPress plugin that allows users to select media files for automatic deletion 30 days after upload. For testing purposes, this version is set to delete files after 10 minutes.

Features
--------

-   Automatic Deletion: Enables files to be marked for deletion directly from the media upload screen.
-   Simple to Use: Just check a box to schedule the deletion of the media file.
-   Testing Mode: Currently, the plugin is in testing mode, with files set to be deleted 10 minutes after upload.
Screenshot:
<img width="370" alt="image" src="https://github.com/connectpritam/schedule-delete-wp-media/assets/23458910/cf305e99-7b2f-4722-8d12-b2f9a3ccaca4">


Installation
------------

1.  Download the plugin from the provided link.
2.  Unzip and upload the `delete-media-in-30-days` folder to your `/wp-content/plugins/` directory.
3.  Log into your WordPress dashboard.
4.  Navigate to the Plugins menu and activate the Delete Media in 30 Days plugin.

Usage
-----

1.  When uploading new media through the WordPress media uploader, you will see a new checkbox labeled Delete in 30 Days?.
2.  Check this box if you want the uploaded media file to be automatically deleted after 10 minutes (for testing purposes; will be 30 days in the final version).
3.  The file will be deleted automatically based on your selection.

Testing
-------

This version of the plugin is set for testing with a 10-minute deletion period. To test:

-   Follow the regular upload process and select the deletion option.
-   Wait for 10 minutes and check if the file has been successfully deleted.

Customization
-------------

To change the deletion period back to 30 days for production use, modify the `wp_schedule_single_event` line in the `dmid_save_delete_option` function, replacing `(10 * MINUTE_IN_SECONDS)` with `(30 * DAY_IN_SECONDS)`.

License
-------

This plugin is licensed under the GPL v2 or later.

Support
-------

For support, please contact Pritam Mullick (https://www.linkedin.com/in/connectpritam/) .
