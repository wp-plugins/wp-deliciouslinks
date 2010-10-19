=== DeliciousLinkSync ===
Contributors: dcoda
Donate link:http://wordpress.dcoda.co.uk/donate/deliciouslinksync/
Tags: php5,OMPL, synchronize, sync, links, central,delicious
Requires at least: 3.0.0
Tested up to: 3.0.1
Stable tag: 1.1.15.d6v
 Easily synchronize the links list on your blog or multiple blogs with the links in your delicious account. 
<!--

@package RSSInjection
@subpackage RSSInjection Readme
@copyright DCoda Ltd
@author DCoda Ltd
@license http://www.gnu.org/licenses/gpl.txt
$HeadURL$
$LastChangedDate$
$LastChangedRevision$
$LastChangedBy$

-->

== Description ==

This is plugin is written for PHP 5.2 or greater.

DeliciousLinkSync allows you to easily synchronize the links list on your blog or multiple blogs with your delicious account. Making it easier to maintain and organize your links.


 
== Installation ==

1. Copy the plugin folder to `wp-content/plugins`
2. Log in to WordPress as an administrator.
3. Enable the plugin in the `Plugins` admin screen.
4. Visit the admin page `Plugins->DeliciousLinkSync->Settings` to configure.
5. Set delicious account details setting page.
6. Indicate which links you wish to sync in delicious by tagging them as `sync`

Delicious tags are different to WordPress categories in that they are lower case and do not allow spaces. To allow for better looking tags in WordPress imported tags will have dashes and underscores replaces with space and the first letter of each word will be capitalized. 

== Changelog ==

= 1.1.15.d6v =

+ update base library.

= 1.0.12.D5v =

+ Pull delicious links
+ Select links tagged sync
+ Delete local links categorized Sync that do not appear.
+ Update local links categorized Sync that appear.
+ Insert local links if they do not appear.
+ Update on page views

== Copyright ==

(c) Copyright DCoda Limited, 2007 -, All Rights Reserved.

This code is released under the GPL license version 3 or later, available here:

[http://www.gnu.org/licenses/gpl.txt](http://www.gnu.org/licenses/gpl.txt)

There are so many possibly configurations of installation the plugin can be installed on we limit testing to a PHP 5.2+ Linux platform running the latest version of WordPress at the time of release but it is released WITHOUT ANY WARRANTY;
 without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
