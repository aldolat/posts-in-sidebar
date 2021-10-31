# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [4.16.3] - 2021-10-31
### Changed
* Changed admin UI in the "Getting posts" section.

## [4.16.2] - 2021-10-18
### Fixed
* Fixed displaying post ID to admins only.

## [4.16.1] - 2021-10-17
### Added
* Added a new option in the Debug section for displaying the post ID just before the post title.
### Changed
* Changed the comment for an option.
### Fixed
* Fixed i18n for a string.

## [4.16.0] - 2021-10-16
### Added
* Added a comment in Custom field type fields.
* It's now possible to add custom classes to the span element of custom fields.
* Added `pis_cf_add_attribute` filter. It allows to add custom attributes to the span element of custom fields.
* Added a comment for "Skip this number of posts" (thanks to @china_guy).
### Fixed
* Fixed missing i18n (thanks to @china_guy).
* Fixed getting all posts by recent comments (thanks to @china_guy).
* Fixed "Read more..." behavior (thanks to @china_guy).

## [4.15.1] - 2021-05-15
### Added
* Added filter to date and time format in `pis_get_current_datetime()` function.
* The word `now` used as Custom field value in the Custom field query panel will be changed in the current date, ignoring time by default. This can be changed using a filter.
### Changed
* Changed date format in `pis_get_current_datetime()` function, now forced to `Y-m-d` (YYYY-MM-DD).

## [4.15.0] - 2021-03-06
### Added
* Added compatibility with Yoast SEO plugin when determining the main category of a post.
### Changed
* Cleaned and beautified HTML source code.
* Updated compatibility to WordPress 5.7.

## [4.14.0] - 2020-11-21
### Added
* Added new links to plugin line in the WordPress plugins management page.
### Changed
* Changed functions names in `pis-duplicate.js` to avoid conflicts with Duplicate Widgets plugin.
* Load the JS for duplicating a widget only if "Duplicate Widgets" plugin is not active.
* Updated compatibility to WordPress 5.6.

## [4.13.0] - 2020-10-31
### Added
* Added function to duplicate the widget.
### Changed
* Changed some strings.

## [4.12.1] - 2020-08-14
### Changed
* Changed some strings.

## [4.12.0] - 2020-08-14
### Added
* Added fields for date and time formatting.

## [4.11.0] - 2020-04-26
### Added
* In "Get posts from username", added option to get posts from a category (instead of a custom field key) that has the same name as the currently logged-in user.

## [4.10.3] - 2019-12-01
### Added
* Added time passed in the debug section, when the cache is active, and reorganized items.
### Changed
* Now uninstall delete all transients created by this plugin, not only the transients with the widget ID in their names.
### Fixed
* Removed use of PHP `time()` when creating automatic shortcode ID. In a shortcode, the user must define a `shortcode_id` when using the cache. This fixes proliferation of transients.

## [4.10.2] - 2019-11-18
### Fixed
* Fixed HTML output for time, thanks to [saajo-lab](https://github.com/saajo-lab) for his post [here](https://github.com/aldolat/posts-in-sidebar/issues/41).

## [4.10.1] - 2019-11-17
### Fixed
* Added missing domain in a string.

## [4.10.0] - 2019-11-17
### Added
* Added option to get posts from current user login name.
* Added current page URL to debug.
### Changed
* The widget introduction is now passed through PHP trim to remove extra spaces, carriage returns, etc.

## [4.9.0] - 2019-11-10
### Added
* Totally revamped cache management.
* The word `now` used as Custom field value in the Custom field query panel will be changed in the current date and time.
* Added new filter for adding hidden (protected) custom fields to the dropdown menu in the widget admin (section "Displaying posts" > "The custom fields" > "Display this custom field").
### Changed
* In debug section, if an option is active, the widget will display "true".
* Removed use of `date_i18n()`.
### Fixed
* Fixed printing the correct class for custom field key.

## [4.8.5] - 2019-10-06
### Changed
* Added sanitization for shortcode_id.
### Fixed
* Fixed PHP notice for $transient_id variable.

## [4.8.4] - 2019-09-29
### Changed
* Improved cache management.
### Added
* Display date and time of the cache expiration in the debug section.
* Added an ID when the shortcode has not an option for the ID.

## [4.8.3] - 2019-09-15
### Fixed
* Removed non-breakable space between the label and the select/checkbox menus.

## [4.8.2] - 2019-09-01
### Added
* Added a new option to choose the HTML tag for post title (thanks [@ugotta](https://wordpress.org/support/users/ugotta/) for the suggestion).
### Changed
* Minor editings.

## [4.8.1] - 2019-06-23
### Added
* Added a new option for the shortcode to define the id of the HTML container (useful when we have multiple shortcodes and we want to give a unique style to each instance).
* Fixed applying CSS in "Page Builder" by SiteOrigin plugin.
### Fixed
* Fixed applying correct id to ul element when using the shortcode.

## [4.8.0] - 2019-06-01
### Added
* Added new section for post formats when changing the query in single posts or in an archive page.
* Added a filter for the `img` element of the featured image (thanks [@chancarlo](https://github.com/chancarlo) for the suggestion).

## [4.7.7] - 2019-05-25
### Fixed
* Fixed the displaying of custom title when modifying the query in single posts/pages (thanks [@emmagbro](https://wordpress.org/support/users/emmagbro/) for reporting it).
* Code optimization.

## [4.7.6] - 2019-04-27
### Added
* The link to the archive page can be automatically generated in single post or archive pages.
### Changed
* The default options are in a separate function now to simplify the plugin.
* Code optimization.

## [4.7.5] - 2019-03-09
### Changed
* The displayed custom field value can now be filtered.
* The returned output of a meta query can now be filtered.

## [4.7.4] - 2019-02-24
### Changed
* It is no longer necessary to enter the custom field value in the "Custom field query" panel.
### Fixed
* Fixed the stripping of operators containing `<` or `>` in custom field query.

## [4.7.3] - 2018-11-18
### Added
* Added support for private custom post types.

## [4.7.2] - 2018-10-14
### Added
* Added option to display the number of comments only.
* Added option to hide the comments section if there is no comment.
### Fixed
* Fixed the displaying of comments number.

## [4.7.1] - 2018-09-16
### Fixed
* Reverted widget title escaping which can break use of the widget_title filter. Thanks to [@mlang38](https://github.com/mlang38) for [reporting this](https://github.com/aldolat/posts-in-sidebar/issues/37).
* Fixed link to review page on wordpress.org when viewing the plugin list page.

## [4.7.0] - 2018-09-01
### Added
* Added new option to filter posts in the same category/tag/author/custom-field by searching for the title of the main post.
* Added "Post type", "Relevance (when searching)", and "Preserve post parent order" in "Order posts by" option.
* Added new options when changing the query in single posts or in an archive page.
### Changed
* Now custom post types are supported when changing the query in single posts.
* Reorganized some sub-panels and added colors in panel titles.
* Checked all files with [WordPress Coding Standard for PHPCS](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards).
### Removed
* Removed old comment for HTML in widget introduction.
### Fixed
* Fixed displaying the query in debug section when the query contains multidimensional arrays.
### Security
* Hardened security.

## [4.6.0] - 2018-07-01
### Added
* Added option to get posts from the current archive page (category, tag, and author).
### Changed
* Changed wp_kses to wp_kses_post for widget introduction.
* Now debug options are displayed without execution.

## [4.5.2] - 2018-05-13
### Fixed
* Fixed opening widgets containers when used with SiteOrigin page builder.

## [4.5.1] - 2018-01-29
### Fixed
* Fixed displaying UTF-8 charset when shortening titles and excerpts.

## [4.5.0] - 2018-01-28
### Added
* Added options for title and WordPress-generated excerpt length unit.
* Added filter for HTML arrows in title and excerpt.
* Added CSS ID selector to ul.

## [4.4.0] - 2017-09-24
### Added
* Added option to shorten post titles.

## [4.3.0] - 2017-07-02
### Added
* Added option to get posts from the current tag, when on a single post.
* Added option to assign post classes via `get_post_class()`.
### Fixed
* Fixed empty p tag when no excerpt or no image.
* Fixed post modification date/time.
* Minor fixes.

## [4.2.0] - 2017-06-25
### Added
* Added option to display the post time and modification time.
### Changed
* Aligned shortcode options to main options.
* Comments are not linked to comments section in the post by default.

## [4.1] - 2017-06-04
### Added
* Added option to get posts by recent comments.
* Added option to display all the custom fields of the post.

## [4.0] - 2017-05-21
### Added
* Added section for retrieving posts from multiple custom fields.
* Added option for retrieving posts with/without password.
* Added option for retrieving posts with a certain password.
### Changed
* Now panels do not collapse after saving the widget.

## [3.8.8] - 2017-04-30
### Added
* Added option to get multiple post types.
### Removed
* Removed option for displaying query number (it's displayed by default now).

## [3.8.7] - 2017-04-16
### Fixed
* Fixed link on titles and title arrows.
* Fixed a bug where, regardless of the state of "Display the featured image of the post" checkbox, the featured image was always visible if the "Display this section before the title of the post" checkbox were active.

## [3.8.6] - 2017-04-15
### Added
* Added option to get posts from a certain amount of time ago.
### Removed
* Removed empty items from the array for the query.
* Removed some default settings in custom taxonomy query.

## [3.8.5] - 2017-04-11
### Changed
* Updated the description in "When on single posts, get posts from the current category".

## [3.8.4] - 2017-04-09
### Added
* Added option to maintain active other parameters when on single post (requested by techsmurfy).
* Added option to sort categories of the main post before getting the posts for the sidebar. When the user wants to get posts from the current category, now the user can choose to fetch posts from the main category of the main post (i.e. the category with the lowest ID) or from the category with the lowest key ID in the array of categories (requested by techsmurfy).
* Added options to move certain parts before/after the title.
* Added Indonesian translation, thanks to Jordan Silaen.
### Removed
* Removed empty lines in the HTML source.

## [3.8.3] - 2017-03-11
### Added
* Added option to display debugging information to admins only.
* Now the custom field value for getting posts via custom field key can be the taxonomy ID (requested by morfe1).
### Removed
* Removed Italian l10n from `languages/` directory, because the Italian language pack in GlotPress is completed.
### Security
* Hardening security on i18n.

## [3.8.2] - 2016-12-04
### Changed
* Rewritten function for displaying the Read more.
* Completely removed the HTML title attribute on links.
* Aligned widget and shortcode options.
### Fixed
* Minor corrections.

## [3.8.1] - 2016-09-17
### Changed
* Exclude current post even if the user has specified a list of IDs.
### Removed
* Removed title attribute on links.

## [3.8] - 2016-06-02
### Fixed
* FIX: The "Rich Content" option for excerpt correctly executes shortcodes now.
### Changed
* If the length of a WordPress-generated excerpt is smaller than or equal to the maximum length defined by the user for the excerpt, the "Read more..." text is automatically hidden.
* Reduced widget width to 600px in the admin area.

## [3.7] - 2016-05-24
### Added
* NEW: Added support to get posts, when on single post, from user-defined category/tag using custom field (requested by Mike S).
* NEW: Added support for changing number of posts when on single post.
### Fixed
* FIX: fixed displaying comments string when using languages different from English.

## [3.6] - 2016-04-02
### Added
* NEW: now the user can remove the link of the featured image.

## [3.5] - 2016-03-24
### Added
* NEW: Added support to get posts by the author of the current post (requested by Derek).
### Fixed
* FIX: fixed getting posts by category slug.
### Changed
* Updated the shortcodes options.

## [3.4] - 2016-02-11
### Changed
* Updated the shortcodes options.

## [3.3.1] - 2016-02-06
### Fixed
* FIX: fixed wrong characters displaying in custom field values (requested by [bubdev](https://wordpress.org/support/profile/bubdev)).
* Minor improvements.

## [3.3] - 2016-01-21
### Added
* NEW: Added option to truncate the custom field content (requested by [bubdev](https://wordpress.org/support/profile/bubdev)).

## [3.2] - 2015-12-31
### Added
* NEW: Added support to get posts from the category of the current post (requested by [wendygordon](https://wordpress.org/support/profile/wendygordon)).
* Minor improvements.

## [3.1] - 2015-11-08
### Added
* NEW: Added option to display the modification date (requested by [ecdltf](https://wordpress.org/support/profile/ecdltf)).

## [3.0.1] - 2015-10-31
### Fixed
* Fixed shortcodes execution in "Full content" type of text (thanks to [fabianfabian](https://wordpress.org/support/profile/fabianfabian) for reporting).
### Added
* Added check if the post is private.
* Added check for current post in pages.
* Added link to review page in the plugins list page.

## [3.0] - 2015-10-03
### Added
* NEW: Added the shortcode.
* NEW: Added Gravatar support for authors.
* NEW: Added "Any" to posts status.
* NEW: Added an option to change the tooltip in the title link.
### Changed
* Reorganized the widget sections.
* Changed some files names.
* Changed translation domain into posts-in-sidebar.

## [2.0.4] - 2015-08-12
### Fixed
* Fixed custom container DIV (thanks to felipebadr).
* Minor improvements.

## [2.0.3] - 2015-08-10
### Fixed
* FIX: Fixed sticky posts.
### Added
* The complete list of widget options is now fully displayed.
* Other minor improvements.

## [2.0.2] - 2015-08-09
### Fixed
* FIX: Fixed link on widget title.

## [2.0.1] - 2015-08-09
### Fixed
* FIX: Fixed printing local style.

## [2.0] - 2015-08-09
### Added
* NEW: Support for taxonomy complex queries (requires WordPress 4.1).
* NEW: Support for date queries.
* NEW: Support for for getting and excluding posts by multiple authors.
* NEW: Support for queries based on search.
* NEW: Support for getting and excluding posts that are children of other posts.
* NEW: Support for custom taxonomies.
* NEW: Added support for custom link in featured image (requested by troy-f).
* NEW: Changed appearance for widget sections that are collapsible now.
### Changed
* WordPress 4.1 is required (for nested taxonomy handling).
* Added URL of the site and WordPress version in the debug section.
* Switched to PHP5 `__contruct()` in creating the widget.
* Updated the Hebrew translation (thanks to Ahrale).
### Security
* Improved security.
### Fixed
* Fixed PHP notices when upgrading from previous versions.

## [1.28] - 2015-04-12
### Added
* Added check for "NULL" (as string) value in certain variables.
### Fixed
* FIX: Now the plugin correctly displays attachment post type.
* FIX: Custom featured images are now displayed when the user choose no text to display.

## [1.27] - 2015-03-29
### Added
* NEW: Now it's possible to display the name of the taxonomy in the archive link.
### Fixed
* FIX: resolved multiple PHP notices.

## [1.26] - 2015-03-22
### Changed
* Compatibility with Relevantssi plugin (requested by KTS915).
* Updated the Hebrew translation (thanks to Ahrale).

## [1.25] - 2015-03-14
### Added
* NEW: Added options to use a custom image instead of the standard featured image (requested by joaogsr).
* NEW: Added class "sticky" if a post is sticky (requested by acrok).
* Added placeholders in HTML fields.
### Changed
* CHANGE: Added a checkbox to completely hide the widget if no posts are found (instead of removing the "no posts text" in order to do this).

## [1.24] - 2015-02-28
### Fixed
* FIX: resolved "No posts" issue when upgrading from 1.22 version.
### Changed
* Updated Hebrew (thanks to Ahrale) and Italian translations.

## [1.23] - 2015-02-25
### Added
* NEW: the widget can be hidden now if no posts are found (requested by der_velli).
* NEW: Added the option to display the full size of the featured image (requested by Ilaria).
### Changed
* Moved plugin's functions files into subfolder.
* Moved plugin's functions into separate file.
* Added debugging tools.
* Updated the Hebrew translation (thanks to Ahrale).
* Code improvements.
### Fixed
* Fixed one undefined index notice.

## [1.22] - 2014-11-05
### Added
* NEW: Added an option to display only the "Read more..." link.
* Added Serbo-Croatian language (thanks to Borisa Djuraskovic).
### Changed
* Updated the Hebrew translation (thanks to Ahrale).

## [1.21] - 2014-08-11
### Added
* NEW: Added an option to exclude the current post in single post page or the current page in single page.
* Added an alert in the widget admin if the current theme doesn't support the Post Thumbnail feature.

## [1.20] - 2014-06-29
### Fixed
* FIX: Now the dropdown menu for post type selection correctly displays all the public post types (thanks to pathuri).

## [1.19] - 2014-05-24
### Added
* NEW: Selection of categories and tags is in form of comma separated values. This will prevent server load in case there are too many terms. Also, now the user can get posts from multiple categories.

## [1.18] - 2014-03-16
### Added
* NEW: The section with author, date, and comments can now be displayed before the post's excerpt.
### Changed
* Various small improvements.

## [1.17] - 2014-01-25
### Added
* NEW: Added option to exclude posts with certain IDs.
* NEW: Added option to display image before post title.
* NEW: Completed options for Order by parameter.
* Now the plugin requires at least WordPress 3.5.
### Changed
* Code optimization.
* The custom container receives now only a single CSS class.
* Completed the PhpDocumentor tags.
### Security
* The class for the custom container class is now sanitized.

## [1.16.1] - 2014-01-06
### Added
* NEW: The cache can be flushed now.
### Changed
* Updated Hebrew translation.

## [1.16] - 2014-01-05
### Added
* NEW: Added a field to define a class for a container.
* NEW: Now the user can define a cache when retrieving posts from database.

## [1.15.1] - 2013-12-15
### Fixed
* FIX: The HTML for ul is now fixed.

## [1.15] - 2013-12-15
### Added
* NEW: The posts can be retrieved using the ID (requested by Matt).
* NEW: The list of posts can now be displayed in a numbered list (requested by Sean).
* NEW: The excerpt can be displayed up to the "more" tag (requested by EvertVd).
### Fixed
* FIX: There are no more empty spaces after "Category" or "Tags" text.
### Changed
* The widget panel has been slightly enlarged.
* Minor refinements.
### Removed
* Deleted unused options in widgets dropdown menus.

## [1.14] - 2013-10-02
### Fixed
* FIX: fetching posts from tags now works correctly.
### Changed
* Updated Hebrew translation, thanks to Ahrale.

## [1.13] - 2013-08-30
### Added
* NEW: Added option for adding user defined styles (requested by Ahrale).
* NEW: Added option for setting the space around the image (requested by Ahrale).
* NEW: Added check for rtl languages (the arrow can now be from right to left, requested by Ahrale).
* NEW: Added option for ordering by "Menu order" and "Comment count" (requested by hypn0ticnet).
### Changed
* Updated Hebrew translation (thanks to Ahrale).
* Minor enhancements.
### Fixed
* Minor bug fixings.

## [1.12] - 2013-08-10
### Added
* NEW: added option for rich content.
* NEW: added option for displaying the custom fields value/key of the post.
* NEW: added option for removing bullets and extra left space for the list elements.
### Changed
* Code improvements.

## [1.11] - 2013-07-24
### Fixed
* FIX: image align has been fixed (thanks to Clarry).

## [1.10] - 2013-07-23
### Added
* NEW: Now the user-defined excerpt can display a paragraph break, if any.
* NEW: Added Hebrew translation, thanks to Ahrale.
### Fixed
* FIX: If the post is password protected, now the post password form is displayed before showing the post.
### Changed
* Other minor changes.

## [1.9] - 2013-06-22
### Added
* NEW: The space after each line can be defined via widget interface.
* NEW: The featured image can be aligned with text.
* NEW: Added `apply_filters` where needed.
### Fixed
* FIX: HTML structure for the archive link is now W3C valid, thanks to [cilya](http://wordpress.org/support/profile/cilya) for reporting it.
* Minor bug fixings.
### Changed
* Updated French translation, thanks to [cilya](http://wordpress.org/support/profile/cilya).

## [1.8] - 2013-06-08
### Added
* New: added post format as option to get posts.
* New: added option for link to custom post type archive.
* New: added option for link to post format archive.
### Changed
* Other minor changes.

## [1.7] - 2013-06-01
### Added
* New: The widget can display the author of the post.
* New: Now the user can choose which type of posts to display: posts, pages, custom post types, etc.
* New: The widget can display the full content (as in single posts).
* New: Now the user can add a custom "Read more" text.
* Added French translation by Thérèse Lachance.
### Security
* Code improvements and sanitization.

## [1.6.1] - 2013-04-17
### Fixed
* Minor fixes.

## [1.6] - 2013-04-17
### Added
* New: if in single post, the user can now stylize the current post in the sidebar (feature request from lleroy).

## [1.5] - 2013-03-28
### Added
* New: Now the title of the widget can be linked to a user-defined URL (feature request from Mike).

## [1.4] - 2013-03-24
### Added
* New: Now the user can add an introductory text to the widget (feature request from Mike).

## [1.3] - 2013-01-11
### Added
* New: The date can be linkified or not.
* New: The widget panel now shows empty categories and tags.
* New: The 'No posts yet.' text can be customized.
### Fixed
* Bug fix: The markup no longer shows empty containers.
### Changed
* Some minor enhancements.

## 1.2.1 - 2012-11-02
### Changed
* Changed the minimum required WordPress version to 3.3.
### Added
* Added Persian language, thanks to AlirezaJamali.

## 1.2 - 2012-10-20
### Added
* Enhancement: Now the user can display the entire content for each post. Feature request from [sjmsing](http://wordpress.org/support/topic/plugin-posts-in-sidebar-great-plugin-feature-request)
### Changed
* Moved screenshots to `/assets/` directory.

## 1.1 - 2012-09-29
### Added
* Enhancement: Now it is possible to show the categories of the post
* Enhancement: Now it is possible to exclude posts coming from some categories and/or tags
### Changed
* Moved the widget section into a separate file.

## 1.0.2 - 2012-09-10
### Added
* Updated *Credits* section.

## 1.0.1 - 2012-09-10
### Changed
* Small typo in `readme.txt`.

## 1.0 - 2012-09-10
### Added
* First release of the plugin.

[Unreleased]: https://github.com/aldolat/posts-in-sidebar/commits/develop
[4.16.3]: https://github.com/aldolat/posts-in-sidebar/compare/4.16.2...4.16.3
[4.16.2]: https://github.com/aldolat/posts-in-sidebar/compare/4.16.1...4.16.2
[4.16.1]: https://github.com/aldolat/posts-in-sidebar/compare/4.16.0...4.16.1
[4.16.0]: https://github.com/aldolat/posts-in-sidebar/compare/4.15.1...4.16.0
[4.15.1]: https://github.com/aldolat/posts-in-sidebar/compare/4.15.0...4.15.1
[4.15.0]: https://github.com/aldolat/posts-in-sidebar/compare/4.14.0...4.15.0
[4.14.0]: https://github.com/aldolat/posts-in-sidebar/compare/4.13.0...4.14.0
[4.13.0]: https://github.com/aldolat/posts-in-sidebar/compare/4.12.1...4.13.0
[4.12.1]: https://github.com/aldolat/posts-in-sidebar/compare/4.12.0...4.12.1
[4.12.0]: https://github.com/aldolat/posts-in-sidebar/compare/4.11.0...4.12.0
[4.11.0]: https://github.com/aldolat/posts-in-sidebar/compare/4.10.3...4.11.0
[4.10.3]: https://github.com/aldolat/posts-in-sidebar/compare/4.10.2...4.10.3
[4.10.2]: https://github.com/aldolat/posts-in-sidebar/compare/4.10.1...4.10.2
[4.10.1]: https://github.com/aldolat/posts-in-sidebar/compare/4.10.0...4.10.1
[4.10.0]: https://github.com/aldolat/posts-in-sidebar/compare/4.9.0...4.10.0
[4.9.0]: https://github.com/aldolat/posts-in-sidebar/compare/4.8.5...4.9.0
[4.8.5]: https://github.com/aldolat/posts-in-sidebar/compare/4.8.4...4.8.5
[4.8.4]: https://github.com/aldolat/posts-in-sidebar/compare/4.8.3...4.8.4
[4.8.3]: https://github.com/aldolat/posts-in-sidebar/compare/4.8.2...4.8.3
[4.8.2]: https://github.com/aldolat/posts-in-sidebar/compare/4.8.1...4.8.2
[4.8.1]: https://github.com/aldolat/posts-in-sidebar/compare/4.8.0...4.8.1
[4.8.0]: https://github.com/aldolat/posts-in-sidebar/compare/4.7.7...4.8.0
[4.7.7]: https://github.com/aldolat/posts-in-sidebar/compare/4.7.6...4.7.7
[4.7.6]: https://github.com/aldolat/posts-in-sidebar/compare/4.7.5...4.7.6
[4.7.5]: https://github.com/aldolat/posts-in-sidebar/compare/4.7.4...4.7.5
[4.7.4]: https://github.com/aldolat/posts-in-sidebar/compare/4.7.3...4.7.4
[4.7.3]: https://github.com/aldolat/posts-in-sidebar/compare/4.7.2...4.7.3
[4.7.2]: https://github.com/aldolat/posts-in-sidebar/compare/4.7.1...4.7.2
[4.7.1]: https://github.com/aldolat/posts-in-sidebar/compare/4.7.0...4.7.1
[4.7.0]: https://github.com/aldolat/posts-in-sidebar/compare/4.6.0...4.7.0
[4.6.0]: https://github.com/aldolat/posts-in-sidebar/compare/4.5.2...4.6.0
[4.5.2]: https://github.com/aldolat/posts-in-sidebar/compare/4.5.1...4.5.2
[4.5.1]: https://github.com/aldolat/posts-in-sidebar/compare/4.5.0...4.5.1
[4.5.0]: https://github.com/aldolat/posts-in-sidebar/compare/4.4.0...4.5.0
[4.4.0]: https://github.com/aldolat/posts-in-sidebar/compare/4.3.0...4.4.0
[4.3.0]: https://github.com/aldolat/posts-in-sidebar/compare/4.2.0...4.3.0
[4.2.0]: https://github.com/aldolat/posts-in-sidebar/compare/4.1...4.2.0
[4.1]: https://github.com/aldolat/posts-in-sidebar/compare/4.0...4.1
[4.0]: https://github.com/aldolat/posts-in-sidebar/compare/3.8.8...4.0
[3.8.8]: https://github.com/aldolat/posts-in-sidebar/compare/3.8.7...3.8.8
[3.8.7]: https://github.com/aldolat/posts-in-sidebar/compare/3.8.6...3.8.7
[3.8.6]: https://github.com/aldolat/posts-in-sidebar/compare/3.8.5...3.8.6
[3.8.5]: https://github.com/aldolat/posts-in-sidebar/compare/3.8.4...3.8.5
[3.8.4]: https://github.com/aldolat/posts-in-sidebar/compare/3.8.3...3.8.4
[3.8.3]: https://github.com/aldolat/posts-in-sidebar/compare/3.8.2...3.8.3
[3.8.2]: https://github.com/aldolat/posts-in-sidebar/compare/3.8.1...3.8.2
[3.8.1]: https://github.com/aldolat/posts-in-sidebar/compare/3.8...3.8.1
[3.8]: https://github.com/aldolat/posts-in-sidebar/compare/3.7...3.8
[3.7]: https://github.com/aldolat/posts-in-sidebar/compare/3.6...3.7
[3.6]: https://github.com/aldolat/posts-in-sidebar/compare/3.5...3.6
[3.5]: https://github.com/aldolat/posts-in-sidebar/compare/3.4...3.5
[3.4]: https://github.com/aldolat/posts-in-sidebar/compare/3.3.1...3.4
[3.3.1]: https://github.com/aldolat/posts-in-sidebar/compare/3.3...3.3.1
[3.3]: https://github.com/aldolat/posts-in-sidebar/compare/3.2...3.3
[3.2]: https://github.com/aldolat/posts-in-sidebar/compare/3.1...3.2
[3.1]: https://github.com/aldolat/posts-in-sidebar/compare/3.0.1...3.1
[3.0.1]: https://github.com/aldolat/posts-in-sidebar/compare/3.0...3.0.1
[3.0]: https://github.com/aldolat/posts-in-sidebar/compare/2.0.4...3.0
[2.0.4]: https://github.com/aldolat/posts-in-sidebar/compare/2.0.3...2.0.4
[2.0.3]: https://github.com/aldolat/posts-in-sidebar/compare/2.0.2...2.0.3
[2.0.2]: https://github.com/aldolat/posts-in-sidebar/compare/2.0.1...2.0.2
[2.0.1]: https://github.com/aldolat/posts-in-sidebar/compare/2.0...2.0.1
[2.0]: https://github.com/aldolat/posts-in-sidebar/compare/1.28...2.0
[1.28]: https://github.com/aldolat/posts-in-sidebar/compare/1.27...1.28
[1.27]: https://github.com/aldolat/posts-in-sidebar/compare/1.26...1.27
[1.26]: https://github.com/aldolat/posts-in-sidebar/compare/1.25...1.26
[1.25]: https://github.com/aldolat/posts-in-sidebar/compare/1.24...1.25
[1.24]: https://github.com/aldolat/posts-in-sidebar/compare/1.23...1.24
[1.23]: https://github.com/aldolat/posts-in-sidebar/compare/1.22...1.23
[1.22]: https://github.com/aldolat/posts-in-sidebar/compare/1.21...1.22
[1.21]: https://github.com/aldolat/posts-in-sidebar/compare/1.20...1.21
[1.20]: https://github.com/aldolat/posts-in-sidebar/compare/1.19...1.20
[1.19]: https://github.com/aldolat/posts-in-sidebar/compare/1.18...1.19
[1.18]: https://github.com/aldolat/posts-in-sidebar/compare/1.17...1.18
[1.17]: https://github.com/aldolat/posts-in-sidebar/compare/v1.16.1...1.17
[1.16.1]: https://github.com/aldolat/posts-in-sidebar/compare/v1.16...v1.16.1
[1.16]: https://github.com/aldolat/posts-in-sidebar/compare/v1.15.1...v1.16
[1.15.1]: https://github.com/aldolat/posts-in-sidebar/compare/v1.15...v1.15.1
[1.15]: https://github.com/aldolat/posts-in-sidebar/compare/v1.14...v1.15
[1.14]: https://github.com/aldolat/posts-in-sidebar/compare/v1.13...v1.14
[1.13]: https://github.com/aldolat/posts-in-sidebar/compare/v1.12...v1.13
[1.12]: https://github.com/aldolat/posts-in-sidebar/compare/v1.11...v1.12
[1.11]: https://github.com/aldolat/posts-in-sidebar/compare/v1.10...v1.11
[1.10]: https://github.com/aldolat/posts-in-sidebar/compare/v1.9...v1.10
[1.9]: https://github.com/aldolat/posts-in-sidebar/compare/v1.8...v1.9
[1.8]: https://github.com/aldolat/posts-in-sidebar/compare/v1.7...v1.8
[1.7]: https://github.com/aldolat/posts-in-sidebar/compare/v1.6.1...v1.7
[1.6.1]: https://github.com/aldolat/posts-in-sidebar/compare/v1.6...v1.6.1
[1.6]: https://github.com/aldolat/posts-in-sidebar/compare/v1.5...v1.6
[1.5]: https://github.com/aldolat/posts-in-sidebar/compare/v1.4...v1.5
[1.4]: https://github.com/aldolat/posts-in-sidebar/compare/v1.3...v1.4
[1.3]: https://github.com/aldolat/posts-in-sidebar/releases/tag/v1.3
