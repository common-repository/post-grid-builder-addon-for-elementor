<?php
if (!defined('ABSPATH')) {
    exit;
}
$pgggo_repeatergrid->add_control(
    'field_shortcode',
    [
        'label'     => esc_html__('Shortcode with brackets', 'pgggo'),
        'type'      => \Elementor\Controls_Manager::TEXT,
        'condition' => [
            'field_type' => 'shortcode',
        ],
    ]
);

$pgggo_repeatergrid->add_control(
    'field_shortcode_elementor',
    [
        'label'     => esc_html__('Elementor Template ID', 'pgggo'),
        'type'      => \Elementor\Controls_Manager::NUMBER,
        'description'  => esc_html__('Note is Elementor Pro Only Feature, if page template shortcode is [elementor-template id="736"] then its ID is 736', 'pgggo'),
        'condition' => [
            'field_type' => 'shortcode_elementor_pro',
        ],
    ]
);
