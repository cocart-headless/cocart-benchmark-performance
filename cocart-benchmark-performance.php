<?php
/**
 * Plugin Name: CoCart Benchmark Performance
 * Plugin URI:  https://cocartapi.com
 * Description: Only whitelisted plugins will load when requesting CoCart to improve performance.
 * Author:      CoCart Headless, LLC
 * Author URI:  https://cocartapi.com
 * Version:     0.0.2
 */

add_filter( 'option_active_plugins', 'cocart_benchmark_performance' );

function cocart_benchmark_performance( $plugins ) {
	/**
	 * Disable cronjobs for this request.
	 */
	if ( ! defined( 'DISABLE_WP_CRON' ) ) {
		define( 'DISABLE_WP_CRON', true );
	}

	/**
	 * If we're not performing a REST API request, return early.
	 */
	if ( ! defined( 'REST_REQUEST' ) ) {
		return $plugins;
	}

	/**
	 * If we are not requesting CoCart, return early.
	 */
	$request_uri = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
	$rest_prefix = trailingslashit( rest_get_url_prefix() );
	if ( strpos( $request_uri, $rest_prefix . 'cocart/' ) === false ) {
		return $plugins;
	}

	/**
	 * The list of plugins to allow.
	 */
	$accepted_plugins = array_flip(
		array(
			'cart-rest-api-for-woocommerce/cart-rest-api-for-woocommerce.php',
			'woocommerce/woocommerce.php',
			'flexible-shipping-ups/flexible-shipping-ups.php',
			'advanced-custom-fields/acf.php',
			'advanced-custom-fields-pro/acf.php',
			'amazon-s3-and-cloudfront/wordpress-s3.php',
			'rest-api-head-tags/plugin.php',
			'sg-cachepress/sg-cachepress.php',
			'wordpress-seo/wp-seo.php',
			'wp-rest-api-cache/class-wp-rest-cache.php',
			'wp-rest-cache/wp-rest-cache.php'
		)
	);

	/**
	 * Use this filter to allow other plugins to load.
	 *
	 * @param array $accepted_plugins
	 */
	$accepted_plugins = apply_filters( 'cocart_benchmark_whitelist_plugins', $accepted_plugins );

	/**
	 * The list of plugins blacklisted from loading.
	 * Even if whitelisted via the filter `cocart_benchmark_whitelist_plugins` it will be ignored.
	 */
	$blacklisted_plugins = array_flip(
		array(
			'elementor/elementor.php'
		)
	);

	/**
	 * Loop through the active plugins, if the plugin is whitelisted or is 
	 * either a CoCart or WooCommerce extension, allow the plugin to be loaded.
	 * Otherwise, remove it from the list of plugins to load to improve performance.
	 */
	foreach ( $plugins as $key => $plugin ) {
		if (
			false !== strpos( $plugin, 'cocart-' ) || 
			false !== strpos( $plugin, 'woocommerce-' ) || 
			isset( $accepted_plugins[ $plugin ] )
		) {
			/**
			 * Even if plugin is accepted, if it's blacklisted then defiantly remove the plugin.
			 */
			if ( isset( $blacklisted_plugins[ $plugin ] ) ) {
				unset( $plugins[ $key ] );
			}

			continue;
		}

		/**
		 * Defiantly remove the plugin if it's been blacklisted.
		 */
		if ( isset( $blacklisted_plugins[ $plugin ] ) ) {
			unset( $plugins[ $key ] );
		} else {
			unset( $plugins[ $key ] );
		}
	}

	return $plugins;
}
