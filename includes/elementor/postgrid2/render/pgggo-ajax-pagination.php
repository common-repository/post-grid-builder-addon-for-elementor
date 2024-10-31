<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//Post Type
$pgggo_u_posttype         = $settings['posttype'];
$pgggo_u_poststatus       = $settings['post_type_status'];
$pgggo_u_postpagingenable = $settings['post_enable_pagination'];
$pgggo_u_paginate_num     = $settings['post_count'];
$pgggo_u_password         = $settings['password_protected'];

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
//$pgggo_u_tax_array["relation"] = $pgggo_u_tax_relation;

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
$pgggo_enable_ajax_sorting    = $settings['pgggo_enable_sorter_ajax'];
$pgggo_enable_ajax_loadmore   = $settings['pgggo_enable_ajax_loadmore'];

$pgggo_u_date = $settings['meta_compare_type'];


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
    $pgggo_u_date
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
if($_POST['pgggopage']){
  unset($pgggo_args['paged']);
  $pgggo_args['paged'] = $_POST['pgggopage'];
}

$pgggo_args = array_filter($pgggo_args);

//Adding relational Field Support
$pgggo_enable_relational = $settings['pgggo_post_grid_includer'];
if ($pgggo_enable_relational == 'yes') {
  $pgggo_args =  $pgggo_user_data->pgggo_acf_realational_field_support($pgggo_args, $settings);
}

if ($settings['pgggo_enable_sorter'] == 'yes'):

    //check 1 - Sort
    $pgggo_acend_var = $pagesortorderaccend;
    if (!empty($pgggo_acend_var) && $pgggo_acend_var == 'on') {
        if (array_key_exists('order', $pgggo_args)) {
            unset($pgggo_args['order']);
        }
        $pgggo_args['order'] = 'ASC';
    }
    //check 2 - Sort
    $pgggo_decend_var = $pagesortorderdecnet;
    if (!empty($pgggo_decend_var) && $pgggo_decend_var == 'on') {
        if (array_key_exists('order', $pgggo_args)) {
            unset($pgggo_args['order']);
        }
        $pgggo_args['order'] = 'DESC';
    }

    //selection

    $pgggo_selection_ajax_array_sel = array();
    if(!empty($pagetaxondataselect) && is_array($pagetaxondataselect) ){
      foreach ($pagetaxondataselect as $keypaga => $valuepage) {
      $pgggo_selection_ajax_array_sel[] =array(
        'taxonomy' => $keypaga,
         'field' => 'term_id',
         'terms' => $valuepage,
      );
    }}

    $pgggo_selection_var_select = $pgggo_selection_ajax_array_sel;

    $pgggo_selection_ajax_array = array();
    if(!empty($pagetaxondata) && is_array($pagetaxondata) ){
      foreach ($pagetaxondata as $keypaga => $valuepage) {
      $pgggo_selection_ajax_array[] =array(
        'taxonomy' => $keypaga,
         'field' => 'term_id',
         'terms' => $valuepage,
      );
    }}
    $pgggo_selection_var_list = $pgggo_selection_ajax_array;

    if (!empty($pgggo_selection_var_select)) {
        if (array_key_exists('tax_query', $pgggo_args)) {
            array_merge($pgggo_args['tax_query'],$pgggo_selection_var_select);
        }else{
          $pgggo_args['tax_query'] = $pgggo_selection_var_select;
        }

    }

    if (!empty($pgggo_selection_var_list)) {
        if (array_key_exists('tax_query', $pgggo_args)) {
            array_merge($pgggo_args['tax_query'],$pgggo_selection_var_list);
        }else{
          $pgggo_args['tax_query'] = $pgggo_selection_var_list;
        }

    }

endif;


if(!empty($pggo_args_temp) && is_array($pggo_args_temp)){
  $pgggo_args =$pggo_args_temp;
}

if(!empty($pagenumber)){
  if (array_key_exists('paged', $pgggo_args)) {
      unset($pgggo_args['paged']);
  }
  $pgggo_args['paged'] = (int)$pagenumber;
}

$pgggo_query = new \WP_Query($pgggo_args);?>

  <?php do_action('pgggo_action_before_top_pagination_top_hook', 'pgggo_action_before_top_pagination');?>


  <?php do_action('pgggo_action_after_top_pagination_hook_act', 'pgggo_action_after_top_pagination');?>

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
        <script>alert("<?php echo esc_html__($settings['pgggo_filter_all_loaded']); ?>");</script>
        <style>.pgggo-loadmore-button-ajax{display:none;}</style>
<?php endif;?>

<?php
