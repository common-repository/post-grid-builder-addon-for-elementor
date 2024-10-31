<?php
/**
 *
 * @since 1.0.0
 */
namespace Elementor;
use Elementor\Pgggo_Featurred_Image_Getter;


class Elementor_Pgggo_Post_Grid2 extends \Elementor\Widget_Base
{
    public function __construct($data = [], $args = null) {
      parent::__construct($data, $args);
      wp_register_script('pgggo-ajax-jquery', plugin_dir_url(PGGGO_PLUGIN_FILE) . 'includes/js/pgggo-ajax-jquery.js', ['jquery','elementor-frontend'], '1.0', true);
    }

    public function get_script_depends() {
      return [ 'pgggo-ajax-jquery' ];
    }

    /**
     * Get widget name.
     *
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'pgggo_post_grid2';
    }

    /**
     * Get widget title.
     *
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return esc_html__('Post Grid Builder', 'pgggo');
    }

    /**
     * Get widget icon.
     *
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'eicon-info-box';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the  widget belongs to.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories()
    {
        return ['pgggo-category'];
    }

    /**
     * Get list of custom postypes.
     *
     * Retrieve the list of categories the  widget belongs to.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array custom post type list.
     */
    public function pgggo_get_data()
    {
        $pgggo_data = new \PGGGOCORENS\Pgggo();
        return $pgggo_data;
    }

    private function get_acf_list()
    {
        if (!class_exists('acf')) {
            return array();
        }
        global $wpdb;

        if (false === ($pgggo_qry = get_transient('pgggo_acf_list_transient'))) {

            // It wasn't there, so regenerate the data and save the transient
            $pgggo_qry = new \WP_Query('posts_per_page=-1&post_excerpt=field_name&post_title=field_label&post_type=acf-field');
            set_transient('pgggo_acf_list_transient', $pgggo_qry, 12 * 107000);
        }
        $pgggo_cus_acf_field = array();
        foreach ($pgggo_qry->posts as $key => $value) {
            $pgggo_cus_acf_field[$value->post_excerpt] = $value->post_title;
        }
        return $pgggo_cus_acf_field;
    }

    public function pgggo_meta_data_retrievier_taxonomy()
    {
        $args = array(
            'public' => true,
        );
        $output     = 'object'; // or objects
        $operator   = 'and'; // 'and' or 'or'
        $taxonomies = get_taxonomies($args, $output, $operator);
        $taxonarray = array();
        foreach ($taxonomies as $key => $value) {
            $taxonarray[$value->name] = $value->label;
        }
        return $taxonarray;
    }

    /**
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls()
    {

        $pgggo_data                    = $this->pgggo_get_data();
        $pgggo_custompostype_array     = $pgggo_data->pgggo_list_of_posttypes();
        $pgggo_taxonomy_array          = $pgggo_data->pgggo_list_of_terms();
        $pgggo_taxonomy_tag_array      = $pgggo_data->pgggo_list_of_tags();
        $pgggo_taxonomy_repeat_tax     = $pgggo_data->pgggo_list_of_taxonomy_repeater_field();
        $pgggo_taxonomy_repeat_tax[''] = 'None';
        $pgggo_taxonomy_repeat_term    = $pgggo_data->pgggo_list_of_terms_repeater_field();
        $pgggo_post_status_array       = $pgggo_data->pgggo_list_of_post_status();
        $pgggo_image_size_list         = $pgggo_data->pgggo_ren_func_array_of_image_size();
        $pgggo_taxonomy_tax_mata       = $pgggo_data->pgggo_list_of_taxonomy_repeater_field();

        $pgggo_taxonomy_list_v2 = $pgggo_data->pgggo_taxonomy_selector();

        $this->start_controls_section(
            'pgggo_grid_design',
            [
                'label' => esc_html__('Grid Maker', 'pgggo'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'pgggo_heading_grid_designer',
            [
                'type'            => \Elementor\Controls_Manager::RAW_HTML,
                'raw'             => esc_html__('Use sortable drag and drop grid maker to create/arrange the layout for your grid.', 'pgggo'),
                'content_classes' => 'pgggo-descriptor',
            ]
        );

        $this->add_responsive_control(
            'pgggo_layout',
            [
                'label'     => esc_html__('Column Count', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 'flex',
                'options'   => [
                    'flex' => esc_html__('1 Colum Layout', 'pgggo'),
                    'grid' => esc_html__('2 Colum Layout', 'pgggo'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .pgggo-card-design-mul' => 'display: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'pgggo_width_control',
            [
                'label'      => esc_html__('Column 1 Width', 'pgggo'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['fr'],
                'range'      => [
                    'fr' => [
                        'min'  => 0,
                        'max'  => 5,
                        'step' => 0.1,
                    ],
                ],
                'default'    => [
                    'unit' => 'fr',
                    'size' => 1,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .pgggo-card-design-mul' => 'grid-template-columns: {{SIZE}}{{UNIT}} 1fr;',
                ],
                'condition'  => [
                    'pgggo_layout' => ['grid'],
                ],
            ]
        );

        $this->add_control(
            'pgggo_align_content_grid_ver',
            [
                'label'     => esc_html__('Vertical Align Content', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'options'   => [
                    'flex-start' => esc_html__('Top', 'pgggo'),
                    'Center'     => esc_html__('Center', 'pgggo'),
                    'flex-end'   => esc_html__('Bottom', 'pgggo'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .pgggo-card-design-mul' => 'align-items: {{VALUE}}',
                ],
                'default'   => 'flex-start',
                'condition' => [
                    'pgggo_layout' => ['grid'],
                ],
            ]
        );

        $pgggo_repeatergrid = new \Elementor\Repeater();

        $pgggo_repeatergrid->add_responsive_control(
            'pgggo_layout_inner',
            [
                'label'       => esc_html__('Inner Column Width', 'pgggo'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'range' => [
          					'%' => [
          						'min' => 0,
          						'max' => 100,
          					],
          				],
          				'default' => [
          					'unit' => '%',
          					'size' => 100,
          				],
                'description' => __('Setting 50%/50% for 2 adjacent field type will put the content in same row. 25%-75% Supported', 'pgggo'),
                'selectors'   => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'flex-basis: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $pgggo_repeatergrid->add_responsive_control(
            'pgggo_layout_inner_max_width',
            [
                'label'       => esc_html__('Inner Column Max Width', 'pgggo'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'range' => [
                    '%' => [
                      'min' => 0,
                      'max' => 100,
                    ],
                  ],
                  'default' => [
                    'unit' => '%',
                    'size' => 100,
                  ],
                'description' => __('if content overflows using above control then this can be used', 'pgggo'),
                'selectors'   => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'max-width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );


        $pgggo_repeatergrid->add_control(
            'field_type',
            [
                'label'   => esc_html__('Field Type', 'pgggo'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'title',
                'options' => [
                    'title'          => esc_html__('Title', 'pgggo'),
                    'content'        => esc_html__('Content', 'pgggo'),
                    'metadata'       => esc_html__('Post/Page Metadata', 'pgggo'),
                    'metadatasingle' => esc_html__('Single Metadata', 'pgggo'),
                    'button'         => esc_html__('Button', 'pgggo'),
                    'image'          => esc_html__('Image', 'pgggo'),
                    'html'           => esc_html__('HTML', 'pgggo'),
                    'shortcode'      => esc_html__('Shortcode', 'pgggo'),
                    'shortcode_elementor_pro' => esc_html__('Elementor Pro - Template ID', 'pgggo'),
                    'rating'         => esc_html__('WooCommerce Rating', 'pgggo'),
                    'wooprice'       => esc_html__('WooCommerce Price', 'pgggo'),
                ],
            ]
        );

        $pgggo_repeatergrid->add_control(
            'pgggo_content_after_placer',
            [
                'label'       => esc_html__('Content After', 'pgggo'),
                'type'        => \Elementor\Controls_Manager::TEXTAREA,
                'rows'        => 2,
                'placeholder' => esc_html__('Adds the content after field type', 'pgggo'),
                'condition'   => [
                    'field_type' => ['content', 'wooprice', 'shortcode', 'metadatasingle'],
                ],
            ]
        );
        //version 1.1.0 Update for content
        // Allows text content to be placed befor content
        $pgggo_repeatergrid->add_control(
            'pgggo_content_before_placer',
            [
                'label'       => esc_html__('Content Before', 'pgggo'),
                'type'        => \Elementor\Controls_Manager::TEXTAREA,
                'rows'        => 2,
                'placeholder' => esc_html__('Adds the content before field type', 'pgggo'),
                'condition'   => [
                    'field_type' => ['content', 'wooprice', 'shortcode', 'metadatasingle'],
                ],
            ]
        );

        require plugin_dir_path(__FILE__) . 'fields/pgggo_button.php';
        require plugin_dir_path(__FILE__) . 'fields/pgggo_content.php';
        require plugin_dir_path(__FILE__) . 'fields/pgggo_html.php';
        require plugin_dir_path(__FILE__) . 'fields/pgggo_image.php';
        require plugin_dir_path(__FILE__) . 'fields/pgggo_metadata.php';
        require plugin_dir_path(__FILE__) . 'fields/pgggo_price.php';
        require plugin_dir_path(__FILE__) . 'fields/pgggo_shortcode.php';
        require plugin_dir_path(__FILE__) . 'fields/pgggo_metadatasingle.php';
        require plugin_dir_path(__FILE__) . 'fields/pgggo_common_controls.php';

        $this->start_controls_tabs('pgggo_columntabs');

        $this->start_controls_tab(
            'pgggo_column_1',
            [
                'label' => esc_html__('Column 1 Content', 'pgggo'),
            ]
        );

        $this->add_control(
            'pgggo_field_gen',
            [
                'label'       => esc_html__('Grid Layout', 'pgggo'),
                'type'        => \Elementor\Controls_Manager::REPEATER,
                'fields'      => $pgggo_repeatergrid->get_controls(),
                'default'     => [
                    [
                        'field_type' => 'title'
                    ],
                    [
                        'field_type' => 'content',
                    ],
                ],
                'title_field' => '{{{ field_type }}}',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'pgggo_column_2',
            [
                'label'     => esc_html__('Column 2 Content', 'pgggo'),
                'condition' => [
                    'pgggo_layout' => ['grid'],
                ],
            ]
        );

        $this->add_control(
            'pgggo_field_gen2',
            [
                'label'       => esc_html__('Grid Layout2', 'pgggo'),
                'type'        => \Elementor\Controls_Manager::REPEATER,
                'fields'      => $pgggo_repeatergrid->get_controls(),
                'default'     => [
                    [
                        'field_type' => 'title',
                    ],
                    [
                        'field_type' => 'content',
                    ],
                ],
                'title_field' => '{{{ field_type }}}',
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'pgggo_grid_sort_and_filter',
            [
                'label' => esc_html__('Sort / Filter Design', 'pgggo'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'pgggo_enable_sorter',
            [
                'label'        => esc_html__('Enable Frontend Sorting and Filtering', 'pgggo'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'pgggo'),
                'label_off'    => esc_html__('No', 'pgggo'),
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $this->add_control(
            'pgggo_enable_clear',
            [
                'label'        => esc_html__('Enable Clear Button', 'pgggo'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'pgggo'),
                'label_off'    => esc_html__('No', 'pgggo'),
                'description'  => esc_html__('A Clear button with same styling as filter button will be added which will allow quickly clear options selected', 'pgggo'),
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $this->add_control(
            'pgggo_filter_button_text_clear',
            [
                'label'   => esc_html__('Clear Button Text', 'pgggo'),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Clear Selections', 'pgggo'),
                'condition'   => [
                    'pgggo_enable_clear' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'pgggo_enable_sorter_ajax',
            [
                'label'        => esc_html__('Enable Ajax Functionaliy', 'pgggo'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'pgggo'),
                'description'  => esc_html__('By enabling this there loading will happen without page load', 'pgggo'),
                'label_off'    => esc_html__('No', 'pgggo'),
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );


        $this->add_control(
            'pgggo_enable_sorter_collapse',
            [
                'label'        => esc_html__('Enable Collapase', 'pgggo'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'pgggo'),
                'label_off'    => esc_html__('No', 'pgggo'),
                'description'  => esc_html__('Do not enable while in preview mode. Complete the design first then enable before publishing', 'pgggo'),
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $this->add_control(
            'pgggo_filter_button_text',
            [
                'label'   => esc_html__('Filter Button Text', 'pgggo'),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Filter', 'pgggo'),
            ]
        );

        $this->add_control(
            'pgggo_filter_button_text2',
            [
                'label'   => esc_html__('Sort/Filter Button Text', 'pgggo'),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Sort/Filter', 'pgggo'),
            ]
        );

        $this->add_control(
           'pgggo_filter_button_text_include',
           [
               'label'   => esc_html__('Include only these Term IDs', 'pgggo'),
               'type'    => \Elementor\Controls_Manager::TEXT,
               'description' => esc_html__('Comma seperated list of term ids to be included. Tip: Reveal IDs By Oliver Schlöbe can show all the IDS.  https://wordpress.org/plugins/reveal-ids-for-wp-admin-25/','pgggo'),
               'default' => "",
           ]
       );

        $this->add_control(
           'pgggo_filter_button_text_exculde',
           [
               'label'   => esc_html__('Exclude these Term IDs', 'pgggo'),
               'type'    => \Elementor\Controls_Manager::TEXT,
               'description' => esc_html__('Comma seperated list of term ids to excluded. Tip: Reveal IDs By Oliver Schlöbe can show all the IDS.  https://wordpress.org/plugins/reveal-ids-for-wp-admin-25/','pgggo'),
               'default' => "",
           ]
       );

       $this->add_control(
    			'pgggo_acend_icon',
    			[
    				'label' => esc_html__( 'Acend Icon', 'pgggo' ),
    				'type' => \Elementor\Controls_Manager::ICONS,
    				'default' => [
    					'value' => 'fas fa-arrow-circle-up',
    					'library' => 'solid',
    				],
    			]
    		);

        $this->add_control(
     			'pgggo_decend_icon',
     			[
     				'label' => esc_html__( 'Decend Icon', 'pgggo' ),
     				'type' => \Elementor\Controls_Manager::ICONS,
     				'default' => [
     					'value' => 'far fa-arrow-alt-circle-down',
     					'library' => 'solid',
     				],
     			]
     		);


        $pgggo_filter_repeater = new \Elementor\Repeater();

        $pgggo_filter_repeater->add_control(
            'pgggo_grid_sort_and_filter_type',
            [
                'label'   => esc_html__('Sort Type', 'pgggo'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'sort',
                'options' => [
                    'sort'   => esc_html__('Sort Ascending/Descending', 'pgggo'),
                    'select' => esc_html__('Selection Bar', 'pgggo'),
                    'list'   => esc_html__('Horizontal List', 'pgggo'),
                ],
            ]
        );

        $pgggo_filter_repeater->add_control(
            'pgggo_grid_sort_and_filter_label',
            [
                'label'       => esc_html__('Label', 'pgggo'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => esc_html__('Label', 'pgggo'),
                'placeholder' => esc_html__('Label', 'pgggo'),
                'condition'   => [
                    'pgggo_grid_sort_and_filter_type' => ['select', 'list', 'sort'],
                ],
            ]
        );

        $pgggo_filter_repeater->add_control(
            'taxonomy',
            [
                'label'     => esc_html__('Select Taxonomy', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'options'   => $pgggo_taxonomy_list_v2,
                'condition' => [
                    'pgggo_grid_sort_and_filter_type' => ['list', 'select'],
                ],
            ]
        );

        $pgggo_filter_repeater->add_control(
            'pgggo_grid_sort_and_filter_multiselect',
            [
                'label'     => esc_html__('Selection Type', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 'single',
                'options'   => [
                    'single'   => esc_html__('Single', 'pgggo'),
                    'multiple' => esc_html__('Multiple', 'pgggo'),
                ],
                'condition' => [
                    'pgggo_grid_sort_and_filter_type' => ['select'],
                ],
            ]
        );

        $pgggo_filter_repeater->add_control(
    			'pgggo_filter_enable_acf_icons',
    			[
    				'label' => esc_html__( 'Enable ACF Icons?', 'pgggo' ),
    				'type' => \Elementor\Controls_Manager::SWITCHER,
    				'label_on' => esc_html__( 'Show', 'pgggo' ),
    				'label_off' => esc_html__( 'Hide', 'pgggo' ),
    				'return_value' => 'yes',
    				'default' => 'no',
            'condition' => [
                'pgggo_grid_sort_and_filter_type' => ['list'],
            ],
    			]
    		);


        $pgggo_filter_repeater->add_control(
          'pgggo_filter_enable_acf_icons_acf',
          [
            'label'     => esc_html__('ACF Field Selector', 'pgggo'),
            'type'      => \Elementor\Controls_Manager::SELECT,
            'options'   => $this->get_acf_list(),
            'description' => esc_html__('Note: Must return url of image and size will remain same'),
            'condition' => [
                'pgggo_grid_sort_and_filter_type' => ['list'],
                'pgggo_filter_enable_acf_icons' => 'yes',
            ],
          ]
        );

        $this->add_control(
            'pgggo_grid_sort_and_filter_layout',
            [
                'label'                           => esc_html__('Layout', 'pgggo'),
                'type'                            => \Elementor\Controls_Manager::REPEATER,
                'fields'                          => $pgggo_filter_repeater->get_controls(),
                'pgggo_grid_sort_and_filter_type' => '{{{ pgggo_grid_sort_and_filter_type }}}',
            ]
        );

        $this->add_control(
            'pgggo_title_one_sorting',
            [
                'label'     => esc_html__('Color Parameters', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'pgggo_sort_color_filter_button_color',
            [
                'label'     => esc_html__('Filter button text color', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::COLOR,

                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .pgggo-sort-collapse-submit-b1' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'pgggo_sort_color_filter_button_background_color',
            [
                'label'     => esc_html__('Filter button background color', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#504f4f',
                'selectors' => [
                    '{{WRAPPER}} .pgggo-sort-collapse-submit-b1' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'pgggo_sort_color_filter_button_color_b2',
            [
                'label'     => esc_html__('Sort/Filter button text color', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .pgggo-sort-collapse-submit-b2' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'pgggo_sort_color_filter_button_background_color_b2',
            [
                'label'     => esc_html__('Sort/Filter button background color', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#504f4f',
                'selectors' => [
                    '{{WRAPPER}} .pgggo-sort-collapse-submit-b2' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'pgggo_sort_color_filter_box_text color',
            [
                'label'     => esc_html__('Filter box text color', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#313131',
                'selectors' => [
                    '{{WRAPPER}} .pgggo-sort-filter' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'pgggo_sort_color_filter_box_background_color',
            [
                'label'     => esc_html__('Filter box background color', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#f6f7f6',
                'selectors' => [
                    '{{WRAPPER}} .pgggo-sort-filter' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'pgggo_sort_color_filter_box_background_color_list',
            [
                'label'     => esc_html__('Filter box list background color', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#e8e8e885',
                'selectors' => [
                    '{{WRAPPER}} .pgggo-list-taxon-main' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'pgggo_sort_color_filter_box_text color_selection',
            [
                'label'     => esc_html__('Filter box options text color', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#373837',
                'selectors' => [
                    '{{WRAPPER}} #pgggo-sort-filter .icon-box, .ui.fluid.pgggodropdown' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'pgggo_sort_color_filter_box_background_color_selection',
            [
                'label'     => esc_html__('Filter box options background color', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} #pgggo-sort-filter .icon-box, .ui.fluid.pgggodropdown' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'pgggo_sort_color_filter_box_text color_active',
            [
                'label'     => esc_html__('Filter box active options text color', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} #pgggo-sort-filter ul li label input[type="checkbox"]:checked ~ .icon-box .fa, #pgggo-sort-filter ul li label input[type="checkbox"]:checked ~ .icon-box div' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'pgggo_sort_color_filter_box_background_color_active',
            [
                'label'     => esc_html__('Filter box active options background color', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#000000',
                'selectors' => [
                    '{{WRAPPER}} #pgggo-sort-filter ul li label input[type="checkbox"]:checked ~ .icon-box' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'pgggo_title_one_typography',
            [
                'label'     => esc_html__('Typography Parameters', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'pgggo_sort_label_button_typography',
                'label'    => esc_html__('Filter Button Typography', 'pgggo'),
                'selector' => '{{WRAPPER}} .pgggo-sort-collapse-submit-b1',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'pgggo_sort_label_button_typography_b2',
                'label'    => esc_html__('Sort/Filter Button Typography', 'pgggo'),
                'selector' => '{{WRAPPER}} .pgggo-sort-collapse-submit-b2',
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'pgggo_sort_label_content_typography',
                'label'    => esc_html__('Label Typography', 'pgggo'),
                'selector' => '{{WRAPPER}} .pgggo-select-label',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'pgggo_sort_options_content_typography',
                'label'    => esc_html__('Options Typography', 'pgggo'),
                'selector' => '{{WRAPPER}} #pgggo-sort-filter .icon-box, .ui.fluid.pgggodropdown',
            ]
        );

        $this->add_responsive_control(
            'pgggo_sort_options_content_margin',
            [
                'label'      => esc_html__('Margin for Filter Box', 'pgggo'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .pgggo-sort-filter' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pgggo_sort_options_content_padding',
            [
                'label'      => esc_html__('Padding for Filter Box', 'pgggo'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default'    => [
                    'top'      => '15',
                    'right'    => '15',
                    'bottom'   => '15',
                    'left'     => '15',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .pgggo-sort-filter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'pgggo_grid_designer',
            [
                'label' => esc_html__('Grid Designer', 'pgggo'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->start_controls_tabs(
            'pgggo_responsive_controller'
        );

        $this->start_controls_tab(
            'pgggo_responsive_controller_desktop',
            [
                'label' => esc_html__('Desktop', 'pgggo'),
            ]
        );

        $this->add_control(
            'pgggo_grid_column_dektop',
            [
                'label'   => esc_html__('Column count in Desktop Devices', 'pgggo'),
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'min'     => 1,
                'max'     => 12,
                'step'    => 1,
                'default' => 4,
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'pgggo_responsive_controller_tablet',
            [
                'label' => esc_html__('Tablets', 'pgggo'),
            ]
        );

        $this->add_control(
            'pgggo_grid_column_tablet',
            [
                'label'   => esc_html__('Column count in Tablet Devices', 'pgggo'),
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'min'     => 1,
                'max'     => 12,
                'step'    => 1,
                'default' => 3,
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'pgggo_responsive_controller_mobile',
            [
                'label' => esc_html__('Mobile', 'pgggo'),
            ]
        );

        $this->add_control(
            'pgggo_grid_column_mobile',
            [
                'label'   => esc_html__('Column count in Mobile Devices', 'pgggo'),
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'min'     => 1,
                'max'     => 12,
                'step'    => 1,
                'default' => 1,
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'pgggo_responsive_controller_hr',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'make_block_clickable',
            [
                'label'        => esc_html__('Make grid clickable', 'pgggo'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'pgggo'),
                'label_off'    => esc_html__('No', 'pgggo'),
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        $this->add_control(
            'make_block_clickable_makeit_acf',
            [
                'label'        => esc_html__('Make it ACF link', 'pgggo'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'pgggo'),
                'label_off'    => esc_html__('No', 'pgggo'),
                'return_value' => 'yes',
                'default'      => '',
                'condition'   => [
                  'make_block_clickable' => ['yes'],
                ],
            ]
        );

        $this->add_control(
          'make_block_clickable_makeit_acf_field',
          [
            'label'       => esc_html__('Custom Field Name', 'pgggo'),
            'type'      => \Elementor\Controls_Manager::SELECT,
            'options'   => $this->get_acf_list(),
            'description' => esc_html__('Note : the field should return url', 'pgggo'),
            'condition'   => [
              'make_block_clickable_makeit_acf' => ['yes'],
            ],
          ]
        );


        $this->add_control(
            'make_block_clickable_makeit_acf_open_new_tab',
            [
                'label'        => esc_html__('Open in New Tab', 'pgggo'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'pgggo'),
                'label_off'    => esc_html__('No', 'pgggo'),
                'return_value' => 'yes',
                'default'      => '',
                'condition'   => [
                  'make_block_clickable' => ['yes'],
                ],
            ]
        );



        $this->add_control(
            'pgggo_align_content_grid',
            [
                'label'     => esc_html__('Align Content', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'options'   => [
                    'flex-start' => esc_html__('Top', 'pgggo'),
                    'Center'     => esc_html__('Center', 'pgggo'),
                    'flex-end'   => esc_html__('Bottom', 'pgggo'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .pgggo-card-design' => 'align-content: {{VALUE}}',
                ],
                'default'   => 'flex-start',

            ]
        );

        $this->add_control(
            'pgggo_grid_column_gap',
            [
                'label'     => esc_html__('Column Gap', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .pgggo-row' => 'grid-column-gap: {{VALUE}}px',
                ],
                'default'   => 10,

            ]
        );

        $this->add_control(
            'pgggo_grid_row_gap',
            [
                'label'     => esc_html__('Row Gap', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .pgggo-row' => 'grid-row-gap: {{VALUE}}px',
                ],
                'default'   => 10,

            ]
        );

        $this->add_control(
            'pgggo_grid_enable_min_hight',
            [
                'label'     => esc_html__('Grid minimum height (px)', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .pgggo-card-design' => 'min-height: {{VALUE}}px',
                ],
                'default'   => 0,

            ]
        );

        $this->add_control(
            'pgggo_features_hr',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'pgggo_background_and_border_heading',
            [
                'label' => esc_html__('Background and Border', 'pgggo'),
                'type'  => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'pgggo_heading_back_in_out',
            [
                'type'            => \Elementor\Controls_Manager::RAW_HTML,
                'raw'             => esc_html__('Each individual grid have an outer and inner container.', 'pgggo'),
                'content_classes' => 'pgggo-descriptor',
            ]
        );

        $this->start_controls_tabs(
            'pgggo_background_and_border_heading_tabs'
        );

        $this->start_controls_tab(
            'pgggo_innergrid_tab',
            [
                'label' => esc_html__('Inner Grid', 'pgggo'),
            ]
        );

        $this->add_control(
            'pgggo_make_featured_image_as_background',
            [
                'label'        => esc_html__('Make Featured image as background', 'pgggo'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'pgggo'),
                'label_off'    => esc_html__('No', 'pgggo'),
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        $this->add_control(
            'pgggo_b_top_grad',
            [
                'label'     => esc_html__('Top gradient', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => 'rgba(0,0,0,0)',
                'condition' => [
                    'pgggo_make_featured_image_as_background' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'pgggo_b_bottom_grad',
            [
                'label'     => esc_html__('Bottom gradient', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => 'rgba(192,252,53,0.88)',
                'condition' => [
                    'pgggo_make_featured_image_as_background' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'pgggo_b_cover',
            [
                'label'     => esc_html__('Background Size', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 'cover',
                'options'   => [
                    'auto'    => esc_html__('Auto', 'pgggo'),
                    'length'  => esc_html__('Length', 'pgggo'),
                    'cover'   => esc_html__('Cover', 'pgggo'),
                    'contain' => esc_html__('Contain', 'pgggo'),
                    'initial' => esc_html__('Initial', 'pgggo'),
                    'inherit' => esc_html__('Inherit', 'pgggo'),
                ],
                'condition' => [
                    'pgggo_make_featured_image_as_background' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'pgggo_b_position',
            [
                'label'     => esc_html__('Background Position', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 'center center',
                'options'   => [
                    'left top'        => esc_html__('left top', 'pgggo'),
                    'left center'     => esc_html__('left center', 'pgggo'),
                    'left bottom'     => esc_html__('left bottom', 'pgggo'),
                    'right top'       => esc_html__('right top', 'pgggo'),
                    'right center'    => esc_html__('right center', 'pgggo'),
                    'right bottom'    => esc_html__('right bottom', 'pgggo'),
                    'center top'      => esc_html__('center top', 'pgggo'),
                    'center center'   => esc_html__('center center', 'pgggo'),
                    'center bottom  ' => esc_html__('center bottom  ', 'pgggo'),
                ],
                'condition' => [
                    'pgggo_make_featured_image_as_background' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'pgggo_b_repeat',
            [
                'label'     => esc_html__('Background Size', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 'no-repeat',
                'options'   => [
                    'repeat'    => esc_html__('Repeat', 'pgggo'),
                    'repeat-x'  => esc_html__('Repeat-x', 'pgggo'),
                    'repeat-y'  => esc_html__('Repeat-y', 'pgggo'),
                    'no-repeat' => esc_html__('No-repeat', 'pgggo'),
                    'initial'   => esc_html__('Initial', 'pgggo'),
                    'inherit'   => esc_html__('Inherit', 'pgggo'),
                ],
                'condition' => [
                    'pgggo_make_featured_image_as_background' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'pgggo_back_featured_image_size',
            [
                'label'     => esc_html__('Select Images Size', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'options'   => $pgggo_image_size_list,
                'condition' => [
                    'pgggo_make_featured_image_as_background' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'Inner_grid_Designer',
            [
                'label' => esc_html__('Background For Grid', 'pgggo'),
                'type'  => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'pgggo_grid_background',
                'label'    => esc_html__('Grid Background', 'pgggo'),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .pgggo-card-design',
            ]
        );

        $this->add_control(
            'pgggo_grid_box_border_radius',
            [
                'label'     => esc_html__('Grid border radius (px)', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .pgggo-card-design' => 'border-radius: {{VALUE}}px',
                ],
                'default'   => 0,

            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'pgggo_border_grid_inner',
                'label'    => esc_html__('Grid Box Border', 'pgggo'),
                'selector' => '{{WRAPPER}} .pgggo-card-design',
            ]
        );

        $this->add_control(
            'pgggo_grid_block_padding_full',
            [
                'label'      => esc_html__('Grid padding', 'pgggo'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default'    => [
                    'top'      => '0',
                    'right'    => '0',
                    'bottom'   => '0',
                    'left'     => '0',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .pgggo-card-design' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'pgggo_outgrid_tab',
            [
                'label' => esc_html__('Outer Grid', 'pgggo'),
            ]
        );

        $this->add_control(
            'Outer_grid_Designer',
            [
                'label' => esc_html__('Background For Outer Grid', 'pgggo'),
                'type'  => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'pgggo_grid_background_outer',
                'label'    => esc_html__('Outer Grid Background', 'pgggo'),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .pgggo-card-design-outer',
            ]
        );

        $this->add_control(
            'pgggo_grid_box_border_radius_outer',
            [
                'label'     => esc_html__('Outer Grid border radius (px)', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .pgggo-card-design-outer' => 'border-radius: {{VALUE}}px',
                ],
                'default'   => 0,

            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'pgggo_border_grid_outer',
                'label'    => esc_html__('Grid Box Border', 'pgggo'),
                'selector' => '{{WRAPPER}} .pgggo-card-design-outer',
            ]
        );

        $this->add_control(
            'pgggo_grid_block_padding_full_outer',
            [
                'label'      => esc_html__('Outer Grid padding', 'pgggo'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default'    => [
                    'top'      => '0',
                    'right'    => '0',
                    'bottom'   => '0',
                    'left'     => '0',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .pgggo-card-design-outer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'pgggo_pagination_customize',
            [
                'label' => esc_html__('Pagination Designer', 'pgggo'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'post_count',
            [
                'label'   => esc_html__('Posts per page', 'pgggo'),
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'default' => '',
            ]
        );

        $this->add_control(
           'post_count_midsize',
           [
               'label'   => esc_html__('Pagination Mid Size', 'pgggo'),
               'type'    => \Elementor\Controls_Manager::NUMBER,
               'default' => 3,
           ]
       );

        $this->add_control(
            'post_enable_pagination',
            [
                'label'        => esc_html__('Disable Pagination', 'pgggo'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'pgggo'),
                'label_off'    => esc_html__('No', 'pgggo'),
                'return_value' => 'true',
                'default'      => 'true',
            ]
        );

        $this->add_control(
            'pgggo_enable_pagination_on_top',
            [
                'label'        => esc_html__('Enable pagination on Top', 'pgggo'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Enable', 'pgggo'),
                'label_off'    => esc_html__('Disable', 'pgggo'),
                'return_value' => 'yes',
                'default'      => '',
                'condition'   => [
                    'post_enable_pagination' => ['','false'],
                ],
            ]
        );

        $this->add_control(
            'pgggo_enable_pagination_on_bottom',
            [
                'label'        => esc_html__('Enable pagination on Bottom', 'pgggo'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Enable', 'pgggo'),
                'label_off'    => esc_html__('Disable', 'pgggo'),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'   => [
                    'post_enable_pagination' => ['','false'],
                ],
            ]
        );

        $this->add_control(
            'post_enable_pagination_fixed',
            [
                'label'        => esc_html__('Display only fixed Post Count', 'pgggo'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'pgggo'),
                'label_off'    => esc_html__('No', 'pgggo'),
                'return_value' => 'true',
                'default'      => '',
            ]
        );

        $this->add_control(
            'pgggo_enable_ajax_pagination',
            [
                'label'        => esc_html__('Enable ajax pagination', 'pgggo'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Enable', 'pgggo'),
                'label_off'    => esc_html__('Disable', 'pgggo'),
                'description'  => esc_html__( 'Only use if page if maximum number of pages are 7 .Instant loading will happen without page refresh. This only works in frontend', 'pgggo' ),
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $this->add_control(
            'pgggo_enable_ajax_loadmore',
            [
                'label'        => esc_html__('Enable Load More Button(Ajax)', 'pgggo'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Enable', 'pgggo'),
                'label_off'    => esc_html__('Disable', 'pgggo'),
                'description'  => esc_html__( 'Instant loading will happen without page refresh. This only works in frontend', 'pgggo' ),
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $this->add_control(
            'pgggo_filter_all_loaded',
            [
                'label'   => esc_html__('All Loaded Button Text', 'pgggo'),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('All Loaded', 'pgggo'),
            ]
        );

        $this->add_control(
            'pgggo_filter_button_text_loadmore',
            [
                'label'   => esc_html__('Load More Button Text', 'pgggo'),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Load More', 'pgggo'),
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'pagination_typography_loadmore',
                'label'    => esc_html__('Load more Typography', 'pgggo'),
                'selector' => '{{WRAPPER}} .pgggo-loadmore-button-ajax',
            ]
        );

        $this->add_control(
            'paggination_loadmore_textcolor',
            [
                'label'     => esc_html__('Load More Button Text Color', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .pgggo-loadmore-button-ajax' => 'color: {{VALUE}}',
                ],
                'condition'   => [
                    'pgggo_enable_ajax_loadmore' => ['yes'],
                ],
            ]
        );

        $this->add_control(
            'paggination_loadmore_backgroundcolor',
            [
                'label'     => esc_html__('Load More Button Background Color', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .pgggo-loadmore-button-ajax' => 'background: {{VALUE}}',
                ],
                'condition'   => [
                    'pgggo_enable_ajax_loadmore' => ['yes'],
                ],
            ]
        );

        $this->add_responsive_control(
            'pgggo_loadmore_margin',
            [
                'label'      => esc_html__('Load More Button Margin', 'pgggo'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .pgggo-loadmore-button-ajax' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'   => [
                    'pgggo_enable_ajax_loadmore' => ['yes'],
                ],
            ]
        );

        $this->add_responsive_control(
            'pgggo_loadmore_padding',
            [
                'label'      => esc_html__('Load More Button Padding', 'pgggo'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default'    => [
                    'top'      => '10',
                    'right'    => '10',
                    'bottom'   => '10',
                    'left'     => '10',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .pgggo-loadmore-button-ajax' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'   => [
                    'pgggo_enable_ajax_loadmore' => ['yes'],
                ],
            ]
        );


        $this->add_control(
            'hrmaincontrol',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'pagination_typography',
                'label'    => esc_html__('Pagination Typography', 'pgggo'),
                'selector' => '{{WRAPPER}} .pgggo-pagination',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'pgggo_paggination_background',
                'label'    => esc_html__('Background', 'pgggo'),
                'types'    => ['classic', 'gradient', 'video'],
                'selector' => '{{WRAPPER}} .pgggo-pagination',
            ]
        );

        $this->add_control(
            'paggination_text_color',
            [
                'label'     => esc_html__('Pagination Text Color', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#5b5b5b',
                'selectors' => [
                    '{{WRAPPER}} .pgggo-pagination .page-numbers' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'paggination_background_color_pagenumber',
            [
                'label'     => esc_html__('Pagination background color for page numbers', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .pgggo-pagination .page-numbers' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'hractive',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'paggination_background_color_active',
            [
                'label'     => esc_html__('Pagination background color for active page', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#54595f',
                'selectors' => [
                    '{{WRAPPER}} .pgggo-pagination .current ' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'paggination_text_color_active',
            [
                'label'     => esc_html__('Pagination text Color For active page', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .pgggo-pagination .current' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'hrbackcol',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'pgggo_pagination_margin',
            [
                'label'      => esc_html__('Pagination Block Margin', 'pgggo'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .pgggo-pagination' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'pgggo_pagination_padding',
            [
                'label'      => esc_html__('Pagination Block Padding', 'pgggo'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default'    => [
                    'top'      => '10',
                    'right'    => '10',
                    'bottom'   => '10',
                    'left'     => '10',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .pgggo-pagination' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'hrpagenum',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'pgggo_pagination_margin_pagenumber',
            [
                'label'      => esc_html__('Page number Margin', 'pgggo'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default'    => [
                    'top'      => '2',
                    'right'    => '20',
                    'bottom'   => '2',
                    'left'     => '20',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .pgggo-pagination .page-numbers' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'pgggo_pagination_padding_pagenumber',
            [
                'label'      => esc_html__('Page number Padding', 'pgggo'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default'    => [
                    'top'      => '5',
                    'right'    => '20',
                    'bottom'   => '5',
                    'left'     => '20',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .pgggo-pagination .page-numbers' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'paggination_numbers_border_border_radius',
            [
                'label'     => esc_html__('Pagination number border radius', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .pgggo-pagination .page-numbers' => 'border-radius: {{VALUE}}px',
                ],
                'default'   => 0,

            ]
        );

        $this->end_controls_section();

        //selection of posts
        $this->start_controls_section(
            'content_section_pgggo_content',
            [
                'label' => esc_html__('Type, Order and Filtering', 'pgggo'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'posttype',
            [
                'label'    => esc_html__('Post Type', 'pgggo'),
                'type'     => \Elementor\Controls_Manager::SELECT2,
                'default'  => 'post',
                'multiple' => true,
                'label_block'=>true,
                'options'  => $pgggo_custompostype_array
                ,
            ]
        );

        $this->add_control(
            'post_type_status',
            [
                'label'    => esc_html__('Post Status', 'pgggo'),
                'type'     => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'default'  => 'publish',
                'label_block'=>true,
                'options'  => $pgggo_post_status_array,
            ]
        );

        $this->add_control(
            'password_protected',
            [
                'label'        => esc_html__('Show password Protected Posts', 'pgggo'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'pgggo'),
                'label_off'    => esc_html__('Hide', 'pgggo'),
                'return_value' => 'true',
                'default'      => '',
            ]
        );

        $this->add_control(
            'order',
            [
                'label'    => esc_html__('Order', 'pgggo'),
                'type'     => \Elementor\Controls_Manager::SELECT,
                'default'  => 'ASC',
                'multiple' => true,
                'options'  => [
                    'ASC'  => esc_html__('Ascending ', 'pgggo'),
                    'DESC' => esc_html__('Descending ', 'pgggo'),
                ],
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label'    => esc_html__('Order By', 'pgggo'),
                'type'     => \Elementor\Controls_Manager::SELECT,
                'multiple' => true,
                'default'  => 'none',
                'options'  => [
                    'none'           => esc_html__('No Order', 'pgggo'),
                    'date'           => esc_html__('Date', 'pgggo'),
                    'ID'             => esc_html__('Order by Post ID', 'pgggo'),
                    'author'         => esc_html__('Order by Author', 'pgggo'),
                    'title'          => esc_html__('Order by Title', 'pgggo'),
                    'type'           => esc_html__('Order by Post type', 'pgggo'),
                    'modified'       => esc_html__('Order by Last Modified', 'pgggo'),
                    'parent'         => esc_html__('Order by Post/Page parent', 'pgggo'),
                    'rand'           => esc_html__('Random', 'pgggo'),
                    'comment_count'  => esc_html__('Order by Comment Count', 'pgggo'),
                    'relevance'      => esc_html__('By relevence', 'pgggo'),
                    'menu_order'     => esc_html__('Order by Page Order', 'pgggo'),
                    'meta_value'     => esc_html__('Order by Meta Value', 'pgggo'),
                    'meta_value_num' => esc_html__('Order by Numeric Meta Value ', 'pgggo'),
                ],
            ]
        );

        $this->add_control(
            'sortingbymetakey',
            [
                'label'       => esc_html__('Meta Key', 'pgggo'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => '',
                'placeholder' => esc_html__('Type meta Key here', 'pgggo'),
                'condition'   => [
                    'orderby' => ['meta_value', 'meta_value_num'],
                ],
            ]
        );

        $this->add_control(
            'sortingbymetavalue',
            [
                'label'       => esc_html__('Meta Value', 'pgggo'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => '',
                'placeholder' => esc_html__('Type meta value here', 'pgggo'),
                'condition'   => [
                    'orderby' => ['meta_value', 'meta_value_num'],
                ],
            ]
        );

        $this->add_control(
            'meta_compare',
            [
                'label'     => esc_html__('Meta Compare', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => '',
                'options'   => [
                    ''            => esc_html__('None', 'pgggo'),
                    '='           => esc_html__('Equal to', 'pgggo'),
                    '!='          => esc_html__('Not Equal to', 'pgggo'),
                    '>'           => esc_html__('Greater than', 'pgggo'),
                    '>='          => esc_html__('Greater than or equal to', 'pgggo'),
                    '<'           => esc_html__('Less Than', 'pgggo'),
                    '<='          => esc_html__('Less Than or equal to', 'pgggo'),
                    'LIKE'        => esc_html__('LIKE', 'pgggo'),
                    'NOT LIKE'    => esc_html__('NOT LIKE', 'pgggo'),
                    'IN'          => esc_html__('IN', 'pgggo'),
                    'NOT IN'      => esc_html__('NOT IN', 'pgggo'),
                    'BETWEEN'     => esc_html__('BETWEEN', 'pgggo'),
                    'NOT BETWEEN' => esc_html__('NOT BETWEEN', 'pgggo'),
                    'NOT EXISTS'  => esc_html__('NOT EXISTS', 'pgggo'),
                    'REGEXP'      => esc_html__('NOT REGEXP', 'pgggo'),
                    'RLIKE'       => esc_html__('RLIKE', 'pgggo'),
                ],
                'condition' => [
                    'orderby' => 'meta_value',
                ],
            ]
        );

        $this->add_control(
            'meta_compare_type',
            [
                'label'     => esc_html__('Meta Type', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 'CHAR',
                'options'   => [
                    'CHAR'            => esc_html__('CHAR Default', 'pgggo'),
                    'NUMERIC'           => esc_html__('NUMERIC', 'pgggo'),
                    'BINARY'            => esc_html__('BINARY', 'pgggo'),
                    'DATE'            => esc_html__('DATE', 'pgggo'),
                    'DATETIME'            => esc_html__('DATETIME', 'pgggo'),
                    'DECIMAL'            => esc_html__('DECIMAL', 'pgggo'),
                    'SIGNED'            => esc_html__('SIGNED', 'pgggo'),
                    'TIME'            => esc_html__('TIME', 'pgggo'),
                    'UNSIGNED'            => esc_html__('UNSIGNED', 'pgggo'),

                ],
                'condition' => [
                    'orderby' => 'meta_value',
                ],
            ]
        );

        $this->add_control(
            'oneauthor',
            [
                'label'       => esc_html__('Show Posts for specific Authors', 'pgggo'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'description' => esc_html__('This can be used to show specific posts from an author or multiple authors. Add comma seperated author IDs to display posts.
                                                To exclude any specific author put a negative sign infront of the id', 'pgggo'),
                'language'    => 'text',
            ]
        );

        $this->add_control(
    			'pgggo_enable_offset',
    			[
    				'label' => __( 'Offset', 'pgggo' ),
    				'type' => \Elementor\Controls_Manager::NUMBER,
            'description'=> esc_html__('This can be used to offset the post and have a new kind of layout. Note that posts per page and pagination must be enabled.', 'pgggo'),
    				'default' => 0,
    			]
    		);

        $this->add_control(
            'pgggo_exclude_current_post',
            [
                'label'        => __('Exclude current post', 'pgggo'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'pgggo'),
                'label_off'    => __('No', 'pgggo'),
                'description' => esc_html__('In case if you are using the plugin on single post then enable this to exclude the active single post from grid', 'pgggo'),
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $this->add_control(
            'pgggo_user_posts',
            [
                'label'        => __('Show only Current user posts', 'pgggo'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'pgggo'),
                'label_off'    => __('No', 'pgggo'),
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $this->add_control(
            'pgggo_post_grid_includer',
            [
                'label'        => __('Enable ACF Relation', 'pgggo'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'pgggo'),
                'label_off'    => __('No', 'pgggo'),
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $this->add_control(
            'pgggo_relation_type_set',
            [
                'label'     => esc_html__('Relation Type', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 'postobject',
                'options'   => [
                    'postobject'   => esc_html__('Post Object', 'pgggo'),
                    'relationship' => esc_html__('Relationship', 'pgggo'),
                    'user'         => esc_html__('User', 'pgggo'),
                ],
                'condition' => [
                    'pgggo_post_grid_includer' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'pgggo_post_grid_post_id_show_only',
            [
                'label'     => esc_html__('ACF - Relational Field Key', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'options'   => $this->get_acf_list(),
                'condition' => [
                    'pgggo_post_grid_includer' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'pgggo_cat_terms_select',
            [
                'label'    => esc_html__('Select Category Term', 'pgggo'),
                'type'     => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'label_block'=>true,
                'options'  => $pgggo_taxonomy_array,
            ]
        );
        $this->add_control(
            'pgggo_exclude_terms',
            [
                'label'    => esc_html__('Exclude Category Term', 'pgggo'),
                'type'     => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'label_block'=>true,
                'options'  => $pgggo_taxonomy_array,
            ]
        );

        $this->add_control(
            'pgggo_tag_terms_select',
            [
                'label'    => esc_html__('Select Tag Term', 'pgggo'),
                'type'     => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options'  => $pgggo_taxonomy_tag_array,
            ]
        );
        $this->add_control(
            'pgggo_exclude__tag_terms',
            [
                'label'    => esc_html__('Exclude Tag Term', 'pgggo'),
                'type'     => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'label_block'=>true,
                'options'  => $pgggo_taxonomy_tag_array,
            ]
        );

        $this->add_control(
            'enable_tax_query',
            [
                'label'        => esc_html__('Enable Tax Query', 'pgggo'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('No', 'pgggo'),
                'label_off'    => esc_html__('Yes', 'pgggo'),
                'return_value' => 'no',
                'default'      => '',
            ]
        );

        $this->add_control(
            'pgggo_tax_selector_relation',
            [
                'label'     => esc_html__('Select Relation', 'pgggo'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 'AND',
                'options'   => [
                    'AND' => esc_html__('AND', 'pgggo'),
                    'OR'  => esc_html__('OR', 'pgggo'),
                ],
                'condition' => [
                    'enable_tax_query' => 'no',
                ],
            ]
        );

        $pgggo_repeater = new \Elementor\Repeater();

        $pgggo_repeater->add_control(
            'taxonomy',
            [
                'label'   => esc_html__('Select Taxonomy', 'pgggo'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => $pgggo_taxonomy_repeat_tax,
            ]
        );

        $pgggo_repeater->add_control(
            'terms',
            [
                'label'    => esc_html__('Select Terms', 'pgggo'),
                'type'     => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'label_block'=>true,
                'options'  => $pgggo_taxonomy_repeat_term,
            ]
        );

        $this->add_control(
            'pgggo_repeater_term_chooser_field',
            [
                'label'       => esc_html__('Taxonomy List', 'pgggo'),
                'type'        => \Elementor\Controls_Manager::REPEATER,
                'fields'      => $pgggo_repeater->get_controls(),
                'title_field' => '{{{ taxonomy }}}',
                'condition'   => [
                    'enable_tax_query' => 'no',
                ],
            ]
        );

        $this->end_controls_section();

        // Author Control
        $this->start_controls_section(
            'pgggo_enable_debugging',
            [
                'label' => esc_html__('Debug', 'pgggo'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'pgggo_debug_activator',
            [
                'label'        => esc_html__('Debug Enaber', 'pgggo'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'pgggo'),
                'label_off'    => esc_html__('Hide', 'pgggo'),
                'description'  => esc_html__('If you notice any bug Geeky Green Owl request to add this to support', 'pgggo'),
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        $this->end_controls_section();

    }

    /**
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $paged_id = $this->get_id();
        $settings              = $this->get_settings_for_display();
        $config_data      = 'pgggoAjax'.$paged_id;
        $pagination_midsize    = $settings['post_count_midsize'];
        $pgggo_user_data = new \PGGGOCORENS\Pgggo();
          wp_enqueue_script('pgggo-ajax-jquery');
          wp_localize_script( 'pgggo-ajax-jquery', $config_data, array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( "pgggo_ajax_loader_nonce" ),
            'pgggosettings' => wp_json_encode($settings),
          ) );

        require plugin_dir_path( __FILE__ ) . '/render/pgggo-maincontent.php';
      }


}
