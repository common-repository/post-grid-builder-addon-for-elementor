<?php
if (! defined('ABSPATH')) {
    exit;
} // Exit if accessed directly
//Post Type
$pgggo_u_posttype         = $settings['posttype'];
$pgggo_u_poststatus       = $settings['post_type_status'];
$pgggo_u_postpagingenable = $settings['post_enable_pagination'];
$pgggo_u_paginate_num     = $settings['post_count'];
$pgggo_u_password         = $settings['password_protected'];


//Link Conditions
$pgggo_grid_click_acf        = $settings['make_block_clickable_makeit_acf'];
$pgggo_grid_click_acf_field_name   = $settings['make_block_clickable_makeit_acf_field'];
$pgggo_grid_click_acf_field_open   = $settings['make_block_clickable_makeit_acf_open_new_tab'];

//Ordering
$pgggo_u_order    = $settings['order'];
$pgggo_u_orderby  = $settings['orderby'];
$pgggo_u_meta_key = $settings['sortingbymetakey'];

$pgggo_u_meta_value   = $settings['sortingbymetavalue'];
$pgggo_u_meta_value   = $pgggo_user_data->pgggo_mata_value_gen($pgggo_u_meta_value);
$pgggo_u_meta_compare = $settings['meta_compare'];

//Author
$pgggo_u_authorid = $settings['oneauthor'];

//Category Arg Creator
$pgggo_u_catid        = $settings['pgggo_cat_terms_select'];
$pgggo_u_catidexclude = $settings['pgggo_exclude_terms'];
$pgggo_u_catid        = $pgggo_user_data->pgggo_catergory_id_generator($pgggo_u_catid, $pgggo_u_catidexclude);

//tag arg creator
$pgggo_u_tagid         = $settings['pgggo_tag_terms_select'];
$pgggo_u_tagid_exclude = $settings['pgggo_exclude__tag_terms'];

//taxonomy arg creator
$pgggo_u_tax_relation          = $settings['pgggo_tax_selector_relation'];
$pgggo_u_tax_array             = $settings['pgggo_repeater_term_chooser_field'];
$pgggo_u_tax_array["relation"] = $pgggo_u_tax_relation;

$pgggo_enable_taxquery = $settings['enable_tax_query'];

//the field generrator
$pgggo_field_gen = $settings['pgggo_field_gen'];
$pgggo_field_gen = $pgggo_user_data->pgggo_ren_func_repeater_to_array($pgggo_field_gen);

//Make clickable
$pgggo_make_clickable = $settings['make_block_clickable'];

//The paginatio parameters
$pgggo_enable_pagination_on_top    = $settings['pgggo_enable_pagination_on_top'];
$pgggo_enable_pagination_on_bottom = $settings['pgggo_enable_pagination_on_bottom'];

//Background parameters
$pgggo_b_enable               = $settings['pgggo_make_featured_image_as_background'];
$pgggo_b_img_type_size        = $settings['pgggo_back_featured_image_size'];
$pgggo_b_top_grad             = $settings['pgggo_b_top_grad'];
$pgggo_bottom_grad            = $settings['pgggo_b_bottom_grad'];
$pgggo_b_cover                = $settings['pgggo_b_cover'];
$pgggo_b_repeat               = $settings['pgggo_b_repeat'];
$post_enable_pagination_fixed = $settings['post_enable_pagination_fixed'];
$pgggo_b_position             = $settings['pgggo_b_position'];
$pgggo_allowed                = $pgggo_user_data->pgggo_html_allowed_html();
$pgggo_enable_ajax_loadmore   = $settings['pgggo_enable_ajax_loadmore'];
$pgggo_enable_ajax_sorting    = $settings['pgggo_enable_sorter_ajax'];
$pgggo_u_date = $settings['meta_compare_type'];

//current user
$pgggo_u_enable_current_user =  $settings['pgggo_user_posts'];
$pgggo_u_exclude_current_post = $settings['pgggo_exclude_current_post'];

$pgggo_u_offset = $settings['pgggo_enable_offset'];

$pgggo_args = $pgggo_user_data->pgggo_args_generator(
    $pgggo_u_posttype,
    $pgggo_u_poststatus,
    $pgggo_u_postpagingenable,
    $pgggo_u_paginate_num,
    $pgggo_u_password,
    $pgggo_u_order,
    $pgggo_u_orderby,
    $pgggo_u_meta_key,
    $pgggo_u_meta_value,
    $pgggo_u_meta_compare,
    $pgggo_u_authorid,
    $pgggo_u_catid,
    $pgggo_u_tagid,
    $pgggo_u_tagid_exclude,
    $pgggo_u_tax_array,
    $paged_id,
    $pgggo_u_date,
    $pgggo_u_enable_current_user,
    $pgggo_u_exclude_current_post,
    $pgggo_u_offset
);

if ($pgggo_u_orderby == 'meta_value_num') {
    unset($pgggo_args['meta_query']);
} elseif ($pgggo_u_orderby != 'meta_value') {
    unset($pgggo_args['meta_query']);
    unset($pgggo_args['meta_key']);
}
if ($pgggo_enable_taxquery != 'no') {
    unset($pgggo_args['tax_query']);
}
if ($post_enable_pagination_fixed == 'true') {
    unset($pgggo_args['nopaging']);
}
$pgggo_args = array_filter($pgggo_args);

//Adding relational Field Support
$pgggo_enable_relational = $settings['pgggo_post_grid_includer'];
if ($pgggo_enable_relational == 'yes') {
    $pgggo_args = $pgggo_user_data->pgggo_acf_realational_field_support($pgggo_args, $settings);
}

$pgggo_debug = $settings['pgggo_debug_activator'];
if ($pgggo_debug == 'yes'):
    echo '<div class="pgggo_shortcode">';
    echo "<pre>";
    print_r($pgggo_field_gen);
    echo "</pre>";
    echo "<br>";
    echo "<hr>";
    echo "<pre>";
    print_r($pgggo_args);
    echo "</pre>";
    echo "<br>";
    echo '</div>';
endif;

//sort and filter module
if ($settings['pgggo_enable_sorter'] == 'yes'):

    //check 1 - Sort
    $pgggo_acend_var = (get_query_var('pgggo-sort-acend')) ? get_query_var('pgggo-sort-acend') : "";
    if (!empty($pgggo_acend_var) && $pgggo_acend_var == 'on') {
        if (array_key_exists('order', $pgggo_args)) {
            unset($pgggo_args['order']);
        }
        $pgggo_args['order'] = 'ASC';
    }
    //check 2 - Sort
    $pgggo_decend_var = (get_query_var('pgggo-sort-decend')) ? get_query_var('pgggo-sort-decend') : "";
    if (!empty($pgggo_decend_var) && $pgggo_decend_var == 'on') {
        if (array_key_exists('order', $pgggo_args)) {
            unset($pgggo_args['order']);
        }
        $pgggo_args['order'] = 'DESC';
    }

    //selection
    $pgggo_selection_var_select = $pgggo_user_data->pgggo_generator_select($settings, $pgggo_user_data)['select_array'];

    $pgggo_selection_var_list = $pgggo_user_data->pgggo_generator_select($settings, $pgggo_user_data)['list_array'];

    if (!empty($pgggo_selection_var_select)) {
        if (array_key_exists('tax_query', $pgggo_args)) {
            unset($pgggo_args['tax_query']);
        }
        $pgggo_args['tax_query'] = $pgggo_selection_var_select;
    }

    if (!empty($pgggo_selection_var_list)) {
        if (array_key_exists('tax_query', $pgggo_args)) {
            unset($pgggo_args['tax_query']);
        }
        $pgggo_args['tax_query'] = $pgggo_selection_var_list;
    }

    //form
    $pgggo_user_data->pgggo_sort_filer_form_gen($settings, $pgggo_user_data, $pgggo_args, $pgggo_acend_var, $pgggo_decend_var, $pgggo_enable_ajax_sorting);

endif;

$pgggo_user_data = new \PGGGOCORENS\Pgggo();
$pgggo_query = new \WP_Query($pgggo_args);
?>

<div data-ppggoargs="{}" data-pgggopagenum="1" id="pgggo-container" data-pgggoeleid="<?php echo $paged_id; ?>" class="pgggp-container-main pgggo-container <?php if ($settings['pgggo_enable_ajax_pagination'] == 'yes') {
    echo 'pgggo-container-ajax';
} if ($pgggo_enable_ajax_loadmore == 'yes') {
    echo ' pgggo-container-ajax-loadmore';
}  ?>">
  <?php do_action('pgggo_action_before_top_pagination_top_hook', 'pgggo_action_before_top_pagination');?>

  <?php if ($pgggo_enable_pagination_on_top == 'yes'): ?>
  <div id="pgggo-pagination-top" class="pgggo-pagination pgggo-pagination-top" >
              <?php echo paginate_links($pgggo_user_data->pgggo_paginate_args_new_v1($pgggo_query, $paged_id, $pagination_midsize)); ?>

  </div>
  <?php endif;?>

  <?php do_action('pgggo_action_after_top_pagination_hook_act', 'pgggo_action_after_top_pagination');?>

  <?php if (class_exists('WooCommerce')):
    echo '<div class="pgggo-wooocommerc-notices ">';
    if (function_exists('wc_print_notices')) {
        wc_print_notices();
    }
    echo '</div>';
endif;
?>

      <div id="pgggo-row pgggo-row-spec-<?php echo intval(get_queried_object_id()); ?>" class="pgggo-row pgggo-row-mob-<?php echo intval($settings['pgggo_grid_column_mobile']); ?> pgggo-row-tab-<?php echo intval($settings['pgggo_grid_column_tablet']); ?> pgggo-row-desk-<?php echo intval($settings['pgggo_grid_column_dektop']); ?>">

        <?php if ($pgggo_query->have_posts()): ?>

        <?php while ($pgggo_query->have_posts()): $pgggo_query->the_post(); ?>
                <div id="pgggo-card-design-outer pgggo-grid-outer-<?php echo intval(get_the_id()); ?>" class="pgggo-card-design-outer pgggo-grid-outer-<?php echo intval(get_the_id()); ?>">
                  <div id="pgggo-card-design pgggo-grid-<?php echo intval(get_the_id()); ?>" class="pgggo-card-design pgggo-grid-<?php echo intval(get_the_id()); ?>"
                      <?php
                      if ($pgggo_b_enable == 'yes'):
                        $pgggo_b_img_id = get_the_ID();
                        $pgggo_b_back   = $pgggo_user_data->pgggo_background_image_genenrator($pgggo_b_img_id, $pgggo_b_enable, $pgggo_b_img_type_size, $pgggo_b_cover, $pgggo_b_repeat, $pgggo_b_top_grad, $pgggo_bottom_grad, $pgggo_b_position);
                        echo wp_kses($pgggo_b_back, $pgggo_allowed);
                      endif;
                      ?>>
                        <div id="pgggo-card-design-mul" class="pgggo-card-design-mul">
                          <div id="pgggo-card-design-mul-inner pgggo-card-design-mul-inner-col-1" class="pgggo-card-design-mul-inner pgggo-card-design-mul-inner-col-1" >
                          <?php $pgggo_user_data->pgggo_core_grid_loader($pgggo_field_gen, $pgggo_make_clickable, $pgggo_allowed, $pgggo_grid_click_acf, $pgggo_grid_click_acf_field_name, $pgggo_grid_click_acf_field_open); ?>
                          </div>
                          <?php
                          $pgggo_enable_multi = $settings['pgggo_layout'];
                            if ($pgggo_enable_multi == 'grid'):
                              ?>
                          <div id="pgggo-card-design-mul-inner pgggo-card-design-mul-inner-col-2" class="pgggo-card-design-mul-inner pgggo-card-design-mul-inner-col-2">
                          <?php
                            $pgggo_field_gen2 = $settings['pgggo_field_gen2'];
                            $pgggo_field_gen2 = $pgggo_user_data->pgggo_ren_func_repeater_to_array($pgggo_field_gen2);
                            $pgggo_user_data->pgggo_core_grid_loader($pgggo_field_gen2, $pgggo_make_clickable, $pgggo_allowed, $pgggo_grid_click_acf, $pgggo_grid_click_acf_field_name, $pgggo_grid_click_acf_field_open);
                          ?>
                          </div>
                        <?php endif;?>
                      </div>
                </div><!-- pgggo-card-design  Ends-->
              </div>

                <?php endwhile;?>
          <!-- end of the loop -->

           <!-- pagination here -->

       <?php wp_reset_postdata();?>
<?php else: ?>
        <p><?php
      esc_html_e('Sorry, no posts matched your criteria', 'pgggo');
?></p>
<?php endif;?>
</div>

<?php do_action('pgggo_action_before_top_pagination_bottom_hook', 'pgggo_action_before_bottom_pagination');?>

<?php if ($pgggo_enable_pagination_on_bottom == 'yes'): ?>
<div id="pgggo-pagination-bottom" class="pgggo-pagination pgggo-pagination-bottom">
    <?php echo paginate_links($pgggo_user_data->pgggo_paginate_args_new_v1($pgggo_query, $paged_id, $pagination_midsize)); ?>
</div>
<?php endif;?>

<?php if ($pgggo_enable_ajax_loadmore == 'yes'): ?>
<div id="pgggo-loadmore-button" class="pgggo-loadmore-button">
   <div class="pgggo-loadmore-button-ajax" type="button" name="pgggobuttonload"><?php echo esc_html_e($settings['pgggo_filter_button_text_loadmore']); ?></div>
</div>
<?php endif;?>

<?php do_action('pgggo_action_before_top_pagination_bottom_hook', 'pgggo_action_before_bottom_pagination');?>

</div>
<?php
