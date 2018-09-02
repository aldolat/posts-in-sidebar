![banner](https://ps.w.org/posts-in-sidebar/assets/banner-772x250.png)

[![version][version-badge]][CHANGELOG]
[![requires][requires-badge]][README]
[![tested][tested-badge]][README]
[![license][license-badge]][LICENSE]

# Posts in Sidebar
**Contributors:** aldolat  
**Donate link:** https://dev.aldolat.it/projects/posts-in-sidebar/  
**Tags:** post, sidebar, widget, query, wp_query  
**Requires at least:** 4.6  
**Tested up to:** 5.0.0  
**Stable tag:** 4.7.0  
**License:** GPLv3 or later  
**License URI:** http://www.gnu.org/licenses/gpl-3.0.html  

This plugin adds a widget to display a list of posts in the WordPress sidebar.

## Description

Posts in Sidebar is a plugin for WordPress that lets you show a list of your posts using the criteria you want. This plugin gives you almost all the power of WordPress to retrieve the posts you want and show them in your sidebars.

The plugin has also a shortcode, that you can use in your posts/pages to list your posts.

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

### Documentation, Help & Bugs

The plugin's documentation is hosted on [GitHub](https://github.com/aldolat/posts-in-sidebar/wiki). Please refer to it before asking for support.

If you need help, please use [WordPress forum](http://wordpress.org/support/plugin/posts-in-sidebar). Do not send private email unless it is really necessary.

If you have found a bug, please report it on [GitHub](https://github.com/aldolat/posts-in-sidebar/issues).

This plugin is developed using [GitHub](https://github.com/aldolat/posts-in-sidebar). If you wrote an enhancement and would share it with the world, please send me a [Pull request](https://github.com/aldolat/posts-in-sidebar/pulls).

### Credits

I would like to say *Thank You* to all the people who helped me in making this plugin better and translated it into their respective languages.

### Privacy Policy

This plugin does not collect any user data.

## Installation

**Installing Posts in Sidebar**

This section describes how to install the plugin and get it working.

1. Upload  the `posts-in-sidebar` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the Plugins menu in WordPress
1. Go to the widgets manager and add the newly available widget into the sidebar
1. Adjust the options to fit your needs
1. Save and test your results.

**Uninstalling**

Posts in Sidebar cleans up after itself. All plugin settings will be removed from your database when the plugin is uninstalled via the Plugins screen.

## Frequently Asked Questions

Please, see [FAQ page](https://github.com/aldolat/posts-in-sidebar/wiki/FAQ) on GitHub.

## Screenshots

### 1. The widget panel (all sections are closed).
![1. The widget panel (all sections are closed).](https://ps.w.org/posts-in-sidebar/assets/screenshot-1.png)

### 2. The widget panel (all sections are open).
![2. The widget panel (all sections are open).](https://ps.w.org/posts-in-sidebar/assets/screenshot-2.png)

### 3. A simple output of the widget: title, excerpt, and link to the entire archive.
![3. A simple output of the widget: title, excerpt and link to the entire archive.](https://ps.w.org/posts-in-sidebar/assets/screenshot-3.png)

### 4. Displaying the featured image, floating left.
![4. Displaying the featured image, floating left.](https://ps.w.org/posts-in-sidebar/assets/screenshot-4.png)

### 5. The same image as before, but in a larger size.
![5. The same image as before, but in larger size.](https://ps.w.org/posts-in-sidebar/assets/screenshot-5.png)

### 6. The introductory text for the widget.
![6. The introductory text for the widget.](https://ps.w.org/posts-in-sidebar/assets/screenshot-6.png)

### 7. Displaying the full set of items (categories, date, author, tags, and so on).
![7. Displaying the full set of items (categories, date, author, tags, and so on).](https://ps.w.org/posts-in-sidebar/assets/screenshot-7.png)

## Upgrade Notice

### 4.7.0

This version of the plugin requires WordPress 4.6 (for post_parent__in option in orderby parameter).

### 2.0

This version requires WordPress 4.1 (for nested taxonomy handling).

### 1.28

This upgrade will check if the "NULL" string exists in certain variables and convert it to an empty value. After upgrading, the user must save every widget of this plugin.

### 1.27

This version resolves multiple PHP notices.

### 1.24

Bugfix for the issue when upgrading from 1.22 to 1.23.

### 1.17

This version requires WordPress 3.5 (for "post__in" option in "Order by" field).

### 1.10

Bugfix for password-protected posts.

### 1.2

Version 1.2 has changed the option to display the text of the post. When upgrading to version 1.2, check every Posts in Sidebar widget at section The text of the post to make sure that the option fits your needs.

### 1.0.2

No notice to display.

### 1.0.1

No notice to display.

### 1.0

No notice to display.

[CHANGELOG]: ./CHANGELOG.md
[LICENSE]: ./gpl-3.0.txt
[README]: ./README.md
[version-badge]: https://img.shields.io/badge/Version-4.7.0-blue.svg
[requires-badge]: https://img.shields.io/badge/Requires_WordPress-4.6.0-green.svg
[tested-badge]: https://img.shields.io/badge/Tested_up_to_WordPress-5.0.0-green.svg
[license-badge]: https://img.shields.io/badge/License-GPLv3-ff69b4.svg
