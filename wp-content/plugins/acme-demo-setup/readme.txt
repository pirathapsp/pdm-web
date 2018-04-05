=== Acme Demo Setup ===

Contributors: acmethemes, codersantosh
Tags: demo, dummydata, import, acmethemes, themes, oneclick, customizer, widget
Requires at least: 4.5
Tested up to: 4.9.1
Stable tag: 1.0.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Setup you site with dummy data easily. Import settings, widgets and content with one click.  Your dummy data must have ZIP file of xml, dat and wie file. Put all this three files on one ZIP folder and import the ZIP file and
your site will be ready within few seconds.

== Description ==

Setup you site with dummy data easily. Import settings, widgets and content with one click.  Your dummy data must have ZIP file of xml, dat and wie file. Put all this three files on one ZIP folder and import the ZIP file and your site will be ready within few seconds.

== Installation ==

= From your WordPress dashboard =

1. Visit 'Plugins > Add New'
2. Search for 'acme-demo-setup'
3. Activate Acme Demo Setup from Appearance > Plugins
4. Go to the Appearance -> Acme Demo Setup and upload the ZIP file of dummy data.

= From WordPress.org =

1. Download acme-demo-setup.zip
2. Upload the 'acme-demo-setup' directory to your '/wp-content/plugins/' directory, using your favorite method (ftp, sftp, scp, etc...)
3. Activate acme-demo-setup from your Appearance > Plugins page.
4. Go to the Appearance -> Acme Demo Setup and upload the ZIP file of dummy data.

== Frequently Asked Questions ==

= I have activated the plugin. Where to find the plugin page to import data? =

You will find the import page in *wp-admin -> Appearance -> Acme Demo Setup*.

= How to automatically assign Menu Locations after the importer is done? =

You can do that, with the `acme_demo_setup_nav_data` action hook. The code would look something like this:

`if( !function_exists( 'prefix_demo_nav_data') ){
    function prefix_demo_nav_data(){
        $demo_navs = array(
            'one-page' => 'Front Page',
            'primary'  => 'Inner Page'
        );
        return $demo_navs;
    }
}
add_filter('acme_demo_setup_nav_data','prefix_demo_nav_data');`

= How to automatically assign "Front page" & "Posts page" after the importer is done?  =
You can do that, with the `acme_demo_setup_wp_options_data` action hook. The code would look something like this:


`if( !function_exists( 'prefix_demo_wp_options_data') ){
    function prefix_demo_wp_options_data(){
        $wp_options = array(
            'blogname'       => 'Theme Name',
            'page_on_front'  => 'Home Page',
            'page_for_posts' => 'Blog',
        );
        return $wp_options;
    }
}
add_filter('acme_demo_setup_wp_options_data','prefix_demo_wp_options_data');`

= What files we need to put on the demo zipped file ? =

You need a zip file of three files,
a. XML file for Data
B. DAT file for Customizer 
C. WIE file for Widgets
and make the zip file of this theme files. 

= Are you a theme author and want to integrate one click demo import on your own themes? =
One click demo import is very helpful for your theme users, recommend this Acme Demo Setup plugin for better user experience for your theme users.

= You are a theme user and facing the problem with the demo import? =
Contact to your theme author and provide the link of this plugin and request the author to integrate this plugin for your theme. It will be very easy to make the entire site.


= Got the issues on the plugin? =

If you have the issues on the plugin [Visit Support Page](https://wordpress.org/support/plugin/acme-demo-setup), so that we can solve the issues. 


== Screenshots ==

1. Acme Demo Setup

== Changelog ==

= 1.0.7
* Fixed : Alternative File extension checker

= 1.0.6
* Fixed : Memory Limit on parser

= 1.0.5
* Fixed : Text Domain
* Added : Error

= 1.0.4
* Fixed : undefined function ZipArchive on some hosting

= 1.0.3
* Update : Read Me

= 1.0.2
* Fixed issue with Menu import in PHP 7

= 1.0.1
* Implement ajax to import

= 1.0.0
* Initial Release