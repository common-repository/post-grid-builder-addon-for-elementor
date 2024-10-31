<?php
if (!defined('ABSPATH')) {
  exit;
}

$pgggo_repeatergrid->add_control(
			'image',
			[
				'label' => esc_html__( 'Placeholder Image', 'pgggo' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
        'condition' => [
          'field_type' => 'image',
        ],
			]
		);

$pgggo_repeatergrid->add_group_control(
    \Elementor\Group_Control_Image_Size::get_type(),
    [
        'name'      => 'image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
        'default'   => 'large',
        'separator' => 'none',
        'condition' => [
          'field_type' => 'image',
        ],
    ]
);

$pgggo_repeatergrid->add_control(
  'field_image_url',
  [
    'label'     => esc_html__('Image Type', 'pgggo'),
    'type'      => \Elementor\Controls_Manager::SELECT,
    'default'   => 'featured',
    'options'   => [
      'featured'          => esc_html__('Featured Images', 'pgggo'),
      'acf_image'         => esc_html__('ACF Image', 'pgggo'),
      'custom_field'      => esc_html__('Custom Field', 'pgggo'),
      'woocommerce_image' => esc_html__('WooCommerce  Product Image', 'pgggo'),
    ],
    'condition' => [
      'field_type' => 'image',
    ],
  ]
);

$pgggo_repeatergrid->add_control(
			'important_note_acf',
			[
				'label' => esc_html__( 'Important Note', 'pgggo' ),
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => esc_html__( 'The return format of ACF image must be "Image ID". Field expects attachment ID', 'pgggo' ),
				'content_classes' => 'your-class',
        'condition'   => [
          'field_type'      => 'image',
          'field_image_url' => ['acf_image', 'custom_field'],
        ],
			]
		);

$pgggo_repeatergrid->add_control(
  'field_image_acf_or_custom',
  [
    'label'     => esc_html__('ACF Field Selector', 'pgggo'),
    'type'      => \Elementor\Controls_Manager::SELECT,
    'options'   => $this->get_acf_list(),
    'condition'   => [
      'field_type'      => 'image',
      'field_image_url' => ['acf_image', 'custom_field'],
    ],
  ]
);


$pgggo_repeatergrid->start_controls_tabs(
  'coretabimageeffects'
);

$pgggo_repeatergrid->start_controls_tab(
  'pgggo_image_hover_effect_tab_normal',
  [
    'label' => esc_html__( 'Normal', 'pgggo' ),
    'condition' => [
      'field_type' => 'image',
    ],
  ]
);


$pgggo_repeatergrid->add_responsive_control(
			'pgggo_image_hover_effect_tab_normal_overlay',
			[
				'label' => esc_html__( 'Overlay Effect', 'pgggo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => [
          'pgggo-overlay-effect-1-redtint'  => esc_html__( 'Red Overlay', 'pgggo' ),
          'pgggo-overlay-effect-1-greentint'  => esc_html__( 'Green Overlay', 'pgggo' ),
          'pgggo-overlay-effect-1-bluetint'  => esc_html__( 'Blue Overlay', 'pgggo' ),
          'pgggo-overlay-effect-1-greenrosetint'  => esc_html__( 'Green Rose Overlay', 'pgggo' ),
          'pgggo-overlay-effect-1-blueredtint'  => esc_html__( 'Blue Red Overlay', 'pgggo' ),
					'' => esc_html__( 'None', 'pgggo' ),
				],
        'condition' => [
          'field_type' => 'image',
        ],
			]
);

$pgggo_repeatergrid->end_controls_tab();

$pgggo_repeatergrid->start_controls_tab(
  'pgggo_image_hover_effect_tab_hover',
  [
    'label' => esc_html__( 'Hover', 'pgggo' ),
    'condition' => [
      'field_type' => 'image',
    ],
  ]
);

$pgggo_repeatergrid->add_responsive_control(
			'pgggo_image_hover_effect_tab_hover_transition',
			[
				'label' => esc_html__( 'Transition Effect', 'pgggo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'pgggo-transition-effect-1-hover'  => esc_html__( 'Zoom In', 'pgggo' ),
          'pgggo-transition-effect-1-hover-zoomout'  => esc_html__( 'Zoom Out', 'pgggo' ),
          'pgggo-transition-effect-1-hover-cir'  => esc_html__( 'Circle', 'pgggo' ),
          'pgggo-transition-effect-1-hover-shine'  => esc_html__( 'Shine', 'pgggo' ),
          'pgggo-transition-effect-1-hover-flash'  => esc_html__( 'Flash', 'pgggo' ),
          'pgggo-transition-effect-1-hover-rotate'  => esc_html__( 'Rotate', 'pgggo' ),
          'pgggo-transition-effect-1-hover-slide'  => esc_html__( 'Slide', 'pgggo' ),
          'pgggo-transition-effect-1-hover-blur'  => esc_html__( 'Blur', 'pgggo' ),
          'pgggo-transition-effect-1-hover-grey'  => esc_html__( 'Gray Scale', 'pgggo' ),
          'pgggo-transition-effect-1-hover-sepia'  => esc_html__( 'Sepia', 'pgggo' ),
					'' => esc_html__( 'None', 'pgggo' ),
				],
        'condition' => [
          'field_type' => 'image',
        ],
			]
);




$pgggo_repeatergrid->add_responsive_control(
			'pgggo_image_hover_effect_tab_hover_overlay',
			[
				'label' => esc_html__( 'Overlay Effect', 'pgggo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'pgggo-overlay-effect-1-hover-redtint'  => esc_html__( 'Red Overlay', 'pgggo' ),
          'pgggo-overlay-effect-1-hover-greentint'  => esc_html__( 'Green Overlay', 'pgggo' ),
          'pgggo-overlay-effect-1-hover-bluetint'  => esc_html__( 'Blue Overlay', 'pgggo' ),
          'pgggo-overlay-effect-1-hover-greenrosetint'  => esc_html__( 'Green Rose Overlay', 'pgggo' ),
          'pgggo-overlay-effect-1-hover-blueredtint'  => esc_html__( 'Blue Red Overlay', 'pgggo' ),
					'' => esc_html__( 'None', 'pgggo' ),
				],
        'condition' => [
          'field_type' => 'image',
        ],
			]
);


$pgggo_repeatergrid->end_controls_tab();
$pgggo_repeatergrid->end_controls_tabs();
