=== uTestDrive ===
Contributors: boospot, raoabid491
Tags: multisite, test drive, test drive plugin, network, plugin author, theme author, demo setup
Donate link: https://www.buymeacoffee.com/raoabid
Requires at least: 4.7
Tested up to: 5.3
Requires PHP: 5.6
Stable tag: trunk
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

The Plugin to create Demo Test Drive setup for plugin and theme authors

== Description ==
### Attention
- This plugin is to be used on **WordPress Multisite installation**. For details, check [Create A Network](https://wordpress.org/support/article/create-a-network/)
- This plugin is intended to be used by plugin and theme developers who want to create test drive setup for their theme so that potential users can play around with the plugin and/or theme. Its kind of a playground for the potential users.
- The plugin can also be used where you need to create a expiring user accounts for test setups

### How it Works?
1. Install and activate plugin on main site and:
	-  Verify default settings for Test Site Expiry (default 48 hours) and test drive user role
	- Network activate the plugin or themes you want to create test drive for.
2. Add shortcode `[utd_reg_form]` on main site. where user can provide their name and email
3. On form submit, test site is created for the user and credentials sent to the email provided.

### Heads up
1. Test drive user and Test drive site created in step 3 , auto deletes after the expiry of stipulated period.
2. Plugin does not save any data for test drive user or site, if you need to have a list of users who created the test drive site, please install [flamingo](https://wordpress.org/plugins/flamingo/). (works out of box, just activate the plugin and all users registered will be saved)

**Credits**

- Thanks to excellent article by John Turner at [SeedProd](https://www.seedprod.com/setup-demo-site-plugin-theme/)



== Installation ==
**Automatic Plugin Installation**

1. Log in to your WordPress Site dashboard.
1. Go to “Plugins -> Add New”
1. Search for “uTestDrive”.
1. Check the Author is “BooSpot”
1. Click to “Install Now” when you find the plugin.
1. Activate the plugin by clicking “Activate”.

**Manual Plugin Installation**

1. Download your WordPress Plugin to your desktop.
1. If downloaded as a zip archive, extract the Plugin folder to your desktop.
1. Read through the “readme” file thoroughly to ensure you follow the installation instructions.
1. With your FTP program, upload the Plugin folder to the wp-content/plugins folder in your WordPress directory online.
1. Go to Plugins screen and find the newly uploaded Plugin in the list.
1. Click Activate to activate it.

For more details, read: [https://codex.wordpress.org/Managing_Plugins](https://codex.wordpress.org/Managing_Plugins)

== Frequently Asked Questions ==
= Minimum Requirements? =
This plugin is intended for Multisite installation with php 5.6+ and WordPress 4.7+

= Shortcode to display form =
`[utd_reg_form]`

= Styling of Form ==
plugin does not load any css or styling, just provide css classes in the form elements. please style as per your requirements. Don't want to super-impose my design taste to others.

However, if you want it to style response messages like Bootstrap alert messages, yu may use following css:

```css
.utd-response-cont .success {
    position: relative;
    padding: .75rem 1.25rem;
    margin-bottom: 1rem;
    border: 1px solid #c3e6cb;
    border-radius: .25rem;
    color: #155724;
    background-color: #d4edda;
}

.utd-response-cont .error {
    position: relative;
    padding: .75rem 1.25rem;
    margin-bottom: 1rem;
    border: 1px solid #f5c6cb;
    border-radius: .25rem;
    color: #721c24;
    background-color: #f8d7da;

}
```

= Is plugin translation ready =
Yes, plugin is translation ready

= Does it work with sub-domain and sub-directory setup of multisite network =
Yes, it works with both

= What shall be path of test drive site created =
test drive site slug shall be the created from user email as base to avoid conflicts in sub-domain names

= Does it have Github Repo? =
Yes, You may browse it here: [https://github.com/boospot/utestdrive](https://github.com/boospot/utestdrive)

== Screenshots ==
1. Settings Page


== Changelog ==

= 1.0.1 =

- Update: Better error message in case of user already exists
- Update: updated readme

= 1.0.0 =
Initial Release of plugin

== Upgrade Notice ==
Initial release, no upgrade notes yet.