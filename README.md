# Posts in Sidebar

![banner](https://ps.w.org/posts-in-sidebar/assets/banner-772x250.png)

**Contributors:** aldolat  
**Donate link:** <https://dev.aldolat.it/projects/posts-in-sidebar/>  
**Tags:** post, sidebar, widget, query, wp_query  
**Requires at least:** 4.6  
**Tested up to:** 5.8  
**Stable tag:** 4.16.3  
**License:** GPLv3 or later  
**License URI:** <https://www.gnu.org/licenses/gpl-3.0.html>

This plugin adds a widget to display a list of posts in the WordPress sidebar.

* [Posts in Sidebar](#posts-in-sidebar)
  * [Description](#description)
  * [Documentation, Help & Bugs](#documentation-help--bugs)
  * [Installation](#installation)
    * [Installing Posts in Sidebar](#installing-posts-in-sidebar)
    * [Uninstalling Posts in Sidebar](#uninstalling-posts-in-sidebar)
  * [Screenshots](#screenshots)
  * [Frequently Asked Questions](#frequently-asked-questions)
  * [License](#license)
  * [Credits](#credits)
  * [Privacy Policy](#privacy-policy)
  * [Changelog](#changelog)
    * [4.16.2](#4162)

## Description

Posts in Sidebar is a plugin for WordPress that lets you show a list of your posts using the criteria you want. This plugin gives you almost all the power of WordPress to retrieve the posts you want and show them in your sidebars.

The plugin has also a shortcode, that you can use in your posts/pages to list your posts. You can find more information in the [Wiki page on GitHub](https://github.com/aldolat/posts-in-sidebar/wiki/The-Shortcode).

Once installed, Posts in Sidebar creates a new widget for your sidebar. Add it to your sidebar, select the options to retrieve the posts you want, and save the widget: you're done!

Here are some of the functions you'll have:

* get posts by authors, categories, tags, post format, or any custom taxonomy;
* get posts by exact IDs;
* get posts by meta key/value;
* get posts by modification date;
* get posts children of other posts;
* get posts by search;
* get posts by recent comments;
* get posts by complex taxonomies queries;
* get posts by complex date queries;
* get posts by complex custom fields queries;
* get posts from the category of the current post;
* exclude posts by authors, taxonomies, and so on;
* control which elements of the posts are displayed (like post thumbnail, taxonomies, meta values, and so on);
* stylize the output of the widget using custom CSS styles;
* cache the output of the widget to save queries to the database;
* ... and much more.

The powerful WordPress class `WP_Query` is at your fingertips with this plugin. To understand what this plugin can do, take a look at this [Codex page](https://codex.wordpress.org/Class_Reference/WP_Query): almost all these functions are already included in Posts in Sidebar.

This plugin is [free software](https://en.wikipedia.org/wiki/Free_software) and it's developed with many efforts: [a donation](https://dev.aldolat.it/projects/posts-in-sidebar/#donate) is very appreciated.

Enjoy!

## Documentation, Help & Bugs

The plugin's documentation is hosted on [GitHub](https://github.com/aldolat/posts-in-sidebar/wiki). Please refer to it before asking for support.

If you need help, please use [WordPress forum](http://wordpress.org/support/plugin/posts-in-sidebar). Do not send private email unless it is really necessary.

If you have found a bug, please report it on [GitHub](https://github.com/aldolat/posts-in-sidebar/issues).

This plugin is developed using [GitHub](https://github.com/aldolat/posts-in-sidebar). If you wrote an enhancement and would share it with the world, please send me a [Pull request](https://github.com/aldolat/posts-in-sidebar/pulls).

## Installation

### Installing Posts in Sidebar

This section describes how to install the plugin and get it working.

1. Upload  the `posts-in-sidebar` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the Plugins menu in WordPress
1. Go to the widgets manager and add the newly available widget into the sidebar
1. Adjust the options to fit your needs
1. Save and test your results.

### Uninstalling Posts in Sidebar

Posts in Sidebar cleans up after itself. All plugin settings will be removed from your database when the plugin is uninstalled via the Plugins screen.

## Screenshots

The updated screenshots are available in the [official wiki](https://github.com/aldolat/posts-in-sidebar/wiki/Screenshots) of this plugin.

## Frequently Asked Questions

Please, see [FAQ page](https://github.com/aldolat/posts-in-sidebar/wiki/FAQ) on GitHub.

## License

This software is released under the terms of the [GNU GPLv3](https://github.com/aldolat/posts-in-sidebar/blob/master/LICENSE) or later.

## Credits

I would like to say *Thank You* to all the people who helped me in making this plugin better and translated it into their respective languages.

This plugin uses the following Javascript code, released under the terms of the GNU GPLv2 or later:

* a modified version of @kometschuh's code for "Category Posts Widget" plugin, used to open and close panels in the widget admin user interface;
* a modified version of @themesfactory's code for "Duplicate Widgets" plugin, used to duplicate a widget.

Thanks to these developers for their work and for using the GNU General Public License.

## Privacy Policy

This plugin does not collect any user data.

## Changelog

### 4.16.3

* Changed admin UI in the "Getting posts" section.

The full changelog is documented in the changelog file released along with the plugin package and is hosted on [GitHub](https://github.com/aldolat/posts-in-sidebar/blob/master/CHANGELOG.md).
