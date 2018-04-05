=== Advanced Woo Search ===
Contributors: Mihail Barinov
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=GSE37FC4Y7CEY
Tags: widget, plugin, woocommerce, search, product search, woocommerce search, ajax search, live search, custom search, ajax, shortcode, better search, relevance search, relevant search, search by sku, search plugin, shop, store, wordpress search, wp ajax search, wp search, wp search plugin, sidebar, ecommerce, merketing, products, category search, instant-search, search highlight, woocommerce advanced search, woocommerce live search, WooCommerce Plugin, woocommerce product search
Requires at least: 4.0
Tested up to: 4.9.4
Stable tag: 1.38
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Advanced AJAX search plugin for WooCommerce

== Description ==

Advanced Woo Search - powerful live search plugin for WooCommerce. Just start typing and you will immediately see the products that you search

= Main Features =

* **Products search** - Search across all your WooCommerce products
* **Settings page** - User-friendly settings page with lot of options
* **Search in** - Search in product title, content, excerpt, categories, tags and sku. Or just in some of them
* **Shortcode** - Use shortcode to place search box anywhere you want
* **Product image** - Each search result contains product image
* **Product price** - Each search result contains product price
* **Terms search** - Search for product categories and tags
* **Smart ordering** - Search results ordered by the priority of source where they were found
* **Fast** - Nothing extra. Just what you need for proper work
* **WPML**, **Polylang**, **WooCommerce Multilingual**, **qTranslate** support
* Supports variable products
* Plurals support
* Google Analytics support
* Custom Product Tabs for WooCommerce plugin support
* Search Exclude plugin support

= Premium Features =

[Premium Version Demo](https://advanced-woo-search.com/)
	
* Search **results layouts**
* **Filters**. Switch between tabs to show different search results
* **Unlimited** amount of search form instances
* **Advanced settings page** with lot of options
* **Exclude** spicific products by its categories or tags from search results
* Ability to specify **source of image** for search results: featured image, gallery, product content, product short description or set default image if there is no other images
* **Visibility option** - choose what catalog visibility must be for product to displayed in search results
* Show product **categories** and **variations** in search results
* Support for [WooCommerce Brands plugin](https://woocommerce.com/products/brands/)

== Installation ==

1. Upload advanced-woo-search to the /wp-content/plugins/ directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place the plugin shortcode [aws_search_form] into your template or post-page or just use build-in widget

== Frequently Asked Questions ==

= How to insert search form? =

You can use build-in widget to place plugins search form to your sidebar.

Or just use shortcode for displaying form inside your post/page:

`[aws_search_form]`

Or insert this function inside php file ( often it used to insert form inside page templates files ):

`echo do_shortcode( '[aws_search_form]' );`

= Is this plugin compatible with latest version of Woocommerce? =

Yep. This plugin is always compatible with the latest version of Woocommerce?

== Screenshots ==

1. Front-end view
2. Plugin settings page

== Changelog ==

= 1.38 =
* Add 'Clear form' buttom for search form on mobile devices
* Fix bug with not srtiped shortcodes on product description
* Fix bug with aws_reindex_table action

= 1.37 =
* Add 'aws_indexed_content', 'aws_indexed_title', 'aws_indexed_excerpt' filters

= 1.36 =
* Update re-index function
* Add support for custom tabs content made with Custom Product Tabs for WooCommerce plugin
* Add support for Search Exclude plugin

= 1.35 =
* Fix issue with position of search results box
* Add 'aws_page_results' filter
* Add support for 'order by' box in search results page
* Fix issue fix re-index timeout

= 1.34 =
* Add arrows navigation for search results
* Fix bug with php 7+ vesion

= 1.33 =
* Fix re-index bug
* Fix bug with search page

= 1.32 =
* Fix shortcodes stripping from product content
* Fix qTranslate plugin issue with product name 
* Fix reindex issue

= 1.31 =
* Add WooCommerce version check

= 1.30 =
* Add qTranslate plugin support

= 1.29 =
* Fix bug with search results page

= 1.28 =
* Add caching table to store cached search result instead of store them in wp_options table
* Fix deprecated action 'woocommerce_variable_product_sync'

= 1.27 =
* Add option to show stock status in search results
* Add 'aws_special_chars' filter

= 1.26 =
* Add Polylang plugin support

= 1.25 =
* Add markdown support for 'Nothing found' field
* Fix WPML bug

= 1.24 =
* Add plurals support
* Fix Polylang plugin conflict
* Fix SKU search bug
* Add function for cron job

= 1.23 =
* Add 'Stop-words list' option

= 1.22 =
* Fix reindex bug
* Hide empty taxonomies from search results
* Add support for old WooCommerce versions

= 1.21 =
* Fix search page switching to degault language

= 1.20 =
* Add WPML, WooCommerce Multilingual support

= 1.19 =
* Fix bugs

= 1.18 =
* Fix bugs

= 1.17 =
* Fix layout bugs
* Fix bugs with older versions of WooCommerce
* Add Google Analytics support

= 1.16 =
* Option for 'Out of stock' products
* Fix bugs

= 1.15 =
* Exclude 'Out of stock' products from search
* Fix bugs

= 1.14 =
* Fix number of search results on search page
* Exclude draft products from search
* Fix bugs

= 1.13 =
* Add support for variable products
* Fix bugs

= 1.12 =
* Fix small bugs in search results output

= 1.11 =
* Fix issue with indexing large amount of products
* Fix bag with search page query

= 1.10 =
* Update search results page
* Fix some layout issues

= 1.09 =
* Make indexing of the products content much more fuster
* Fix several bugs

= 1.08 =
* Update check for active WooCommerce plugin
* Add hungarian translation ( big thanks to hunited! )

= 1.07 =
* Exclude hidden products from search
* Update translatable strings

= 1.06 =
* Cache search results to increase search speed

= 1.05 =
* Improve search speed

= 1.04 =
* Fix issue with SKU search
* Add option to display product SKU in search results

= 1.03 =
* Add search in product terms ( categories, tags )
* Fix issue with not saving settings

= 1.02 =
* Add single page search for 'product' custom post type
* Fix problem with dublicate products in the search results

= 1.01 =
* Fix problem with result block layout

= 1.00 =
* First Release