<?php

/*
  Plugin Name:          Integrate Ninja Forms + Polylang
  Plugin URI:           https://github.com/alexist-ong/ninja-forms-polylang
  Description:          Add form titles, descriptions, field labels, etc, to Polylang string translations
  Version:              3.0
  Author:               Alexist Ong
  Author URI:           https://github.com/alexist-ong
  License:              GPL2
  License URI:          https://www.gnu.org/licenses/gpl-2.0.html
  GitHub Plugin URI:    https://github.com/alexist-ong/ninja-forms-polylang
 */

if (!defined('ABSPATH'))
    exit;

if (!class_exists('NF_PLL_Initialize')) :

    include 'class_NF_PLL.php';

    class NF_PLL_Initialize {

        public static function register_strings() {
            $nf_pll = new NF_PLL();
            $nf_pll->register_strings();
        }

        public static function translate_form_strings($form_settings, $form_id) {
            $nf_pll = new NF_PLL();
            return $nf_pll->translate_form_strings($form_settings, $form_id);
        }

        public static function translate_field_strings($field) {
            $nf_pll = new NF_PLL();
            return $nf_pll->translate_field_strings($field);
        }

        public static function translate_action_strings($actions, $form_data) {
            $nf_pll = new NF_PLL();
            return $nf_pll->translate_action_strings($actions, $form_data);
        }

    }

    add_action('init', array('NF_PLL_Initialize', 'register_strings'));
    add_filter('ninja_forms_display_form_settings', array('NF_PLL_Initialize', 'translate_form_strings'), 10, 2);
    add_filter('ninja_forms_localize_fields', array('NF_PLL_Initialize', 'translate_field_strings'), 10);
    add_filter('ninja_forms_submission_actions', array('NF_PLL_Initialize', 'translate_action_strings'), 10, 2);

endif;