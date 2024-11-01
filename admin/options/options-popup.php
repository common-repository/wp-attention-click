<?php
PF::addSection('setting-popup', array(
    'parent' => WPAC_ID . '-setting',
    'title'  => 'Popup',
    'icon'   => 'fa fa-square',
    'fields' => array(
        array(
            'id'      => 'wpac_popup_activate',
            'type'    => 'checkbox',
            'title'   => 'Activete Popup',
            'inline'  => true,
            'options' => array(
                'activate'  => '💡 Activate',
                'mode-test' => 'Mode Test',
            ),
            'default' => array(),
        ),
        array(
            'type'       => 'notice',
            'style'      => 'warning',
            'content'    => 'This will make the attention appear every time you refresh the page <i>(for the tests)</i>',
            'dependency' => array( 'wpac_activate_plugin', 'any', 'mode-test' ),
        ),
        array(
            'id'     => 'wpac_popup_fieldset_content',
            'type'   => 'fieldset',
            'title'  => '',
            'fields' => array(
                array(
                    'type'     => 'subheading',
                    'title'    => 'Popup customizer',
                    'subtitle' => 'Options to customize the popup to get attention'
                ),
                array(
                    'id'            => 'popup_editor',
                    'type'          => 'wp_editor',
                    'title'         => 'Content of the popup',
                    'subtitle'      => 'You can add a small photo, spaces, colors, and fonts.',
                    'height'        => '250px',
                    'media_buttons' => true,
                ),
                array(
                    'id'      => 'popup_background',
                    'type'    => 'background',
                    'title'   => 'Background',
                ),
                array(
                    'id'      => 'popup_color_text',
                    'title'   => 'Text color',
                    'type'    => 'color',
                ),
                array(
                    'id'                => 'popup_padding_top_bottom',
                    'type'              => 'dimensions',
                    'title'             => 'Padding',
                    'desc'              => 'Internal padding the popup',
                    //'height'            => false,
                    'units'             => array( 'px' ),
                    //'width_icon'        => '<i class="fa fa-arrows-v" aria-hidden="true"></i>',
                    'width_placeholder'  => 'Top/Bottom',
                    'height_placeholder' => 'Left/Right',
                    'attribute'          => array( 'type' => 'number' ),
                ),
            ),
            'default' => array(
                'popup_editor' => '<img width="165" height="164"  src="'.WPAC_URL . 'admin/assets/images/logo-wp.png"  class="size-thumbnail alignleft" />'
                ."Hello, this week we have a promotion for you, sure you will be interested \n{{button}}",
                'popup_background' => array(
                    'background-color'      => '#333',
                    'background-position'   => 'center center',
                    'background-repeat'     => 'no-repeat',
                    'background-attachment' => '',
                    'background-size'       => '',
                ),
                'popup_color_text' => '#fff',
                'popup_padding_top_bottom' => array('width'=>'15', 'height' => '15'),
            )
        ),
        array(
            'id'     => 'wpac_fieldset_popup_button',
            'type'   => 'fieldset',
            'title'  => '',
            'fields' => array(
                array(
                    'type'     => 'subheading',
                    'title'    => 'Button Custom (Optional)',
                    'subtitle' => 'If you want to show the custom action button you must put this code in the editor <code>{{button}}</code>'
                ),
                array(
                    'id'    => 'popup_button_bg',
                    'title' => 'Background color',
                    'type'  => 'color',
                ),
                array(
                    'id'    => 'popup_button_color',
                    'title' => 'Text color',
                    'type'  => 'color',
                ),
                array(
                    'id'          => 'popup_button_text',
                    'title'       => 'Text of the button',
                    'type'        => 'text',
                    'placeholder' => 'Accept',
                ),
            ),
            'default' => array(
                'popup_button_bg'    => '#FA3292',
                'popup_button_color' => '#fff',
                'popup_button_text'  => 'See',
            )
        ),
        array(
            'id'     => 'wpac_popup_fieldset_action',
            'type'   => 'fieldset',
            'title'  => '',
            'fields' => array(
                array(
                    'type'     => 'subheading',
                    'class'    => 'wpac_fieldset_action_header',
                    'title'    => 'Action onClick',
                    'subtitle' => 'Event configuration click to get attention'
                ),
                array(
                    'id'          => 'popup_time_appear',
                    'type'        => 'select',
                    'title'       => 'Time to appear',
                    'options'     => array(
                        '2' => 2,
                        '3' => 3,
                        '4' => 4,
                        '5' => 5,
                        '6' => 6,
                        '7' => 7,
                    ),
                    'desc' => 'Time it takes for the popup to appear <i>(in seconds)</i>',
                ),
                array(
                    'id'          => 'popup_time_appear_againt',
                    'type'        => 'select',
                    'title'       => 'Show again',
                    'options'     => wpac_generate_range(),
                    'desc' => 'Time it returns to appear <i>(in days)</i>',
                ),
                /*array(
                    'id'    => 'popup_click',
                    'type'  => 'checkbox',
                    'title' => 'Content clickeable',
                    'desc'  => '<strong>Active: </strong> The content of the popup will become a link.
<br /><strong>Inactive: </strong>The content of the popup is not clickable, but you can use the <i>button</i> as a clickable item.</stron>',
                    'label'   => '🖱 Activate.',
                ),*/
                array(
                    'id'      => 'popup_click_target',
                    'type'    => 'checkbox',
                    'title'   => 'Open link in a new page',
                    'desc'    => 'Open the link on a new page, if this option is not activated then open it on the same page',
                    'label'   => '&#8599; <code>target</code>',
                ),
                /*array(
                    'id'          => 'popup_type_action',
                    'type'        => 'select',
                    'title'       => 'Action',
                    'options'     => array(
                        'r-url'      => 'Redirection to URL',
                        'r-whatsapp' => 'Redirection to Whatsapp',
                    ),
                    'desc' => '<strong>Url: </strong>By clicking on the action box or button this redirects to a customizable URL here.
<br /><strong>Whatsapp: </strong>Redireccion a la aplicacion Whatsapp'
                ),*/
                array(
                    'id'          => 'popup_redirection_url',
                    'title'       => 'Url',
                    'type'        => 'text',
                    'placeholder' => 'https://google.com/........',
                    'attributes' => array(
                        'style'    => 'width: 100%; height: 40px; border-color: #93C054;'
                    ),
                    'subtitle'   => 'Enter the URL of the button when clicking',
                    //'validate'    => 'pf_validate_url',
                    //'dependency' => array( 'type_action', '==', 'r-url' ),
                ),
                /*array(
                    'id'          => 'number_whatsapp',
                    'title'       => 'Number Whatsapp',
                    'desc'        => 'Enter the number of whatsapp that will be redirected',
                    'type'        => 'text',
                    'sanitize'    => 'pf_onlynumber',
                    'placeholder' => '+12224445555',
                    'dependency'  => array( 'type_action', '==', 'r-whatsapp' ),
                ),
                array(
                    'id'          => 'number_whatsapp_aditional',
                    'type'        => 'select',
                    'title'       => 'Send to Whatsapp',
                    'options'     => array(
                        'send_title_link'  => 'Title of the current post + link',
                        'send_custom_text' => 'Welcome message',
                    ),
                    'dependency' => array( 'type_action', '==', 'r-whatsapp' ),
                ),
                array(
                    'id'          => 'number_whatsapp_custom_text',
                    'type'        => 'textarea',
                    'title'       => 'Enter a message that is sent to whatsapp',
                    'placeholder' => 'Hello, thanks for writing me, leave your name to add ...',
                    'dependency'  => array( 'number_whatsapp_aditional|type_action', '==|==', 'send_custom_text|r-whatsapp' ),
                ),*/
            ),
            'default' => array(
                'popup_time_appear'        => '4',
                'popup_time_appear_againt' => '3',
                'popup_click'              => false,
                'popup_click_target'       => true,
                'popup_redirection_url'    => 'https://google.com',

                /*'type_action'                 => 'r-url',
                'number_whatsapp'             => '',
                'number_whatsapp_aditional'   => 'send_title_link',
                'number_whatsapp_custom_text' => 'Hi, I want you to see this ..',*/
                //'time_appear'                 => '3',
            )
        ),
        array(
            'id'     => 'wpac_popup_fieldset_advanced',
            'type'   => 'fieldset',
            'title'  => '',
            'fields' => array(
                array(
                    'type'     => 'subheading',
                    'title'    => 'Advanced',
                    'subtitle' => 'Advanced customization options'
                ),
                array(
                    'id'         => 'popup_countries_include',
                    'type'       => 'select',
                    'title'      => 'Show in country',
                    'chosen'     => true,
                    'multiple'   => true,
                    'placeholder'=> 'Select one or more countries',
                    'desc'       => 'Select the countries in which you want to display<br />
If the option is "All" or empty then it will be shown in all countries',
                    'options'    => $array_countries,
                ),
            ),
            'default' => array( 'popup_countries_include' => 'all' )
        )
    )
) );