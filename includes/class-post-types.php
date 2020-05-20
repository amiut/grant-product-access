<?php
/**
 * Define post types and things related to them
 *
 * @package DW_GACCESS
 * @since   1.0
 */

namespace DW_GACCESS;

defined('ABSPATH') || exit;

/**
 * DW_GACCESS main class
 */
class Post_Types
{
    public static function init()
    {
        add_action('init', [__CLASS__, 'post_types']);
        add_action('init', [__CLASS__, 'taxonomies']);

        /**
         * Metaboxes
         */
        add_action('add_meta_boxes', [__CLASS__, 'metaboxes']);

        /**
         * Save Data
         *
         */
        add_action('save_post', [__CLASS__, 'save_data']);
    }

    public static function manage_columns($post_type, $callback = '', $priority = 10, $params = 1) {
        add_filter("manage_{$post_type}_posts_columns", $callback, $priority, $params);
    }

    public static function post_types()
    {

    }

    public static function taxonomies()
    {

    }

    /**
     * Register Metaboxes
     */
    public static function metaboxes()
    {

    }
}
