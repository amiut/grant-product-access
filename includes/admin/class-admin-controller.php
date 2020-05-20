<?php
/**
 * Plati integration Admin Constroller class
 *
 * @author Dornaweb.com
 * @package Plati Integration Plugin
 * @since 1.0
 */

namespace DW_GACCESS\Admin;

class Admin_Controller
{
    public static function init() {
        add_action('admin_menu', [__CLASS__, 'admin_menus'], 99);
        add_action('admin_init', [__CLASS__, 'register_settings'], 99);
        add_action('admin_enqueue_scripts', [__CLASS__, 'assets']);
        add_action( 'wp_ajax_dwgaccess_grant_access', [__CLASS__, 'grant_access'] );
    }

    public static function assets($hook) {
        wp_register_script('dwgaccess', dw_gaccess()->plugin_url() . '/assets/js/wcgaccess.js', ['jquery'], null, true );
        wp_localize_script('dwgaccess', 'dwgaccess', [
            'ajaxurl' => admin_url('admin-ajax.php')
        ]);
        wp_enqueue_script('dwgaccess');
    }

    public static function admin_menus() {
        $hook = add_submenu_page(
            'woocommerce',
            __('اعطای دسترسی محصول', 'dw-gaccess'),
            __('اعطای دسترسی محصول', 'dw-gaccess'),
            'manage_options',
            'dw=gaccess',
            [__CLASS__, 'settings_page_content']
        );
    }

    public static function settings_page_content() {
        include DW_GACCESS_ABSPATH . '/templates/admin/settings.php';
    }

    public static function register_settings() {

    }

    public static function grant_access() {
        header('Content-type: application/json; charset=utf-8');
        // header('Connection: keep-alive');
        set_time_limit(0);
        ob_implicit_flush(true);
        ob_end_flush();

        $email = ! empty($_POST['email']) && is_email($_POST['email']) ? sanitize_email($_POST['email']) : false;
        $product_id = ! empty($_POST['product']) ? absint($_POST['product']) : (int) get_option('options_main_product');

        if (! $product_id) {
            wp_send_json_error([
                'message' => 'محصول انتخاب نشده'
            ]);
        }

        if (! $email) {
            wp_send_json_error([
                'message' => 'ایمیل را وارد کنید'
            ]);
        }

        if (! current_user_can('administrator') || ! wp_verify_nonce($_POST['_dwnonce'], 'dwgaccess_nonce_grant')) {
            wp_send_json_error([
                'message' => 'شما دسترسی کافی ندارید'
            ]);
        }


        $existing_user = get_user_by('email', $email);

        $user = false;

        if ($existing_user && ! is_WP_Error($existing_user)) {
            $user = $existing_user;

            if (! user_can($user, 'customer')) {
                $user->add_role('customer');
            }

        } else {
            $user_id = wc_create_new_customer($email);

            if ($user_id) {
                $user = get_user_by('id', $user_id);

            } else {
                return;
            }

        }

        // Check if customer already bought the product
        if (wc_customer_bought_product($user->user_email, $user->ID, $product_id)) {
            wp_send_json_success([
                'message' => "کاربر از قبل این محصول را خریده است"
            ]);

        } else {
            // Make order
            $order = wc_create_order([
                'customer_id' => $user->ID,
            ]);

            $order->add_product(wc_get_product($product_id), 1);
            $order->calculate_totals();
            $order->update_status( 'completed', 'Order created dynamically - ', true);

            wp_send_json_success([
                'message' => "کاربر با موفقیت ایجاد و دسترسی محصول اعطا شد"
            ]);
        }

        wp_die();
    }
}

function dw_delayed_message($str) {
	echo str_replace(['[Good]', '[Success]'], ['<strong style="color: #0f0;">[Good]</strong>', '<strong style="color: #0f0;">[Success]</strong>'], $str) . '<br>';
	usleep(400000);
	ob_flush();
    flush();
}
