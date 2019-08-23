<?php

if (!class_exists('NF_PLL')) :

    class NF_PLL {

        private $form_whitelist;
        private $field_whitelist;
        private $action_whitelist;

        public function __construct() {
            $this->form_whitelist = array(
                'title',
                'form_title',
                'unique_field_error',
                'sub_limit_msg',
                'not_logged_in_msg',
                'changeEmailErrorMsg',
                'changeDateErrorMsg',
                'confirmFieldErrorMsg',
                'fieldNumberNumMinError',
                'fieldNumberNumMaxError',
                'fieldNumberIncrementBy',
                'formErrorsCorrectErrors',
                'validateRequiredField',
                'honeypotHoneypotError'
            );

            $this->field_whitelist = array(
                'label',
                'processing_label',
                'placeholder',
                'default',
                'help_text',
                'desc_text'
            );

            $this->action_whitelist = array(
                'email_subject',
                'email_message',
                'email_message_plain',
                'success_msg'
            );
        }

        public function register_strings() {
            if (!class_exists('Ninja_Forms') || !function_exists('pll_register_string')) {
                return;
            }

            $forms = Ninja_Forms()->form()->get_forms();
            foreach ($forms as $form) {
                $group = "Ninja Form #{$form->get_id()}: {$form->get_setting('title')}";

                //process form settings
                $form_settings = $form->get_settings();
                foreach ($form_settings as $form_key => $form_value) {
                    if (in_array($form_key, $this->form_whitelist) && is_string($form_value)) {
                        pll_register_string('Form: ' . $form_key, $form_value, $group);
                    }
                }

                //process fields
                $fields = Ninja_Forms()->form($form->get_id())->get_fields();
                foreach ($fields as $field) {
                    $field_settings = $field->get_settings();

                    foreach ($field_settings as $field_key => $field_value) {
                        if (in_array($field_key, $this->field_whitelist) && is_string($field_value)) {
                            pll_register_string('Field: ' . $field_key, $field_value, $group);
                        }
                    }                    
                    
                    //field options
                    if ($field_settings['type'] == 'listcheckbox' || $field_settings['type'] == 'listselect' || $field_settings['type'] == 'listradio') {
                        $options = $field_settings['options'];
                        
                        if (!empty($options)) {
                            foreach ($options as $option) {
                                if (isset($option['label'])) {
                                    $option_label = $option['label'];

                                    if (!empty($option_label)) {
                                        pll_register_string('Field: ' . $field_settings['type'], $option_label, $group);
                                    }                            
                                }
                            }
                        }                        
                    }
                }

                //process actions
                $actions = Ninja_Forms()->form($form->get_id())->get_actions();
                foreach ($actions as $action) {
                    $action_settings = $action->get_settings();

                    foreach ($action_settings as $action_key => $action_value) {
                        if (in_array($action_key, $this->action_whitelist) && is_string($action_value)) {
                            pll_register_string('Action: ' . $action_key, $action_value, $group);
                        }
                    }
                }
            }
        }

        public function translate_form_strings($form_settings, $form_id) {
            if (!class_exists('Ninja_Forms') || !function_exists('pll__')) {
                return $form_settings;
            }

            foreach ($form_settings as $form_key => $form_value) {
                if (in_array($form_key, $this->form_whitelist) && is_string($form_value)) {
                    $form_settings[$form_key] = pll__($form_value);
                }
            }
            return $form_settings;
        }

        public function translate_field_strings($field) {
            if (!class_exists('Ninja_Forms') || !function_exists('pll__')) {
                return $field;
            }

            $field_settings = $field['settings'];
            foreach ($field_settings as $field_key => $field_value) {
                if (in_array($field_key, $this->field_whitelist) && is_string($field_value)) {
                    $field_settings[$field_key] = pll__($field_value);
                }
            }
            
            //field options
            if ($field_settings['type'] == 'listcheckbox' || $field_settings['type'] == 'listselect' || $field_settings['type'] == 'listradio') {
                $options = $field_settings['options'];

                if (!empty($options)) {
                    $translated_options = array();
                    foreach ($options as $option) {
                        if (isset($option['label'])) {
                            $option_label = $option['label'];

                            if (!empty($option_label)) {
                                $option['label'] = pll__($option_label);
                            }                            
                        }
                        
                        $translated_options[] = $option;
                    }
                    
                    $field_settings['options'] = $translated_options;
                }                 
            }

            $field['settings'] = $field_settings;            
            return $field;
        }

        public function translate_action_strings($actions, $form_data) {
            if (!class_exists('Ninja_Forms') || !function_exists('pll__')) {
                return $actions;
            }

            $translated_actions = array();
            foreach ($actions as $action) {
                $action_settings = $action['settings'];
                foreach ($action_settings as $action_key => $action_value) {
                    if (in_array($action_key, $this->action_whitelist) && is_string($action_value)) {
                        $action_settings[$action_key] = pll__($action_value);
                    }
                }

                $action['settings'] = $action_settings;
                $translated_actions[] = $action;
            }

            return $translated_actions;
        }

    }

        
endif;
