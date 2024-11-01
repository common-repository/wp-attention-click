<?php
PF::addSection('general-fields', array(
    'parent' => WPAC_ID . '-setting',
    'title'  => 'Bar',
    'icon'   => 'fa fa-minus',
    'fields' => array(
        array(
			'id'      => 'wpac_bar_activate',
			'type'    => 'checkbox',
			'title'   => 'Activete Bar',
			//'label'   => '💡 Activate.',
            //'default' => true,
            'inline'  => true,
            'options' => array(
				'activate'  => '💡 Activate',
				'mode-test' => 'Mode Test',
            ),
            'default' => array( 'activate' ),
        ),
        array(
			'type'       => 'notice',
			'style'      => 'warning',
			'content'    => 'This will make the attention appear every time you refresh the page <i>(for the tests)</i>',
			'dependency' => array( 'wpac_activate_plugin', 'any', 'mode-test' ),
        ),
        array(
            'id'          => 'direction',
            'type'        => 'select',
            'title'       => 'Direction',
            'options'     => array(
                'top'    => '↑ Top',
                'bottom' => '↓ Bottom',
            ),
            'desc'    => 'Choose the address where the bar appears',
            'default' => 'top'
        ),
        array(
            'id'     => 'wpac_fieldset_text',
            'type'   => 'fieldset',
            'title'  => '',
            'fields' => array(
                array(
                    'type'     => 'subheading',
                    'title'    => 'Bar customizer',
                    'subtitle' => 'Options to customize the bar to get attention'
                ),
                array(
                    'id'            => 'editor',
                    'type'          => 'wp_editor',
                    'title'         => 'Message you want to refer',
                    'subtitle'      => 'You can add a small photo, spaces, colors, and fonts.',
                    'height'        => '50px',
                    'media_buttons' => false,
                ),
                array(
                    'id'    => 'color-bg',
                    'title' => 'Background color',
                    'type'  => 'color',
                ),
                array(
                    'id'      => 'color-text',
                    'title'   => 'Text color',
                    'type'    => 'color',
                ),
                array(
                    'id'                => 'box-padding-top-bottom',
                    'type'              => 'dimensions',
                    'title'             => 'Padding up and/down',
                    'desc'              => 'Internal padding of the message box',
                    'height'            => false,
                    'units'             => array( 'px' ),
                    'width_icon'        => '<i class="fa fa-arrows-v" aria-hidden="true"></i>',
                    'width_placeholder' => 'number',
                    'attribute'         => array( 'type' => 'number' ),
                ),
            ),
            'default' => array(
                'editor'                 => 'Hello  🔊, we are also on Instagram for more  😜  <strong>promotion</strong>&nbsp;or&nbsp;<strong>publication</strong>.. {{button}}',
                'color-bg'               => '#2B2B2B',
                'color-text'             => '#fff',
                'box-padding-top-bottom' => array('width'=>'10'),
            )
        ),
        array(
            'id'     => 'wpac_fieldset_button',
            'type'   => 'fieldset',
            'title'  => '',
            'fields' => array(
                array(
                    'type'     => 'subheading',
                    'title'    => 'Button Custom (Optional)',
                    'subtitle' => 'If you want to show the custom action button you must put this code in the editor <code>{{button}}</code>'
                ),
                array(
                    'id'    => 'button-bg',
                    'title' => 'Background color',
                    'type'  => 'color',
                ),
                array(
                    'id'    => 'button-color',
                    'title' => 'Text color',
                    'type'  => 'color',
                ),
                array(
                    'id'          => 'button-text',
                    'title'       => 'Text of the button',
                    'type'        => 'text',
                    'placeholder' => 'Accept',
                ),
            ),
            'default' => array(
                'button-bg'    => '#FA3292',
                'button-color' => '#fff',
                'button-text'  => 'Accept',
            )
        ),
        array(
            'id'     => 'wpac_fieldset_action',
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
                    'id'          => 'time_appear',
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
                    'desc' => 'Time it takes for the bar to appear <i>(in seconds)</i>',
                ),
                array(
                    'id'          => 'time_appear_againt',
                    'type'        => 'select',
                    'title'       => 'Show again',
                    'options'     => wpac_generate_range(),
                    'desc' => 'Time it returns to appear <i>(in days)</i>',
                ),
                array(
                    'id'    => 'click_bar',
                    'type'  => 'checkbox',
                    'title' => 'Text clickeable',
                    'desc'  => '<strong>Active: </strong> The text of the bar will become a link.
<br /><strong>Inactive: </strong>The text of the bar is not clickable, but you can use the <i>button</i> as a clickable item.</stron>',
                    'label'   => '🖱 Activate.',
                ),
                array(
                    'id'      => 'click_target',
                    'type'    => 'checkbox',
                    'title'   => 'Open link in a new page',
                    'desc'    => 'Open the link on a new page, if this option is not activated then open it on the same page.',
                    'label'   => '&#8599; <code>target</code>',
                ),
                array(
                    'id'          => 'type_action',
                    'type'        => 'select',
                    'title'       => 'Action',
                    'options'     => array(
                        'r-url'      => 'Redirection to URL',
                        'r-whatsapp' => 'Redirection to Whatsapp',
                    ),
                    'desc' => '<strong>Url: </strong>By clicking on the action bar or button this redirects to a customizable URL here.
<br /><strong>Whatsapp: </strong>Redirection to the WhatsApp application'
                ),
                array(
                    'id'          => 'redirection_url',
                    'title'       => 'Url',
                    'type'        => 'text',
                    'placeholder' => 'https://google.com/........',
                    //'validate'    => 'pf_validate_url',
                    'subtitle'   => 'Enter the URL of the button when clicking',
                    'dependency' => array( 'type_action', '==', 'r-url' ),
                    'attributes' => array(
                        'style'    => 'width: 100%; height: 40px; border-color: #93C054;'
                    ),
                ),
                array(
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
                ),
            ),
            'default' => array(
                'time_appear'                 => '4',
                'click_bar'                   => true,
                'click_target'                => true,
                'type_action'                 => 'r-url',
                'number_whatsapp'             => '',
                'number_whatsapp_aditional'   => 'send_title_link',
                'number_whatsapp_custom_text' => 'Hi, I want you to see this ..',
                'redirection_url'             => 'http://instagram.com',
                'time_appear'                 => '3',
                'time_appear_againt'          => '3',
            )
        ),
        array(
            'id'     => 'wpac_fieldset_advanced',
            'type'   => 'fieldset',
            'title'  => '',
            'fields' => array(
                array(
                    'type'     => 'subheading',
                    'title'    => 'Advanced',
                    'subtitle' => 'Advanced customization options'
                ),
                array(
                    'id'         => 'countries_include',
                    'type'       => 'select',
                    'title'      => 'Show in country',
                    'chosen'     => true,
                    'multiple'   => true,
                    'placeholder'=> 'Select one or more countries',
                    'desc'       => 'Select the countries in which you want to display<br />
If the option is "All" or empty, then it will be shown in all countries',
                    'options'    => $array_countries,
                ),
            ),
            'default' => array( 'countries_include' => 'all' )
        )
    ),
));