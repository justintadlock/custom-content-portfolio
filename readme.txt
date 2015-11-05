=== Custom Content Portfolio ===
Contributors: greenshady
Donate link: http://themehybrid.com/donate
Tags: portfolio, images, image, post type, taxonomy
Requires at least: 4.3
Stable tag: 1.0.1
License: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html

A complete portfolio solution for creative people.

== Description ==

Custom Content Portfolio is a portfolio management plugin for creative people such as artists, photographers, and Web designers to showcase their work.  Just like what bbPress is doing for forums and BuddyPress is doing for social networks, Custom Content Portfolio is doing for portfolios.

This plugin was created because of the rising need from users who are downloading WordPress themes with portfolio management built in (not a good idea for content portability).  I wanted users to be able to switch themes without losing their content.  This will also allow any theme developer to build a theme on top of this plugin.

### Features

* **Projects:** Create individual projects for your portfolio.
* **Categories:** Categorize portfolio projects.
* **Tags:** Tag individual portfolio projects.
* **Project Details:** Add custom project details such as a project image, URL, client, location, start/end dates, and a description.
* **Custom Permalinks:** Customize your portfolio permalinks to your own liking.
* **Sticky Projects:** Stick projects to the portfolio page.
* **Post Formats:** If your theme supports them, projects can have the audio, gallery, image, or video formats.

For more info, vist the [Custom Content Portfolio](http://themehybrid.com/plugin/custom-content-portfolio) plugin home page.

### Like this plugin?

Please consider helping the cause by:

* [Making a donation](http://themehybrid.com/donate).
* [Signing up at my site](http://themehybrid.com/club).
* [Rating the plugin](https://wordpress.org/support/view/plugin-reviews/custom-content-portfolio?rate=5#postform).

### Professional Support

If you need professional plugin support from me, the plugin author, you can access the support forums at [Theme Hybrid](http://themehybrid.com/board/topics), which is a professional WordPress help/support site where I handle support for all my plugins and themes for a community of 60,000+ users (and growing).

### Plugin Development

If you're a theme author, plugin author, or just a code hobbyist, you can follow the development of this plugin on it's [GitHub repository](https://github.com/justintadlock/custom-content-portfolio). 

== Installation ==

1. Upload `custom-content-portfolio` to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Go to "Portfolio > Settings" in the admin to set up your portfolio.

== Frequently Asked Questions ==

= Upgrading from pre-1.0.0. What's the deal? =

CCP underwent a complete rewrite from the ground up.  There's very little about the plugin that's even remotely close to the original version.

The biggest thing to note is that this plugin writes directly to the database to change some post type, taxonomy, and post metadata values.  I've tested this extensively, but I still encourage you to **make a backup of your database** prior to upgrading in case anything goes wrong.

The second biggest thing is that you might need to deactivate and reactivate the plugin if you're missing an admin items under "Portfolio".  This should correct any issues.

The third and final item is that you'll want to set your permalinks via "Portfolio > Settings > Permalinks" in the admin.

= Why was this plugin created? =

I hate when themes authors lock users into using the same theme forever or risk losing all of their content.  Many theme developers continue to add portfolios to their themes without thinking about the consequences to their users' data.  This plugin is a way to meet those theme authors in the middle.  Let users choose their design not based on functionality but based on the merits of the design.  Leave the functionality to plugin developers.

= How do I use it? =

It works just like posts or pages.  You'll get a new menu item in the admin called "Portfolio".  From there, you can create new projects (sort of like posts/pages).

= I'm getting 404 errors. How can I fix this? =

You need to save your portfolio permalinks by visiting "Portfolio > Settings" in the admin.  There's a section titled "Permalinks" that will allow you to set up your portfolio rewrite rules.  After saving, any 404s should disappear.

= I don't see the "Portfolio" section. =

By default, only administrators can see this menu item.  If you are an administrator and can't see it after activating the plugin, deactivate and reactivate the plugin.  This should add the required permissions to your administrator role.

= How can I allow other users to create portfolio items on my site? =

By default, the "administrator" role is the only role allowed to edit portfolio-related things.  However, you can install a role management plugin like [Members](http://wordpress.org/plugins/members) to give more users access to portfolio.

= How does this compare with Jetpack portfolios? =

While Jetpack can certainly be a useful plugin, its portolio module is not even in the same league.  Jetpack offers some of the same basic stuff that you can do with a custom post type, but that's where the similarities end.  CCP is a complete portfolio solution.

= Will it work with my theme? =

The plugin should work fine with any theme that's coded to WordPress standards.  However, some features may not appear on the front end without direct integration by your theme author.

= How do I add theme support? =

If you're a theme author, you can declare theme support with the following code in your theme setup function:

	add_theme_support( 'custom-content-portfolio' );

= Where can I get support? =

The official support forum for this plugin is located at [Theme Hybrid](http://themehybrid.com/board/topics). 

== Screenshots ==

1. Projects management admin screen.
2. Edit project admin screen.
3. Project image media modal.
4. Portfolio settings admin screen.
5. Screenshot of the portfolio on the front end.

== Upgrade Notice ==

If upgrading from a version prior to 1.0.0, please read the FAQ before upgrading.

== Changelog ==

The change log is located in the `changelog.md` file in the plugin folder.  You may also [view the change log](https://github.com/justintadlock/custom-content-portfolio/blob/master/changelog.md) online.