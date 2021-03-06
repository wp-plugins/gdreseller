=== GDReseller ===
Contributors: tziady, navi87
Donate link: http://www.in-design.com/gdreseller
Tags: godaddy reseller, storefront interface, domain name searches
Requires at least: 3.0.1
Tested up to: 4.2.2
Stable tag: 4.2.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin helps Godaddy resellers setup a wordpress site to easily interface with their Godaddy Storefront

== Description ==
GDReseller - Godaddy Reseller Wordpress Tools: 
This plugin helps Godaddy resellers setup "Add to Cart" items via shortcodes as well as a domain widget and shortcode to allow searching of domain names through a resellers storefront

Premuim Version Available:
A premium version of the plugin is available with all products populated and personalized support. 
Please visit [GDReseller Page](https://www.in-design.com/gdreseller "Godaddy Reseller and Domain Search Plugin Page")!
Please consider the premuim plug or help support our project by donating to continue the efforts of development and maintaining this software. 
Customized installations and features are always welcome.

== Installation ==

1. Upload `GDReseller.zip` to the `/wp-content/plugins/` directory via ftp or upload plugin in your wp-admin
1. Unzip if ftp and activate the plugin through the 'Plugins' menu in WordPress
1. Populate your products and reseller ID or import product XML file - premium version only
1. Start using the shortcodes in your pages or posts or anywhere else
1. Configure your Domain Name Search widget and start using it throughout your site
1. Look through our screenshots. They clearly explain every step needed to configure the plugin

== Frequently Asked Questions ==
= What should the form URL for the products be? =
https://www.secureserver.net/gdshop/xt_orderform_addmany.asp?

= What should the domain name search widget post URL be? =
http://domain.in-design.com//domains/search.aspx?checkAvail=1&prog_id= (where domain.in-design.com is your storefront URL)

= Is there a way to make the descriptions a bulleted list? =

The description field is fully HTML capable. Please see the following example:

https://www.in-design.com/GDReseller

    If you scroll down the page and look at the Pro Reseller Plan example; the description is an HTML copy directly from my reseller storefront.
   
    So, simple answer is yes. Now, I would not recommend building tables and pages of HTML; however, you should be able to get away with most things.

== Screenshots ==

1. Add your reseller ID and submit
2. Add your products to the list
3. Add your domain search widget
4. Add your shortcodes to your posts or pages
5. Find GD settings under the general settings menu
6. Add your Godaddy ResellerID and consider allowing our footer for credit or donating to our project
8. Fill out the form and make sure that Form Action URL is "https://www.secureserver.net/gdshop/xt_orderform_addmany.asp?"
9. Import products from XML file included in the archive (premium version only). Using Wordpress importer under Tools/import
10. Activate the wordpress-importer plugin
11. Using tools import; upload the XML files
12. Import default-categories.xml first
13. Click upload file and import
14. Submit import
15. Import default-posts.xml
16. Assign author to a user already existing in your wordpress installation like your administrator and submit
17. Make sure to change Form Title and Form Description for the products to reflect what you want
18. Go under appearance and widgets. Here you will find the domain name search widget. Add it to where you like.
19. Make sure the reseller ID is yours and the Form URL is "http://YourReseller.Website.com//domains/search.aspx?checkAvail=1&prog_id="

== Changelog ==

= 1.0 =
* Initial release version.
= 1.1 =
* Fixed problems with Godaddy Reseller ID information
* Fixed problems with domain search widget functionality
= 1.2 =
* Fixed problem with ResellerID
* Finished widget changes
* Finalized Screenshots and Installation instructions
= 1.3 =
* Fixing issues with file versions, readme, and problems with shortcodes
= 1.4 =
* Added new functionality for Add to Cart and donation information
= 1.5 =
* Added information about configuration for clarification purposes.
* Tested plugin functionality with latest Wordpress 4.2.2 and looked for any conflicts - passed.
