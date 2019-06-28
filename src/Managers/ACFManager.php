<?php
/**
 * Manages ACF custom fields via PHP. The main purpose is to include
 * fields for Skela's included custom Gutenberg blocks, without needing
 * those to appear in the admin panel.
 *
 * This file should only be called if ACF is enabled.
 */
namespace Skela\Managers;

class ACFManager
{

    /**
     * Runs initialization tasks.
     *
     * @return void
     */
    public function run()
    {
        add_action('init', [$this, 'imageLayoutFields'], 1);
        add_action('init', [$this, 'relatedArticleFields'], 1);
    }


    /**
     * Register image layout fields
     *
     * @return void
     */
    public function imageLayoutFields()
    {
        acf_add_local_field_group(
            array(
            'key' => 'group_5cdef47b943c9',
            'title' => 'Image Layout',
            'fields' => array(
                array(
                    'key' => 'field_5cdef487a8fcc',
                    'label' => 'Images',
                    'name' => 'imageLayoutImages',
                    'type' => 'gallery',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'min' => '2',
                    'max' => '3',
                    'insert' => 'append',
                    'library' => 'all',
                    'min_width' => '',
                    'min_height' => '',
                    'min_size' => '',
                    'max_width' => '',
                    'max_height' => '',
                    'max_size' => '',
                    'mime_types' => '',
                    'return_format' => 'array',
                    'preview_size' => 'medium',
                ),
                array(
                    'key' => 'field_5cdef49aa8fcd',
                    'label' => 'Layout',
                    'name' => 'imageLayoutLayout',
                    'type' => 'radio',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        '2-symmetrical' => '2 Symmetrical',
                        '2-asymmetrical' => '2 Asymmetrical',
                        '3-symmetrical' => '3 Symmetrical',
                        '3-asymmetrical' => '3 Asymmetrical',
                    ),
                    'allow_null' => 0,
                    'other_choice' => 0,
                    'default_value' => '',
                    'layout' => 'horizontal',
                    'return_format' => 'value',
                    'save_other_choice' => 0,
                ),
                array(
                    'key' => 'field_5cdeffb837320',
                    'label' => 'Crop image to same aspect ratio?',
                    'name' => 'imageLayoutCrop',
                    'type' => 'true_false',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'default_value' => 0,
                    'ui' => 1,
                    'ui_on_text' => '',
                    'ui_off_text' => '',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'block',
                        'operator' => '==',
                        'value' => 'acf/imagelayout',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            )
        );
    }

    /**
     * Register related article fields
     *
     * @return void
     */
    public function relatedArticleFields()
    {
        acf_add_local_field_group(
            array(
            'key' => 'group_5cd5a5077e357',
            'title' => 'Related Articles',
            'fields' => array(
                array(
                    'key' => 'field_5cd5a5401a9eb',
                    'label' => 'Header text',
                    'name' => 'header_text',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => 'Related Articles',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                array(
                    'key' => 'field_5cd5a50d1a9e9',
                    'label' => 'Chosen articles',
                    'name' => 'chosen_articles',
                    'type' => 'repeater',
                    'instructions' => '',
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'collapsed' => '',
                    'min' => 1,
                    'max' => 4,
                    'layout' => 'table',
                    'button_label' => 'Add an article',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_5cd5c5733d782',
                            'label' => 'Related Article',
                            'name' => 'related_article',
                            'type' => 'post_object',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'post_type' => array(
                                0 => 'post',
                            ),
                            'taxonomy' => '',
                            'allow_null' => 0,
                            'multiple' => 0,
                            'return_format' => 'object',
                            'ui' => 1,
                        ),
                    ),
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'block',
                        'operator' => '==',
                        'value' => 'acf/relatedarticles',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            )
        );
    }
}
