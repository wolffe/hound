= 0.X.X =
* TODO: Remove classes
* TODO: Add basic plugins panel with name, version, author and URI
* TODO: Add unique content directory
* TODO: Group all core files
* TODO: Better upgrader
* TODO: Responsive default theme
* TODO: AJAX
* TODO: PureCSS
* TODO: Better check_login function
* TODO: Update FontAwesome

= 0.8.4 =
* UPDATE: Changed path detection for performance
* UPDATE: Added more constants to config file

= 0.8.3 =
* FIX: Path fixes
* FIX: Media modal fixes
* FIX: Permission check fixes

= 0.8.2 =
* core/admin
* core/config.php
* core/hound.php
* core/plugins.php
* core/template.class.php
* content/plugins
* content/site
* content/files
* content/backup
* vendor/*

= 0.8.0 =
* UPDATE: Refactored authentication check (in progress...)
* UPDATE: Refactored core/content files (in progress...)
* UPDATE: Refactored core using a functional approach
* UPDATE: Removed tagline/slogan
* UPDATE: Removed access log
* UPDATE: Removed .php from template on post/page screen
* UPDATE: Fixed upgrade routine

= 0.7.0 =
* FIX: Fixed PHP 7.2 compatibility
* FIX: Fixed lots of issues with undeclared variables
* UPDATE: Removed template editor
* UPDATE: Removed installation prompt/template

= 0.6.1 =
* UPDATE: Moved Hound version to a constant
* UPDATE: Code quality changes

= 0.6.0 =
* FIX: Removed/combined unused function
* FIX: Consolidated parameter extraction
* UPDATE: Added plugin header

= 0.5.1 =
* FEATURE: Added hook/listener system
* UPDATE: Removed old, non-functional plugin system

= 0.5.0 =
* PERFORMANCE: Removed meta title
* PERFORMANCE: Removed meta description
* PERFORMANCE: Removed meta keywords
* PERFORMANCE: Removed featured image
* SECURITY: Removed unused files from Grey theme
* UPDATE: Updated media library popup
* UPDATE: Update TinyMCE to 4.6.6 (from 4.5.4)

= 0.4.2 =
* UPDATE: Updated media library code

= 0.4.1 =
* UPDATE: Added user agent to cURL call for update checking

= 0.4.0 =
* UPDATE: Removed last accessed location as the API has a small quota
* UPDATE: Changed the way the update check is performed to allow for more server configurations

= 0.3.1 =
* FIX: Fixed post/page preview link
* UPDATE: UI tweaks for content section

= 0.3.0 =
* FIX: Fixed site title in default template header
* FIX: Removed strict types declaration for PHP versions lower than 7
* FIX: Fixed missing styles for several admin pages
* FIX: Fixed sidebar width
* UPDATE: Merged pages.php and posts.php into content.php and used URI arguments
* UPDATE: Merged new-page.php and new-post.php into new.php and used URI arguments

= 0.2.4 =
* FIX: Fixed template editor to not append .tpl at the end of saved files
* FIX: Removed unused styles and compressed stylesheets
* FIX: Fixed a fatal error with the theme editor
* FIX: Fixed TinyMCE editor
* FIX: Fixed logout function not working properly
* UPDATE: Removed Ace editor
* UPDATE: Allowed removal of all files before applying automatic updates
* FEATURE: Added "last login" functionality
* TODO: Document all functions
* TODO: Clean up all files
* TODO: Clean up gallery
* TODO: Add Headline as the second official theme
* TODO: Figure out how plugins work (and then the world opens up)

= 0.2.3 =
* FEATURE: Added CPU load in Dashboard

= 0.2.2 =
* FIX: Fixed a fatal error

= 0.2.1 =
* FEATURE: Added a welcome screen for fresh installations or for no index page found
* REFACTORING: Removed /libs/houndAdmin.php
* REFACTORING: Code cleanup

= 0.2.0 =
* FIX: Removed duplicate license
* UPDATE: Added backups to "At a Glance" section
* UPDATE: Added media items to "At a Glance" section
* UPDATE: Admin UI tweaks
* UPDATE: Added more server software checks to dashboard
* FEATURE: Added automatic updates and notifications

= 0.1.8 =
* FEATURE: Added automatic updates and notifications

= 0.1.5 =
* FIX: Fixed URL parameters not being allowed
* UPDATE: Admin UI tweaks
* FEATURE: Added backup functionality

= 0.1.4 =
* UPDATE: Added CHANGELOG.md
* UPDATE: Refactored system requirements check
* UPDATE: Cleaned up the configuration page
* UI: Updated interface for consistency
* UI: Updated the pages section UI

= 0.1.3 =
* UPDATE: Added default theme (Grey)
* UPDATE: Admin UI tweaks
* UPDATE: New PHP template tags
* FEATURE: Added search and replace feature to assist with migrations
