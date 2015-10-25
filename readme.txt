=== Members ===
Contributors: greenshady
Donate link: http://themehybrid.com/donate
Tags: portfolio, images, image, post type, taxonomy
Requires at least: 3.5
Tested up to: 3.5.1
Stable tag: 0.1
License: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html

A complete portfolio plugin for creative people (built with custom post types and taxonomies).

== Description ==

**Support Questions:**  The official support forum for this plugin is located at <a href="http://themehybrid.com/support">Theme Hybrid</a>.

Custom Content Portfolio is a portfolio management plugin for creative people such as artists, photographers, and Web designers to showcase their work.  Just like what bbPress is doing for forums and BuddyPress is doing for social networks, Custom Content Portfolio is doing for portfolios.

This plugin was created because of the rising need from users who are downloading WordPress themes with portfolio management built in (not a good idea for content portability).  I wanted users to be able to switch themes without losing their content.  This will also allow any theme developer to build a theme on top of this plugin.

If you're looking for a theme that was designed specifically to work with this plugin, there's already two available from day one (with more to come):

* <a href="http://themehybrid.com/themes/chun">Chun</a>
* <a href="http://themehybrid.com/themes/adroa">Adroa</a>

Just because your theme isn't designed to work with this plugin doesn't mean it won't work.  You might just have to tinker around with some CSS to make it look good on your site.  Or, better yet, ask your theme developer to support this plugin.

**Plugin Features**

* Portfolio Items:  Allows you to create portfolio items to put into an unlimited number of portfolios.
* Portfolios:  Organize your projects how you see fit (they work sort of like tags and categories).
* Admin:  Everything is built right into the WordPress admin.  It'll look and feel just like adding posts and pages, so there's not a huge learning curve.

**Credits**

Special thanks to WooThemes for their <a href="http://www.woothemes.com/2009/09/woofunction-178-amazing-web-design-icon">icon set</a> (released under the <a href="http://www.gnu.org/licenses/gpl.html">GPL license</a>).  The folder icon is used for the admin menu and screen.

== Installation ==

1. Upload `custom-content-portfolio` to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Go to "Settings > Permalinks" in the admin to re-save your permalinks.

== Frequently Asked Questions ==

= Why was this plugin created? =

I hate when themes authors lock users into using the same theme forever or risk losing all of their content.  Many theme developers continue to add portfolios to their themes without thinking about the consequences to their users' data.  This plugin is a way to meet those theme authors in the middle.  Let users choose their design not based on functionality but based on the merits of the design.  Leave the functionality to plugin developers.

= How do I use it? =

It works just like posts or pages.  You'll get a new menu item in the admin called "Portfolio".  From there, you can create new portfolios (sort of like tags) and portfolio items (sort of like posts).

= I'm getting 404 errors. How can I fix this? =

Just visit "Settings > Permalinks" in your WordPress admin.  It will flush your rewrite rules.  After that, you shouldn't have any 404 issues.

= I don't see the "Portfolio" section. =

It should be located just below "Media" in the WordPress admin menu.  By default, only administrators can see this menu item.  If you are an administrator and can't see it after activating the plugin, deactivate and reactivate the plugin.  This should add the required permissions to your administrator role.

= How can I allow other users to create portfolio items on my site? =

By default, the "administrator" role is the only role allowed to edit portfolio-related things.  However, you can install a role management plugin like <a href="http://wordpress.org/extend/plugins/members">Members</a> to give more users access to portfolios.

The three capabilities you'll need to add to other roles are:

* `manage_portfolio`:  Allows management of the entire portfolio section (only for trusted users).
* `edit_portfolio_items`:  Allows users to edit (not publish) portfolio items and assign portfolios to those items.
* `create_portfolio_items`:  Allows users to publish portfolio items.

= Where can I get support? =

The official support forum for this plugin is located at <a href="http://themehybrid.com/support">Theme Hybrid</a>.  I don't generally have time to answer questions on the WordPress.org forums.  Theme Hybrid is my fulltime day job.

== Screenshots ==

1. Projects management admin screen.
2. Edit project admin screen.
3. Project image media modal.
4. Portfolio settings admin screen.

== Changelog ==

**Version 0.1**

* Plugin launch.  Everything's new!