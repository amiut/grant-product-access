<?php
/**
 * Useful functions
 *
 */

/**
 * Better var_dump for inspecting variables
 */
if (! function_exists('dumpit')) {
    function dumpit($var) {
        echo '<pre style="text-align: left; direction: ltr;">';
        var_dump($var);
        echo '</pre>';
    }
}

/**
 * Get Client IP Address
 */
if (! function_exists('dw_get_client_ip_address')) {
    function dw_get_client_ip_address() {
        if (! empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];

        } elseif(! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR']; // Ip set by a proxy

        } else {
            return $_SERVER['REMOTE_ADDR'];

        }
    }
}
