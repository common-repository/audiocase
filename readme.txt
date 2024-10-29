=== audioCase ===
Contributors: pconceptions
Donate link: https://kielenki.ng/tips
Tags: audio, music, music player, song player, single song, music showcase
Requires at least: 5.0
Tested up to: 6.3.1
Stable tag: 1.2.1
Requires PHP: 7.0
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

audioCase generates an individual song player with artwork and waveform visualizer.

== Description ==

audioCase allows you to showcase a single song display with artwork and waveform visualizer.

You can upload your own artwork file (1:1 ratio) or you can leave that field blank and a default image will be loaded for you. You can also choose to allow the viewer to download or purchase the track via a URL you provide. The plugin allows for a multiple instances/players per post or page. 


== Frequently Asked Questions ==

  = What file formats can I use for the music? =
    *Currently, the plugin is limited to MP3 files under 15MB in size. This ultimately keeps the file size and load times to a minimum. Additional file formats may be supported in the future.*

  = Can I set up multiple players on a page? =
    *Yes! However, consideration should be given to the quantity of players and the file size of your MP3s. Page load time could be significantly impacted.*

  = Can I change the button and/or waveform colors? =
    *The color scheme is currently limited, but an update is planned that will allow for customization in a future (possibly pro) version.*


== Screenshots ==

1. audioCase main display (multiple on a single page)
2. New audioCase entry screen
3. List of existing audioCases with shortcode

== Changelog ==

= 1.0 =
* Launch version

= 1.0.1 =
* Added js.map file for wavesurfer.js to address error in console
* Updated screenshot-1.png & screenshot-2.png
* Updated Advanced Custom Fields library to 6.0.6

= 1.0.2 =
* Updated to fix issue where apostrophes, etc. in song title names were being converted to their HTML counterparts

= 1.1.0 =
* Updated Wavesurfer.js library to 6.6.3
* Updated Advanced Custom Fields library to 6.1.6

= 1.1.1 =
* Rolled back Advanced Custom Fields library to 6.0.6

= 1.2.0 =
* Updated Advanced Custom Fields library to 6.2.0

= 1.2.1 =
* WP version check (6.3.1)
* ACF library update


== Upgrade Notice ==

= 1.0 =
Original launch version of audioCase.

= 1.0.1 =
This version addresses a console error and updates a library dependency. Update is recommended.

= 1.0.2 =
This version fixes a display issue with apostrophes, etc. in song titles. Update is recommended. 

= 1.1.0 =
This version updates the library files for Wavesurfer.js and Advanced Custom Fields. Functionality in WordPress 6.2 is tested and verified. Update is recommended. 

= 1.1.1 =
This version rolls Advanced Custom Fields library back to version 6.0.6. to address a found critical error. Additional update pending resolution. Update is highly recommended to restore functionality. 

= 1.2.0 =
This version updates the library files for Advanced Custom Fields. Functionality in WordPress 6.3 is tested and verified. Update is recommended. 

= 1.2.1 =
This version verifies functionality in WordPress 6.3.1 and updates the ACF library. Update is recommended. 



== Getting Started ==

Once the plugin is installed and activated...

1. Click (or hover over) audioCase in the left-hand admin menu and select "Add New."
2. Add your song title in the "Add title" field (or a blank space for no title (ALT+0160 on PC)).
3. Under "Song File" click "Add File" and select from your library or upload the MP3 file you wish to use.
4. Under "Cover Art" click "Add Image" and select from your library or upload the image file you wish to use. *(jpg, png, webp, or gif — Image must be square ratio (1:1). Minimum dimensions: 720x720px, Maximum: 1500x1500px.)*
5. Under "Viewer Action" select "Download," "Buy," or "No Action" — If "Download" or "Buy" are selected enter the URL for your download/buy location.
6. Click "Publish."
7. You will be returned to the main page of audioCases. Copy the "Shortcode" in the far right column of the entry/title you just created.
8. Navigate to the body of your page or post and use the editor to paste the shortcode into your content.
9. Preview your page/post to view your new audioCase and publish your page/post when ready!
10. Enjoy!

-----
