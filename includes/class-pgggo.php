<?php
namespace PGGGOCORENS {

  if (!defined('ABSPATH')) {
      exit;
  }

  class Pgggo
  {
      protected $pgggo_version;

      public function __construct()
      {
          if (defined('PGGGO_VERSION')) {
              $this->pgggo_version = PGGGO_VERSION;
          } else {
              $this->pgggo_version = '1.0.0';
          }
      }

      /*
      ID: PGGGO-1
      Since ver 1.0.0
      Modified By : Latheesh V M Villa
      Purpose of the function/class : Returns list of post types
      */
      public function pgggo_list_of_posttypes()
      {
          $args = array(
        'public' => true,
      );

          $output   = 'names'; // 'names' or 'objects' (default: 'names')
      $operator = 'and'; // 'and' or 'or' (default: 'and')

      $post_types = get_post_types($args, $output, $operator);
          $post_types = array_map('ucfirst', $post_types);
          return $post_types;
      }

      /*
      ID: PGGGO-2
      Since ver 1.0.0
      Modified By : Latheesh V M Villa
      Purpose of the function/class : Returns list of post Taxonomy
      */
      public function pgggo_list_of_taxonomy()
      {
          $args = array(
        'public'   => true,
        '_builtin' => true,
      );
          $tax_list = get_taxonomies($args);
          return $tax_list;
      }

      /*
      ID: PGGGO-3
      Since ver 1.0.0
      Modified By : Latheesh V M Villa
      Purpose of the function/class : Generates the list of all taxonomy - Used in taxonomy
      */
      public function pgggo_list_of_taxonomy_repeater_field()
      {
          $tax_list = get_taxonomies();
          return $tax_list;
      }

      /*
      ID: PGGGO-4
      Since ver 5.1.1
      Modified By : Latheesh V M Villa
      Purpose of the function/class : Generates the list of terms for the all the category
      */
      public function pgggo_list_of_terms()
      {
          //$tax = $this->pgggo_list_of_taxonomy();
          $terms = get_terms(array('taxonomy' => 'category'));
          $lat = array();
          foreach ($terms as $key => $term) {
              $lat[$term->term_taxonomy_id] = $term->name;
          }
          return $lat;
      }

      /*
      ID: PGGGO-5
      Since ver 5.1.1
      Modified By : Latheesh V M Villa
      Purpose of the function/class : Generates the list of terms for the all the category - Used in repeater
      */
      public function pgggo_list_of_terms_repeater_field()
      {
          $tax   = $this->pgggo_list_of_taxonomy_repeater_field();
          $terms = get_terms(array('taxonomy' => $tax));
          $lat = array();
          foreach ($terms as $key => $term) {
              $lat[$term->term_taxonomy_id] = $term->name;
          }
          return $lat;
      }

      /*
      ID: PGGGO-6
      Since ver 5.0.0
      Modified By : Latheesh V M Villa
      Purpose of the function/class :Generates the list of terms for the all the the_terms
      */
      public function pgggo_list_of_tags()
      {
          $tax   = $this->pgggo_list_of_taxonomy();
          $terms = get_tags();
          $lat2 = array();
          foreach ($terms as $key => $term) {
              $lat2[$term->term_taxonomy_id] = $term->name;
          }
          return $lat2;
      }

      /*
      ID: PGGGO-7
      Since ver 1.0.0
      Modified By : Latheesh V M Villa
      Purpose of the function/class :  convert the meta value to array if comma exits other wise string
      */
      public function pgggo_mata_value_gen($pgggo_u_meta_value)
      {
          $searchString = ',';
          if (strpos($pgggo_u_meta_value, $searchString) !== false) {
              $pgggo_u_meta_value = explode(",", $pgggo_u_meta_value);
          } else {
              $pgggo_u_meta_value = $pgggo_u_meta_value;
          }
          return $pgggo_u_meta_value;
      }



      /*
      ID: PGGGO-8
      Since ver 1.0.0
      Modified By : Latheesh V M Villa
      Purpose of the function/class :  Collects ids of all the post types, then getpost to collect the IDs, Then returns the key array
      */
      public function pgggo_custom_post_type_keylist()
      {
          $pgggo_custompostype_array = $this->pgggo_list_of_posttypes();

          $pgggo_getposts            = array();
          foreach ($pgggo_custompostype_array as $value) {
              $args = array(
          'numberposts' => 1,
          'post_type'   => $value,
        );
              if (!empty(get_posts($args)[0]->ID)) {
                  $pgggo_getposts[] = get_posts($args)[0]->ID;
              }
          }
          $new_array = array();
          foreach ($pgggo_getposts as $value) {
              $new_array[] = get_post_custom($value);
          }

          $result = array();
          foreach ($new_array as $sub) {
              $result = array_merge($result, $sub);
          }
          $result     = array_keys($result);
          $result     = array_unique($result);
          $result     = array_combine($result, $result);
          $result[''] = 'NONE';
          return $result;
      }

      /*
      ID: PGGGO-8
      Since ver 1.0.0
      Modified By : Latheesh V M Villa
      Purpose of the function/class :  Collects ids of all the post types, then getpost to collect the IDs, Then returns the key array
      */
      public function pgggo_catergory_id_generator($pgggo_u_catid, $pgggo_u_catidexclude)
      {
          if (is_array($pgggo_u_catid)) {
              $pgggo_u_catid = implode(", ", $pgggo_u_catid);
          } else {
              $pgggo_u_catid = '';
          }
          $pgggo_u_catidexcluder = array();
          if (is_array($pgggo_u_catidexclude)) {
              $pgggo_u_catidexclude = array_map('intval', $pgggo_u_catidexclude);

              foreach ($pgggo_u_catidexclude as $value) {
                  $pgggo_u_catidexcluder[] = -1 * $value;
              }
          }
          if (is_array($pgggo_u_catidexcluder)) {
              $pgggo_u_catidexcluder = implode(",", $pgggo_u_catidexcluder);
          } else {
              $pgggo_u_catidexcluder = '';
          }

          if ($pgggo_u_catidexcluder != '') {
              $pgggo_cat_exculution = ',' . $pgggo_u_catidexcluder;
          } else {
              $pgggo_cat_exculution = $pgggo_u_catidexcluder;
          }
          $pgggo_u_catid2 = $pgggo_u_catid . $pgggo_cat_exculution;
          return $pgggo_u_catid2;
      }

      /*
      ID: PGGGO-9
      Since ver 1.0.0
      Modified By : Latheesh V M Villa
      Purpose of the function/class :  Get the post status as list
      */
      public function pgggo_list_of_post_status()
      {
          $post_stat = get_post_stati();
          return $post_stat;
      }

      /*
      ID: PGGGO-9
      Since ver 1.0.0
      Modified By : Latheesh V M Villa
      Purpose of the function/class :  This is the main argument generator for the wp query
      */
      public function pgggo_args_generator(
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
          $paged_id="",
          $pgggo_u_date="",
          $pgggo_u_user_post ="",
          $pgggo_u_exclude_current_post = "",
          $pgggo_u_offset = ""
      ) {


        $paged_query_var = $paged_id.'-paged';
        $paged   = max(1, (int) filter_input(INPUT_GET, $paged_query_var));

        if($pgggo_u_date == 'DATE'){
          $pgggo_u_meta_value = date('Y-m-d');
        }

        $arg = array(
        'post_type'      => $pgggo_u_posttype,
        'post_status'    => $pgggo_u_poststatus, //post_type_status
        'paged'          => $paged,
        'nopaging'       => $pgggo_u_postpagingenable, //post_enable_pagination
        'posts_per_page' => $pgggo_u_paginate_num, //post_count
        'has_password'   => $pgggo_u_password, //password_protected
        'order'          => $pgggo_u_order, //order
        'orderby'        => $pgggo_u_orderby, //orderby
        'meta_query'     => array(
          array(
            'key'     => $pgggo_u_meta_key,
            'value'   => $pgggo_u_meta_value,
            'compare' => $pgggo_u_meta_compare, //meta_compare
            'type'    => $pgggo_u_date,
          ),
        ),
        'meta_key'       => $pgggo_u_meta_key, //sortingbymetakey
        'author'         => $pgggo_u_authorid,
        'cat'            => $pgggo_u_catid,
        'tag__in'        => $pgggo_u_tagid,
        'tag__not_in'    => $pgggo_u_tagid_exclude,
        'tax_query'      => $pgggo_u_tax_array,
        'offset'         => $pgggo_u_offset,
      );

      if(!empty($pgggo_u_user_post)){
        if($pgggo_u_user_post == 'yes'){
          $arg['author'] = get_current_user_id();
        }
      }

      if(!empty($pgggo_u_exclude_current_post)){
        if($pgggo_u_exclude_current_post == 'yes'){
          $arg['post__not_in'] = array(get_the_id());
        }
      }

          return apply_filters('pgggo_args_generator_hook', $arg);
      }

      /*
      ID: PGGGO-10
      Apply filter provided - PGGGO AP1
      Since ver 1.0.0
      Modified By : Latheesh V M Villa
      Purpose of the function/class :  Renders the title of the post safer
      */
      public function pgggo_ren_title()
      {
          $title = the_title_attribute('echo=0');
          return apply_filters('pgggo_ren_title_hook', $title);
      }

      /*
      ID: PGGGO-11
      Since ver 1.0.0
      Modified By : Latheesh V M Villa
      Purpose of the function/class :  This returns the url of the image when used
      */
      public function pgggo_ren_image($adv_settings, $pgggo_img_type, $pgggo_img_id, $pgggo_img_type_size='', $pgggo_img_custom)
      {
          switch ($pgggo_img_type) {
        case "featured":
        $imageurl_before = get_post_thumbnail_id($pgggo_img_id);
        $imageurl_url = wp_get_attachment_url($imageurl_before);
        $extra= array(
          'url'=> $imageurl_url,
          'id'=> $imageurl_before,
        );
        if ($adv_settings == 'old') {
            $imageurl = get_the_post_thumbnail_url($pgggo_img_id, $pgggo_img_type_size);
        } else {
            $imageurl =  \Elementor\Pgggo_Featurred_Image_Getter::get_attachment_image_html_generator($imageurl_before, $adv_settings, $image_size_key = 'image', $image_key = null, $extra);
            if (empty($imageurl)) {
                $imageurl_before = $adv_settings['image']['id'];
                if (!empty($imageurl_before)) {
                    $imageurl =  \Elementor\Pgggo_Featurred_Image_Getter::get_attachment_image_html_generator($imageurl_before, $adv_settings, $image_size_key = 'image', $image_key = null, $extra);
                }
            }
        }
        return $imageurl;
        break;
        case "acf_image":
        if(class_exists('ACF')):
        $imageurl_before = get_field($pgggo_img_custom, $pgggo_img_id);
        $imageurl_url = wp_get_attachment_url($imageurl_before);
        $extra= array(
          'url'=> $imageurl_url,
          'id'=> $imageurl_before,
        );
        if ($adv_settings == 'old') {
            $imageurl = get_the_post_thumbnail_url($pgggo_img_id, $pgggo_img_type_size);
        } else {
            $imageurl =  \Elementor\Pgggo_Featurred_Image_Getter::get_attachment_image_html_generator($imageurl_before, $adv_settings, $image_size_key = 'image', $image_key = null, $extra);
            if (empty($imageurl)) {
                $imageurl_before = $adv_settings['image']['id'];
                if (!empty($imageurl_before)) {
                    $imageurl =  \Elementor\Pgggo_Featurred_Image_Getter::get_attachment_image_html_generator($imageurl_before, $adv_settings, $image_size_key = 'image', $image_key = null, $extra);
                }
            }
        }
        return $imageurl;
      endif;
        break;

        case "custom_field":
        $imageurl_cus = get_post_meta($pgggo_img_id, $pgggo_img_custom, true);
        $imageurl_cus = wp_get_attachment_image_src($imageurl_cus, $pgggo_img_type_size);
        return $imageurl_cus;
        break;
        case "woocommerce_image":
          if(class_exists('ACF')):
        $imageurl_cus = wp_get_attachment_image_src(get_post_thumbnail_id($pgggo_img_id), $pgggo_img_type_size);
        $imageurl_cus = $imageurl_cus[0];
        return $imageurl_cus;
          endif;
        break;
        default:
        $imageurl = get_the_post_thumbnail_url($pgggo_img_id, $pgggo_img_type_size);
        return $imageurl;

      }
      }

      /*
      ID: PGGGO-12
      Since ver 1.0.0
      Modified By : Latheesh V M Villa
      Purpose of the function/class :  This decides the content for the content selector
      */
      public function pgggo_ren_content($pgggo_cont_id, $pgggo_r_content_type, $pgggo_r_enable_excerpt, $pgggo_excerpt_len, $pgggo_r_fieldname)
      {
          switch ($pgggo_r_content_type) {
        case "editor":
        $content = get_the_content();
        if ($pgggo_r_enable_excerpt == 'yes') {
            $content = wp_trim_words($content, $pgggo_excerpt_len, '...');
            return $content;
        } else {
            return $content;
        }
        break;
        case "customfield_acf":
        $content = get_field($pgggo_r_fieldname);
        if (is_object($content) || is_array($content)) {
            return "Check Field Type. Field Type Returns an Object or Array";
        }
        if ($pgggo_r_enable_excerpt == 'yes') {
            $content = wp_trim_words($content, $pgggo_excerpt_len, '...');
            return $content;
        } else {
            return $content;
        }
        break;
        case "excerpt":
        $content = get_the_excerpt();
        if ($pgggo_r_enable_excerpt == 'yes') {
            $content = wp_trim_words($content, $pgggo_excerpt_len, '...');
            return $content;
        } else {
            return $content;
        }
        return $content;
        case 'customfield':
        $post_id = $pgggo_cont_id;
        $key     = $pgggo_r_fieldname;
        $single  = true;

        $content = get_post_meta($post_id, $key, $single);
        if (is_array($content)) {
            return " ";
        }
        if ($pgggo_r_enable_excerpt == 'yes') {
            $content = wp_trim_words($content, $pgggo_excerpt_len, '...');
            return $content;
        } else {
            return $content;
        }
        break;

        default:
        $content = the_content();
        if ($pgggo_r_enable_excerpt == 'yes') {
            $content = wp_trim_words($content, $pgggo_excerpt_len, '...');
            return $content;
        } else {
            return $content;
        }

      }
      }

      /*
      ID: PGGGO-13
      Since ver 1.0.0
      Modified By : Latheesh V M Villa
      Purpose of the function/class :  This decides the content for the button selector
      */
      public function pgggo_ren_button($pgggo_r_id, $pgggo_r_buttonfieldtype, $pgggo_r_customfieldname)
      {
          switch ($pgggo_r_buttonfieldtype) {
        case "linktopost":
        $url = get_permalink();
        return $url;
        break;
        case "customfieldurl":
        $post_id = $pgggo_r_id;
        $key     = $pgggo_r_customfieldname;
        $single  = true;
        $url     = get_post_meta($post_id, $key, $single);
        return $url;
        break;
        case "customfieldurlacfdonwload":
        $url = get_field($pgggo_r_customfieldname);
        return $url['url'];
        break;
        case "customfieldurlacf":
        $url = get_field($pgggo_r_customfieldname);
        return $url;
        break;
        default:
        $url = get_permalink();
        return $url;
      }
      }

      /*
      ID: PGGGO-14
      Since ver 1.1.0
      Modified By : Latheesh V M Villa
      Purpose of the function/class :  This decides the content for the metadata selector
      */
      public function pgggo_ren_metadata($pgggo_m_id, $pgggo_m_en_date, $pgggo_m_en_tags, $pgggo_m_en_tax, $pgggo_m_en_auth, $pgggo_m_en_comm, $pgggo_m_taxvalue)
      {
          if ($pgggo_m_en_date == 'yes') {
              $pgggo_date = get_the_date();
          } else {
              $pgggo_date = '';
          }

          //check for woocommerce product type is introduced  verion 1.1.0
          if (($pgggo_m_en_tags == 'yes') && (get_post_type($pgggo_m_id) == 'product')) {
              $pgggo_tag = get_the_term_list($pgggo_m_id, 'product_tag', '', ', ');
          } elseif ($pgggo_m_en_tags == 'yes') {
              $pgggo_tag = get_the_tag_list("", $sep = ", ");
          } else {
              $pgggo_tag = '';
          }

          //check for woocommerce product type is introduced  versio 1.1.0
          if (($pgggo_m_en_tax == 'yes') && (get_post_type($pgggo_m_id) == 'product')) {
              $pgggo_tax = get_the_term_list($pgggo_m_id, 'product_cat', '', ', ');
          } elseif ($pgggo_m_en_tax == 'yes') {
              $pgggo_tax = get_the_term_list($pgggo_m_id, $pgggo_m_taxvalue, '', ', ');
          } else {
              $pgggo_tax = '';
          }

          if ($pgggo_m_en_auth == 'yes') {
              $pgggo_auth = get_the_author_meta('display_name');
          } else {
              $pgggo_auth = '';
          }
          if ($pgggo_m_en_comm == 'yes') {
              $pgggo_comm = get_comments_number();
          } else {
              $pgggo_comm = '';
          }

          $pgggo_met_array = array(
        'date' => esc_html(ucwords($pgggo_date)),
        'tag'  => esc_html__('Tags&nbsp;:&nbsp;', 'pgggo') . $pgggo_tag,
        'tax'  => ucwords($pgggo_m_taxvalue) . '&nbsp;:&nbsp;' . $pgggo_tax,
        'auth' => esc_html__('By', 'pgggo').'&nbsp;'. $pgggo_auth,
        'com'  => $pgggo_comm . esc_html__('&nbsp;Comments', 'pgggo'),
      );

          if (empty($pgggo_tag)) {
              unset($pgggo_met_array['tag']);
          }

          if (empty($pgggo_tax)) {
              unset($pgggo_met_array['tax']);
          }

          if (empty($pgggo_auth)) {
              unset($pgggo_met_array['auth']);
          }
          if (empty($pgggo_comm)) {
              unset($pgggo_met_array['com']);
          }

          $pgggo_met_array = array_filter($pgggo_met_array);
          return $pgggo_met_array;
      }

      /*
      ID: PGGGO-15
      Apply filter provided -PGGGO AP2
      Since ver 1.0.0
      Modified By : Latheesh V M Villa
      Purpose of the function/class :  This decides the content for the shortcode selector
      */
      public function pgggo_ren_shortcode($pgggo_shortcode)
      {
          $input = $pgggo_shortcode;
          return apply_filters('pgggo_ren_shortcode_hook', $input);
      }

      /*
      ID: PGGGO-16
      Since ver 1.0.0
      Modified By : Latheesh V M Villa
      Purpose of the function/class :  Function modifed for repeater
      */
      public function pgggo_ren_func_repeater_to_array($pgggo_field_gen)
      {
          $valuescs = $pgggo_field_gen;
          return $valuescs;
      }

      /*
      ID: PGGGO-17
      Since ver 1.0.0
      Modified By : Latheesh V M Villa
      Purpose of the function/class :  Converts the image size array to elemenetor selector array
      */
      public function pgggo_ren_func_array_of_image_size()
      {
          $result = get_intermediate_image_sizes();
          $result = array_unique($result);
          $result = array_combine($result, $result);
          return $result;
      }

      /*
      ID: PGGGO-18
      Apply filter provided - PGGGO AP3
      Since ver 6.0.0
      Modified By : Latheesh V M Villa
      Purpose of the function/class :  Pagination arguments
      */
      public function pgggo_paginate_args($pgggo_query)
      {
          $paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;
          $paged = apply_filters('pgggo_paginate_args_paged_hook', $paged);
          $args  = array(
            'format'    => '?paged=%#%',
            'current'   => intval($paged),
            'total'     => intval($pgggo_query->max_num_pages),
            'mid_size'  => 3,
            'prev_text' => '<i class="fa fa-arrow-left"></i>',
            'next_text' => '<i class="fa fa-arrow-right"></i>',
          );
          return apply_filters('pgggo_paginate_args_hook', $args);
      }


      public function pgggo_paginate_args_new_v1($pgggo_query, $paged_id, $pagination_midsize)
      {
          $paged_query_var = $paged_id.'-paged';
          $format = '?'.$paged_id.'-paged=%#%';
          $page_current   = max(1, (int) filter_input(INPUT_GET, $paged_query_var));
          $paged_bottom_args  = array(
             'format'    => $format,
             'current'   => intval($page_current),
             'total'     => intval($pgggo_query->max_num_pages),
             'mid_size'  => $pagination_midsize,
             'prev_text' => '<i class="fa fa-arrow-left"></i>',
             'next_text' => '<i class="fa fa-arrow-right"></i>',
           );
          return apply_filters('pgggo_paginate_args_hook_v1', $paged_bottom_args);
      }

      /*
      ID: PGGGO-19
      Since ver 1.0.0
      Modified By : Latheesh V M Villa
      Purpose of the function/class :  background image generator for row;
      */
      public function pgggo_background_image_genenrator($pgggo_b_img_id, $pgggo_b_enable, $pgggo_b_img_type_size, $pgggo_b_cover, $pgggo_b_repeat, $pgggo_b_top_grad, $pgggo_bottom_grad, $pgggo_b_position)
      {
          $featured_image_url = get_the_post_thumbnail_url($pgggo_b_img_id, $pgggo_b_img_type_size);

          return 'style="background: linear-gradient(' . esc_attr($pgggo_b_top_grad) . ',' . esc_attr($pgggo_bottom_grad) . '),url(' . esc_url($featured_image_url) . '); background-size:cover; background-repeat:no-repeat; background-position:' . esc_html($pgggo_b_position) . ' "';
      }

      /*
      ID: PGGGO-20
      Since ver 1.0.0
      Modified By : Latheesh V M Villa
      Purpose of the function/class :  Security for the html
      */
      public function pgggo_html_allowed_html()
      {
          $allowed_tags = array(
        '&nbsp;'     => array(),
        'a'          => array(
          'class' => array(),
          'href'  => array(),
          'rel'   => array(),
          'title' => array(),
        ),
        'abbr'       => array(
          'title' => array(),
        ),
        'b'          => array(),
        'blockquote' => array(
          'cite' => array(),
        ),
        'cite'       => array(
          'title' => array(),
        ),
        'code'       => array(),
        'del'        => array(
          'datetime' => array(),
          'title'    => array(),
        ),
        'dd'         => array(),
        'div'        => array(
          'class' => array(),
          'title' => array(),
          'style' => array(),
        ),
        'dl'         => array(),
        'dt'         => array(),
        'em'         => array(),
        'h1'         => array(),
        'h2'         => array(),
        'h3'         => array(),
        'h4'         => array(),
        'h5'         => array(),
        'h6'         => array(),
        'i'          => array(),
        'img'        => array(
          'alt'    => array(),
          'class'  => array(),
          'height' => array(),
          'src'    => array(),
          'width'  => array(),
        ),
        'li'         => array(
          'class' => array(),
        ),
        'ol'         => array(
          'class' => array(),
        ),
        'p'          => array(
          'class' => array(),
        ),
        'q'          => array(
          'cite'  => array(),
          'title' => array(),
        ),
        'span'       => array(
          'class' => array(),
          'title' => array(),
          'style' => array(),
        ),
        'strike'     => array(),
        'strong'     => array(),
        'ul'         => array(
          'class' => array(),
        ),
      );

          return $allowed_tags;
      }


      /*
      ID: PGGGO-21
      Since ver 1.1.0
      Modified By : Latheesh V M Villa
      Purpose of the function/class :  Returns the variable product price in neat way . Filter provided
      */
      public function pgggo_woocommmerce_variation_price_printer($pgggo_available_variations, $pgggo_price_param, $pgggo_woocommerce_cur)
      {
          $pgggo_pricing_array = array();
          foreach ($pgggo_available_variations as $key => $pgggo_available_variation) {
              $pgggo_pricing_array[] = $pgggo_available_variation[$pgggo_price_param];
          }
          $pgggo_max_price = $pgggo_woocommerce_cur.max($pgggo_pricing_array);
          $pgggo_min_price = $pgggo_woocommerce_cur.min($pgggo_pricing_array);

          $pgggo_price_range = $pgggo_min_price."&nbsp;-&nbsp;".$pgggo_max_price;
          return apply_filters('pgggo_variation_price_range_hook', $pgggo_price_range);
      }


      /*
      ID: PGGGO-22
      Since ver 1.1.0
      Modified By : Latheesh V M Villa
      Purpose of the function/class :  Returns the simple and external product price in neat way . Filter provided
      */
      public function pgggo_woocommmerce_simple_price_printer($pgggo_simple_regualar_price, $pgggo_simple_sale_price, $pgggo_woocommerce_cur)
      {
          if ($pgggo_simple_sale_price) {
              $pgggo_price = $pgggo_woocommerce_cur."<del>".$pgggo_simple_regualar_price."</del>&nbsp;". $pgggo_woocommerce_cur.$pgggo_simple_sale_price;
          } elseif ($pgggo_simple_regualar_price) {
              $pgggo_price = $pgggo_woocommerce_cur.$pgggo_simple_regualar_price;
          } else {
              $pgggo_price ="";
          }
          return apply_filters('pgggo_simple_price_range_hook', $pgggo_price);
      }

      public function pgggo_image_css_gen($pgggo_img_position, $pgggo_img_position_setter, $pgggo_img_width, $pgggo_img_padding, $pgggo_img_margin, $pgggo_img_border_radius)
      {
          $pgggo_return_array = [
        'width'                      => $pgggo_img_width['size'] . $pgggo_img_width['unit'],
        'position'                   => $pgggo_img_position,
        'top'                        => $pgggo_img_position_setter['top'] . $pgggo_img_position_setter['unit'],
        'right'                      => $pgggo_img_position_setter['right'] . $pgggo_img_position_setter['unit'],
        'left'                       => $pgggo_img_position_setter['left'] . $pgggo_img_position_setter['unit'],
        'bottom'                     => $pgggo_img_position_setter['bottom'] . $pgggo_img_position_setter['unit'],
        'padding-left'               => $pgggo_img_padding['left'] . $pgggo_img_padding['unit'],
        'padding-right'              => $pgggo_img_padding['right'] . $pgggo_img_padding['unit'],
        'padding-top'                => $pgggo_img_padding['top'] . $pgggo_img_padding['unit'],
        'padding-bottom'             => $pgggo_img_padding['bottom'] . $pgggo_img_padding['unit'],
        'margin-top'                 => $pgggo_img_margin['top'] . $pgggo_img_margin['unit'],
        'margin-left'                => $pgggo_img_margin['left'] . $pgggo_img_margin['unit'],
        'margin-right'               => $pgggo_img_margin['right'] . $pgggo_img_margin['unit'],
        'margin-bottom'              => $pgggo_img_margin['bottom'] . $pgggo_img_margin['unit'],
        'border-top-left-radius'     => $pgggo_img_border_radius['top'] . $pgggo_img_border_radius['unit'],
        'border-top-right-radius'    => $pgggo_img_border_radius['left'] . $pgggo_img_border_radius['unit'],
        'border-bottom-left-radius'  => $pgggo_img_border_radius['bottom'] . $pgggo_img_border_radius['unit'],
        'border-bottom-right-radius' => $pgggo_img_border_radius['right'] . $pgggo_img_border_radius['unit'],
      ];

          $pgggo_return_var = '';
          foreach ($pgggo_return_array as $key => $value) {
              $pgggo_return_var .= $key . ':' . $value . ';';
          }
          return $pgggo_return_var;
      }



      public function pgggo_taxonomy_render($pgggo_id, $pgggo_field_gen, $pgggo_key)
      {
          $taxonomy                  = $pgggo_field_gen[$pgggo_key]['pgggo_metadata_taxonmomny_array'];
          $taxonomy_load_links       = $pgggo_field_gen[$pgggo_key]['pgggo_metadata_load_links'];
          $taxonomy_load_links_count = $pgggo_field_gen[$pgggo_key]['pgggo_metadata_count'];
          $taxonomy_load_links_hira  = $pgggo_field_gen[$pgggo_key]['pgggo_metadata_hirarachical'];

          if ($taxonomy_load_links_hira == 'yes') {
              $taxonomy_load_links_hira_ena = true;
          } else {
              $taxonomy_load_links_hira_ena = false;
          }

          $taxonomy_load_links_child = $pgggo_field_gen[$pgggo_key]['pgggo_metadata_childless'];
          if ($taxonomy_load_links_child == 'yes') {
              $taxonomy_load_links_hira_ena = true;
          } else {
              $taxonomy_load_links_hira_ena = false;
          }

          $terms = wp_get_object_terms($pgggo_id, $taxonomy, array(
        'hide_empty'   => true,
        'hierarchical' => $taxonomy_load_links_hira_ena,
        'childless'    => $taxonomy_load_links_hira_ena,
        'number'       => $taxonomy_load_links_count,
      ));

          if ($taxonomy_load_links == 'yes') {
              $terms_list = array();
              foreach ($terms as $key => $value) {
                  $terms_list[] = array(
            'name' => $value->name,
            'link' => get_term_link($value->name, $taxonomy),
          );
                  // $terms_list[] = $value->name;
              }
              if(!empty($terms_list)){
                foreach ($terms_list as $key => $value) {
                  if(!is_wp_error($value['link']) && !is_wp_error($value['name'])){
                    $render_link[] = '<a href="' . $value['link'] . '">' . $value['name'] . '</a>';
                  }
                }
              }
              if (!empty($render_link)) {
                  $the_terms = implode(", ", $render_link);
                  return $the_terms;
              }
          } else {
              $terms_list = array();
              foreach ($terms as $key => $value) {
                  $terms_list[] = $value->name;
              }
              if (!empty($terms_list)) {
                  $the_terms = implode(", ", $terms_list);
                  return $the_terms;
              }
          }
      }

      public function pgggo_ren_metadata_new($pgggo_id, $pgggo_field_gen, $pgggo_key)
      {
          $metatoload        = $pgggo_field_gen[$pgggo_key]['pgggo_meta_to_load'];

          switch ($metatoload) {
        case 'author':
        $author_id    = get_post_field('post_author', $pgggo_id);
        $display_name = get_the_author_meta('display_name', $author_id);
        return esc_html($display_name);
        break;
        case 'taxonomy':
        return wp_kses($this->pgggo_taxonomy_render($pgggo_id, $pgggo_field_gen, $pgggo_key), $this->pgggo_html_allowed_html());
        break;

        case 'date':
        return get_the_date('j F Y', $pgggo_id);
        break;
        case 'commentcount':
        return esc_html(get_comments_number($pgggo_id));
        break;
      }
      }

      public function pgggo_core_grid_loader($pgggo_field_gen, $pgggo_make_clickable, $pgggo_allowed, $pgggo_grid_click_acf, $pgggo_grid_click_acf_field_name, $pgggo_grid_click_acf_field_open)
      {
          foreach (array_column($pgggo_field_gen, 'field_type') as $pgggo_key => $pgggo_value) {
              global $product;
              switch ($pgggo_value) {

          case "title":
          echo '<div id="' . esc_attr($pgggo_field_gen[$pgggo_key]['field_id']) . '" class="' . esc_attr($pgggo_field_gen[$pgggo_key]['field_class']) . ' pgggo-repeater-container elementor-repeater-item-' . $pgggo_field_gen[$pgggo_key]['_id'] . ' ">';
          if ($pgggo_make_clickable == 'yes'):
            if($pgggo_grid_click_acf_field_open == 'yes'){ $target= "_blank"; }else{ $target= ""; }
            if($pgggo_grid_click_acf  == 'yes'){
              if(!empty($pgggo_grid_click_acf_field_name)){
                $pgggo_link = get_field($pgggo_grid_click_acf_field_name, get_the_ID());
                if(empty($pgggo_link)){
                  $pgggo_link = get_permalink();
                }
              }else{
                $pgggo_link = "";
              }
              echo '<a target="'.esc_attr($target).'" id="pgggo-cl-title-a" class="pgggo-cl-title-a" href="' . esc_url($pgggo_link) . '">';
            }else{
              echo '<a target="'.esc_attr($target).'" id="pgggo-cl-title-a" class="pgggo-cl-title-a" href="' . get_permalink() . '">';
            }
          endif;
          echo '<div class="pgggo-repeater-container-inner">';
          echo esc_html($this->pgggo_ren_title()); //the_title_attribute which is safe to use
          echo '</div>';
          if ($pgggo_make_clickable == 'yes'):
            echo '</a>';
          endif;
          echo '</div>';
          break;

          case "image":
         $pgggo_img_id        = get_the_ID();
         $pgggo_img_type      = $pgggo_field_gen[$pgggo_key]['field_image_url'];
         $pgggo_img_custom    = $pgggo_field_gen[$pgggo_key]['field_image_acf_or_custom'];
         $adv_settings = $pgggo_field_gen[$pgggo_key];
         $pgggo_img_src      = $this->pgggo_ren_image($adv_settings,$pgggo_img_type, $pgggo_img_id, $pgggo_img_type_size='', $pgggo_img_custom);
         $pgggo_alt_gen           = get_the_title(get_the_ID());

         if (!empty($pgggo_img_src)):
           echo '<div id="' . esc_attr($pgggo_field_gen[$pgggo_key]['field_id']) . '" class=" '. esc_attr($pgggo_field_gen[$pgggo_key]['pgggo_image_hover_effect_tab_hover_transition']) .' '. esc_attr($pgggo_field_gen[$pgggo_key]['pgggo_image_hover_effect_tab_hover_overlay']) .' '. esc_attr($pgggo_field_gen[$pgggo_key]['pgggo_image_hover_effect_tab_normal_overlay']) .' '. esc_attr($pgggo_field_gen[$pgggo_key]['field_class']) . ' pgggo-repeater-container elementor-repeater-item-' . $pgggo_field_gen[$pgggo_key]['_id'] . ' ">';
           if ($pgggo_make_clickable == 'yes'):
             if($pgggo_grid_click_acf_field_open == 'yes'){ $target= "_blank"; }else{ $target= ""; }
             if($pgggo_grid_click_acf  == 'yes'){
               if(!empty($pgggo_grid_click_acf_field_name)){
                 $pgggo_link = get_field($pgggo_grid_click_acf_field_name, get_the_ID());
                 if(empty($pgggo_link)){
                     $pgggo_link =get_permalink();
                 }
               }else{
                 $pgggo_link = "";
               }
               echo '<a target="'.esc_attr($target).'" id="pgggo-cl-title-a" class="pgggo-cl-title-a" href="' . esc_url($pgggo_link) . '">';
             }else{
               echo '<a target="'.esc_attr($target).'" id="pgggo-cl-title-a" class="pgggo-cl-title-a" href="' . get_permalink() . '">';
             }
           endif;
           echo '<div class="pgggo-repeater-container-inner">';
           echo '<figure>';
           echo '<div class="pgggo-containter-image-hold">';
           echo wp_kses($pgggo_img_src, $this->pgggo_html_allowed_html());
           echo '</div>';
           echo '</figure>';
           echo '</div>';
           if ($pgggo_make_clickable == 'yes'):
             echo '</a>';
           endif;
           echo '</div>';
         endif;
         break;

          case "content":
          $pgggo_cont_id          = get_the_ID();
          $pgggo_r_content_type   = $pgggo_field_gen[$pgggo_key]['field_content_type'];
          $pgggo_r_enable_excerpt = $pgggo_field_gen[$pgggo_key]['trim_content'];
          $pgggo_excerpt_len = $pgggo_field_gen[$pgggo_key]['excerpt_length'];
          if ($pgggo_r_content_type == 'customfield_acf') {
              $pgggo_r_fieldname = $pgggo_field_gen[$pgggo_key]['field_content_type_acf'];
          } else {
              $pgggo_r_fieldname = $pgggo_field_gen[$pgggo_key]['field_content_custom_value_acf'];
          }
          $pgggo_c_src       = $this->pgggo_ren_content($pgggo_cont_id, $pgggo_r_content_type, $pgggo_r_enable_excerpt, $pgggo_excerpt_len, $pgggo_r_fieldname);


          if (!empty($pgggo_c_src)) {
              echo '<div id="' . esc_attr($pgggo_field_gen[$pgggo_key]['field_id']) . '" class="' . esc_attr($pgggo_field_gen[$pgggo_key]['field_class']) . ' pgggo-repeater-container elementor-repeater-item-' . $pgggo_field_gen[$pgggo_key]['_id'] . ' ">';
              if ($pgggo_make_clickable == 'yes'):
                if($pgggo_grid_click_acf_field_open == 'yes'){ $target= "_blank"; }else{ $target= ""; }
                if($pgggo_grid_click_acf  == 'yes'){
                  if(!empty($pgggo_grid_click_acf_field_name)){
                    $pgggo_link = get_field($pgggo_grid_click_acf_field_name, get_the_ID());
                    if(empty($pgggo_link)){
                        $pgggo_link =get_permalink();
                    }
                  }else{
                    $pgggo_link = "";
                  }
                  echo '<a target="'.esc_attr($target).'" id="pgggo-cl-title-a" class="pgggo-cl-title-a" href="' . esc_url($pgggo_link) . '">';
                }else{
                  echo '<a target="'.esc_attr($target).'" id="pgggo-cl-title-a" class="pgggo-cl-title-a" href="' . get_permalink() . '">';
                }
              endif;
              echo '<div class="pgggo-repeater-container-inner">';
              echo wp_kses($pgggo_field_gen[$pgggo_key]['pgggo_content_before_placer'], $pgggo_allowed);
              echo esc_html($pgggo_c_src);
              echo wp_kses($pgggo_field_gen[$pgggo_key]['pgggo_content_after_placer'], $pgggo_allowed);
              echo '</div>';
              if ($pgggo_make_clickable == 'yes'):
              echo '</a>';
              endif;
              echo '</div>';
          }

          break;

          case "metadata":
          $pgggo_m_id       = get_the_ID();
          $pgggo_m_en_date  = $pgggo_field_gen[$pgggo_key]['pgggo_metaldata_show_date'];
          $pgggo_m_en_tags  = $pgggo_field_gen[$pgggo_key]['pgggo_metaldata_show_tags'];
          $pgggo_m_en_tax   = $pgggo_field_gen[$pgggo_key]['pgggo_metaldata_show_taxonomy'];
          $pgggo_m_en_auth  = $pgggo_field_gen[$pgggo_key]['pgggo_metaldata_show_author'];
          $pgggo_m_en_comm  = $pgggo_field_gen[$pgggo_key]['pgggo_metaldata_show_comment'];
          $pgggo_m_taxvalue = $pgggo_field_gen[$pgggo_key]['pgggo_metaldata_select_taxonomy'];

          $pggg_m_out       = $this->pgggo_ren_metadata($pgggo_m_id, $pgggo_m_en_date, $pgggo_m_en_tags, $pgggo_m_en_tax, $pgggo_m_en_auth, $pgggo_m_en_comm, $pgggo_m_taxvalue);
          $pggg_m_out       = implode("&nbsp;|&nbsp;", $pggg_m_out);

          echo '<div id="' . esc_attr($pgggo_field_gen[$pgggo_key]['field_id']) . '" class="' . esc_attr($pgggo_field_gen[$pgggo_key]['field_class']) . ' pgggo-repeater-container elementor-repeater-item-' . $pgggo_field_gen[$pgggo_key]['_id'] . ' ">';
          echo '<div class="pgggo-repeater-container-inner">';
          echo wp_kses($pggg_m_out, $pgggo_allowed);
          echo '</div>';
          echo '</div>';

          break;

          case "metadatasingle":
          $pgggo_id       = get_the_ID();

          $pgggo_data  = $this->pgggo_ren_metadata_new($pgggo_id, $pgggo_field_gen, $pgggo_key);


          if (!empty($pgggo_data)) {
              echo '<div id="' . esc_attr($pgggo_field_gen[$pgggo_key]['field_id']) . '" class="' . esc_attr($pgggo_field_gen[$pgggo_key]['field_class']) . ' pgggo-repeater-container elementor-repeater-item-' . $pgggo_field_gen[$pgggo_key]['_id'] . ' ">';
              echo '<div class="pgggo-repeater-container-inner">';
              echo wp_kses($pgggo_field_gen[$pgggo_key]['pgggo_content_before_placer'], $pgggo_allowed);
              echo wp_kses($pgggo_data, $pgggo_allowed);
              echo wp_kses($pgggo_field_gen[$pgggo_key]['pgggo_content_after_placer'], $pgggo_allowed);
              echo '</div>';
              echo '</div>';
          }
          break;

          case "button":

          $pgggo_r_id              = get_the_ID();
          $pgggo_r_buttonfieldtype = $pgggo_field_gen[$pgggo_key]['button_field_type'];
          $pgggo_r_customfieldname = $pgggo_field_gen[$pgggo_key]['customfieldurl_gen'];
          $pgggo_r_buttonfieldtext = $pgggo_field_gen[$pgggo_key]['button_field_type_text'];

          if ($pgggo_r_buttonfieldtype == 'woocommercecart') {
              echo '<div id="' . esc_attr($pgggo_field_gen[$pgggo_key]['field_id']) . '" class="' . esc_attr($pgggo_field_gen[$pgggo_key]['field_class']) . ' pgggo-repeater-container elementor-repeater-item-' . $pgggo_field_gen[$pgggo_key]['_id'] . ' ">';
              echo '<div class="pgggo-repeater-container-inner">';
              echo sprintf(
                  '<a href="%s" data-quantity="1" style="' . esc_attr($pgggo_button_css_gen) . '" class="%s" %s>%s</a>',
                  esc_url($product->add_to_cart_url()),
                  esc_attr(implode(' ', array_filter(array(
              'pgggo-cl-button-a '. $pgggo_field_gen[$pgggo_key]['field_class'] , 'product_type_' . $product->get_type(),
              $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
              $product->supports('ajax_add_to_cart') ? 'ajax_add_to_cart' : '',
            )))),
                  wc_implode_html_attributes(array(
              'data-product_id'  => $product->get_id(),
              'data-product_sku' => $product->get_sku(),
              'aria-label'       => $product->add_to_cart_description(),
              'rel'              => 'nofollow',
            )),
                  esc_html($pgggo_r_buttonfieldtext)
              ); ?></div></div> <?php
          } elseif ($pgggo_r_buttonfieldtype == 'customfieldurlacfdonwload') {
              $pgggo_c_url = $this->pgggo_ren_button($pgggo_r_id, $pgggo_r_buttonfieldtype, $pgggo_r_customfieldname);
              if (!empty($pgggo_c_url)):
              echo '<div id="' . esc_attr($pgggo_field_gen[$pgggo_key]['field_id']) . '" class="' . esc_attr($pgggo_field_gen[$pgggo_key]['field_class']) . ' pgggo-repeater-container elementor-repeater-item-' . $pgggo_field_gen[$pgggo_key]['_id'] . ' ">';
              echo '<div class="pgggo-repeater-container-inner">';
              echo '<a href="' . esc_url($pgggo_c_url) . '" download >' . esc_html($pgggo_r_buttonfieldtext) . '</a>';
              echo '</div>';
              echo '</div>';
              endif;
          } else {
              $pgggo_c_url   = $this->pgggo_ren_button($pgggo_r_id, $pgggo_r_buttonfieldtype, $pgggo_r_customfieldname);
              echo '<div id="' . esc_attr($pgggo_field_gen[$pgggo_key]['field_id']) . '" class="' . esc_attr($pgggo_field_gen[$pgggo_key]['field_class']) . ' pgggo-repeater-container elementor-repeater-item-' . $pgggo_field_gen[$pgggo_key]['_id'] . ' ">';
              echo '<div class="pgggo-repeater-container-inner">';
              echo '<a href="' . esc_url($pgggo_c_url) . '" >' . esc_html($pgggo_r_buttonfieldtext) . '</a>';
              echo '</div>';
              echo '</div>';
          }
          break;

          case "rating":
          if (get_post_type(get_the_id()) == 'product') {
              $pgggo_average = $product->get_average_rating();
              if ($pgggo_make_clickable == 'yes'):
                if($pgggo_grid_click_acf_field_open == 'yes'){ $target= "_blank"; }else{ $target= ""; }
                if($pgggo_grid_click_acf  == 'yes'){
                  if(!empty($pgggo_grid_click_acf_field_name)){
                    $pgggo_link = get_field($pgggo_grid_click_acf_field_name, get_the_ID());
                    if(empty($pgggo_link)){
                        $pgggo_link =get_permalink();
                    }
                  }else{
                    $pgggo_link = "";
                  }
                  echo '<a target="'.esc_attr($target).'" id="pgggo-cl-title-a" class="pgggo-cl-title-a" href="' . esc_url($pgggo_link) . '">';
                }else{
                  echo '<a target="'.esc_attr($target).'" id="pgggo-cl-title-a" class="pgggo-cl-title-a" href="' . get_permalink() . '">';
                }
              endif;

              if (!empty($product->get_average_rating())):
              echo '<div id="' . esc_attr($pgggo_field_gen[$pgggo_key]['field_id']) . '" class="' . esc_attr($pgggo_field_gen[$pgggo_key]['field_class']) . ' pgggo-repeater-container elementor-repeater-item-' . $pgggo_field_gen[$pgggo_key]['_id'] . ' ">';
              echo '<div class="pgggo-repeater-container-inner">';
              echo '<div class="star-rating" title="' . sprintf(__('Rated %s out of 5', 'woocommerce'), intval($pgggo_average)) . '"><span style="width:' . ((intval($pgggo_average) / 5) * 100) . '%"><strong itemprop="ratingValue" class="rating">' . intval($pgggo_average) . '</strong> ' . __('out of 5', 'pgggo') . '</span></div>';
              echo '</div>';
              echo '</div>';
              endif;

              if ($pgggo_make_clickable == 'yes'):
              echo '</a>';
              endif;
          } else {
              esc_html_e('This field only works with Post type WooCommerce', 'pgggo');
          }
          break;

          case "html":
          $pgggo_html = $pgggo_field_gen[$pgggo_key]['field_html'];
          if ($pgggo_make_clickable == 'yes'):
            if($pgggo_grid_click_acf_field_open == 'yes'){ $target= "_blank"; }else{ $target= ""; }
            if($pgggo_grid_click_acf  == 'yes'){
              if(!empty($pgggo_grid_click_acf_field_name)){
                $pgggo_link = get_field($pgggo_grid_click_acf_field_name, get_the_ID());
                if(empty($pgggo_link)){
                    $pgggo_link =get_permalink();
                }
              }else{
                $pgggo_link = "";
              }
              echo '<a target="'.esc_attr($target).'" id="pgggo-cl-title-a" class="pgggo-cl-title-a" href="' . esc_url($pgggo_link) . '">';
            }else{
              echo '<a target="'.esc_attr($target).'" id="pgggo-cl-title-a" class="pgggo-cl-title-a" href="' . get_permalink() . '">';
            }
          endif;
          echo '<div id="' . esc_attr($pgggo_field_gen[$pgggo_key]['field_id']) . '" class="pgggo-cl-html" >';
          echo wp_kses($pgggo_html, $pgggo_allowed);
          echo '</div>';
          if ($pgggo_make_clickable == 'yes'):
            echo '</a>';
          endif;

          break;

          case "shortcode":
          $pgggo_shortcode = $pgggo_field_gen[$pgggo_key]['field_shortcode'];
          $pgggo_shortcode = $this->pgggo_ren_shortcode($pgggo_shortcode);

          echo '<div id="' . esc_attr($pgggo_field_gen[$pgggo_key]['field_id']) . '" class="' . esc_attr($pgggo_field_gen[$pgggo_key]['field_class']) . ' pgggo-repeater-container elementor-repeater-item-' . $pgggo_field_gen[$pgggo_key]['_id'] . ' ">';
          if ($pgggo_make_clickable == 'yes'):
            if($pgggo_grid_click_acf_field_open == 'yes'){ $target= "_blank"; }else{ $target= ""; }
            if($pgggo_grid_click_acf  == 'yes'){
              if(!empty($pgggo_grid_click_acf_field_name)){
                $pgggo_link = get_field($pgggo_grid_click_acf_field_name, get_the_ID());
                if(empty($pgggo_link)){
                  $pgggo_link =get_permalink();
                }
              }else{
                $pgggo_link = "";
              }
              echo '<a target="'.esc_attr($target).'" id="pgggo-cl-title-a" class="pgggo-cl-title-a" href="' . esc_url($pgggo_link) . '">';
            }else{
              echo '<a target="'.esc_attr($target).'" id="pgggo-cl-title-a" class="pgggo-cl-title-a" href="' . get_permalink() . '">';
            }
          endif;
          echo '<div class="pgggo-repeater-container-inner">';
          echo wp_kses($pgggo_field_gen[$pgggo_key]['pgggo_content_before_placer'], $pgggo_allowed);
          echo do_shortcode($pgggo_shortcode);
          echo wp_kses($pgggo_field_gen[$pgggo_key]['pgggo_content_after_placer'], $pgggo_allowed);
          echo '</div>';
          if ($pgggo_make_clickable == 'yes'):
            echo '</a>';
          endif;
          echo '</div>';
          break;

          case "shortcode_elementor_pro":
          $pgggo_shortcode = $pgggo_field_gen[$pgggo_key]['field_shortcode_elementor'];
          echo '<div id="' . esc_attr($pgggo_field_gen[$pgggo_key]['field_id']) . '" class="' . esc_attr($pgggo_field_gen[$pgggo_key]['field_class']) . ' pgggo-repeater-container elementor-repeater-item-' . $pgggo_field_gen[$pgggo_key]['_id'] . ' ">';
          if ($pgggo_make_clickable == 'yes'):
            if($pgggo_grid_click_acf_field_open == 'yes'){ $target= "_blank"; }else{ $target= ""; }
            if($pgggo_grid_click_acf  == 'yes'){
              if(!empty($pgggo_grid_click_acf_field_name)){
                $pgggo_link = get_field($pgggo_grid_click_acf_field_name, get_the_ID());
                if(empty($pgggo_link)){
                  $pgggo_link =get_permalink();
                }
              }else{
                $pgggo_link = "";
              }
              echo '<a target="'.esc_attr($target).'" id="pgggo-cl-title-a" class="pgggo-cl-title-a" href="' . esc_url($pgggo_link) . '">';
            }else{
              echo '<a target="'.esc_attr($target).'" id="pgggo-cl-title-a" class="pgggo-cl-title-a" href="' . get_permalink() . '">';
            }
          endif;
          echo '<div class="pgggo-repeater-container-inner">';
          echo wp_kses($pgggo_field_gen[$pgggo_key]['pgggo_content_before_placer'], $pgggo_allowed);
            echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($pgggo_shortcode);
          echo wp_kses($pgggo_field_gen[$pgggo_key]['pgggo_content_after_placer'], $pgggo_allowed);
          echo '</div>';
          if ($pgggo_make_clickable == 'yes'):
            echo '</a>';
          endif;
          echo '</div>';
          break;

          case "wooprice":
          if (get_post_type(get_the_id()) == 'product') {
              if (($product->is_type('simple')) || ($product->is_type('external'))) {
                  $pgggo_woocommerce_cur = get_woocommerce_currency_symbol();
                  $pgggo_simple_regualar_price = get_post_meta(get_the_ID(), '_regular_price', true);
                  $pgggo_simple_sale_price = get_post_meta(get_the_ID(), '_sale_price', true);

                  if (!empty($this->pgggo_woocommmerce_simple_price_printer($pgggo_simple_regualar_price, $pgggo_simple_sale_price, $pgggo_woocommerce_cur))) {
                      echo '<div id="' . esc_attr($pgggo_field_gen[$pgggo_key]['field_id']) . '" class="' . esc_attr($pgggo_field_gen[$pgggo_key]['field_class']) . ' pgggo-repeater-container elementor-repeater-item-' . $pgggo_field_gen[$pgggo_key]['_id'] . ' ">';
                      echo '<div class="pgggo-repeater-container-inner">';
                      echo wp_kses($pgggo_field_gen[$pgggo_key]['pgggo_content_before_placer'], $pgggo_allowed);
                      echo wp_kses($this->pgggo_woocommmerce_simple_price_printer($pgggo_simple_regualar_price, $pgggo_simple_sale_price, $pgggo_woocommerce_cur), $pgggo_allowed);
                      echo wp_kses($pgggo_field_gen[$pgggo_key]['pgggo_content_after_placer'], $pgggo_allowed);
                      echo '</div>';
                      echo '</div>';
                  }
              } elseif ($product->is_type('variable')) {
                  $pgggo_available_variations = $product->get_available_variations();
                  $pgggo_price_param = "display_price";
                  $pgggo_woocommerce_cur = get_woocommerce_currency_symbol();
                  if (!empty($this->pgggo_woocommmerce_variation_price_printer($pgggo_available_variations, $pgggo_price_param, $pgggo_woocommerce_cur))) {
                      echo '<div id="' . esc_attr($pgggo_field_gen[$pgggo_key]['field_id']) . '" class="' . esc_attr($pgggo_field_gen[$pgggo_key]['field_class']) . ' pgggo-repeater-container elementor-repeater-item-' . $pgggo_field_gen[$pgggo_key]['_id'] . ' ">';
                      echo '<div class="pgggo-repeater-container-inner">';
                      echo wp_kses($pgggo_field_gen[$pgggo_key]['pgggo_content_before_placer_variable'], $pgggo_allowed);
                      echo esc_html($this->pgggo_woocommmerce_variation_price_printer($pgggo_available_variations, $pgggo_price_param, $pgggo_woocommerce_cur));
                      echo wp_kses($pgggo_field_gen[$pgggo_key]['pgggo_content_after_placer_variable'], $pgggo_allowed);
                      echo '</div>';
                      echo '</div>';
                  }
              } else {
                  do_action('pgggo_suppport_for_custom_product_type_hook_act', 'pgggo_suppport_for_custom_product_type');
              }
          } else {
              esc_html_e('This field only works with Post type - WooCommerce', 'pgggo');
          }

          break;
          default:
          echo "";
        }
          }
      }

      public function pgggo_taxonomy_selector()
      {
          $args = array(
        'public'   => true,);
          $output = 'objects';

          $taxonomies = get_taxonomies($args, $output);

          $formated = array();
          if ($taxonomies) {
              foreach ($taxonomies as $taxonomy) {
                  $formated[$taxonomy->name] =   $taxonomy->labels->name;
              }
          }

          return $formated;
      }

      public function pgggo_taxonomy_selector_terms($taxonomy,$include=array(),$exclude=array())
      {

        if(!empty($include)){
          $terms = get_terms([
          'taxonomy' => $taxonomy,
          'hide_empty' => false,
          'include' => $include,
          ]);
        }elseif(!empty($exclude)){
          $terms = get_terms([
          'taxonomy' => $taxonomy,
          'hide_empty' => false,
          'exclude'=>$exclude,
          ]);
        }else{
          $terms = get_terms([
          'taxonomy' => $taxonomy,
          'hide_empty' => false,
          ]);
        }
          return $terms;
      }

      public function pgggo_acf_realational_field_support($pgggo_args, $settings)
      {
          $pgggo_relational = $settings['pgggo_post_grid_post_id_show_only'];
          if (!empty($pgggo_relational)) {
              if (!empty(get_field($pgggo_relational)) && get_field($pgggo_relational) !== null) {
                  $pgggo_dis_this_only = get_field($pgggo_relational);

                  $pgggo_relationshiptype = $settings['pgggo_relation_type_set'];
                  switch ($pgggo_relationshiptype) {
              case 'postobject':
              if (is_object($pgggo_dis_this_only)) {
                  $pgggo_rel_arr[] = $pgggo_dis_this_only->ID;
              } elseif (is_object($pgggo_dis_this_only[0])) {
                  $pgggo_rel_arr = array();
                  foreach ($pgggo_dis_this_only as $key => $value) {
                      $pgggo_rel_arr[] = $value->ID;
                  }
              } elseif (is_array($pgggo_dis_this_only)) {
                  $pgggo_rel_arr2 = array();
                  foreach ($pgggo_dis_this_only as $key => $value) {
                      $pgggo_rel_arr2[] = $value;
                  }
                  $pgggo_rel_arr= $pgggo_rel_arr2;
              } elseif (is_int($pgggo_dis_this_only)) {
                  $pgggo_rel_arr[] = $pgggo_dis_this_only;
              }
              if (!empty($pgggo_rel_arr)) {
                  if (array_key_exists('post__in', $pgggo_args)) {
                      unset($pgggo_args['post__in']);
                  }
                  $pgggo_args['post__in'] = $pgggo_rel_arr;
                  return $pgggo_args;
              }
              break;
              case 'relationship':
              if (is_object($pgggo_dis_this_only)) {
                  $pgggo_rel_arr[] = $pgggo_dis_this_only->ID;
              } elseif (is_object($pgggo_dis_this_only[0])) {
                  $pgggo_rel_arr = array();
                  foreach ($pgggo_dis_this_only as $key => $value) {
                      $pgggo_rel_arr[] = $value->ID;
                  }
              } elseif (is_array($pgggo_dis_this_only)) {
                  $pgggo_rel_arr2 = array();
                  foreach ($pgggo_dis_this_only as $key => $value) {
                      $pgggo_rel_arr2[] = $value;
                  }
                  $pgggo_rel_arr= $pgggo_rel_arr2;
              } elseif (is_int($pgggo_dis_this_only)) {
                  $pgggo_rel_arr[] = $pgggo_dis_this_only;
              }
              if (!empty($pgggo_rel_arr)) {
                  if (array_key_exists('post__in', $pgggo_args)) {
                      unset($pgggo_args['post__in']);
                  }
                  $pgggo_args['post__in'] = $pgggo_rel_arr;
                  return $pgggo_args;
              }
              break;

              case 'user':
              if (is_object($pgggo_dis_this_only)) {
                  $pgggo_rel_arr[] = $pgggo_dis_this_only->ID;
              } elseif (is_object($pgggo_dis_this_only[0])) {
                  $pgggo_rel_arr = array();
                  foreach ($pgggo_dis_this_only as $key => $value) {
                      $pgggo_rel_arr[] = $value->ID;
                  }
              } elseif (is_array($pgggo_dis_this_only)) {
                  $pgggo_rel_arr2 = array();
                  if (array_key_exists('user_firstname', $pgggo_dis_this_only)) {
                      foreach ($pgggo_dis_this_only as $key => $value) {
                          $pgggo_rel_arr2[] = $value['ID'];
                      }
                  } else {
                      foreach ($pgggo_dis_this_only as $key => $value) {
                          $pgggo_rel_arr2[] = $value;
                      }
                  }
                  $pgggo_rel_arr= $pgggo_rel_arr2;
              } elseif (is_int($pgggo_dis_this_only)) {
                  $pgggo_rel_arr[] = $pgggo_dis_this_only;
              }
              if (!empty($pgggo_rel_arr)) {
                  if (array_key_exists('author__in', $pgggo_args)) {
                      unset($pgggo_args['author__in']);
                  }
                  $pgggo_args['author__in'] = $pgggo_rel_arr;
                    return $pgggo_args;
              }
              break;
            }
              }
          }
      }

      public function pgggo_sort_filer_form_gen($settings, $pgggo_user_data, $pgggo_args, $pgggo_acend_var, $pgggo_decend_var, $pgggo_enable_ajax_sorting='')
      {
          ?>
        <div data-ajax-container-select="{}" data-ajax-container="{}" class="pgggo-sort-container pgggo-sort-collapse <?php if ($pgggo_enable_ajax_sorting == 'yes') {
              echo ' pgggo-container-ajax-sorting';
          } ?>">
          <div class="pgggo-filter-box">
            <?php if($settings['pgggo_enable_clear'] == 'yes'): ?>
            <div class="pgggo-clear-active-filters pgggo-sort-collapse-button pgggo-sort-collapse-submit-b1">
              <?php echo esc_html__($settings['pgggo_filter_button_text_clear']); ?>
            </div>
          <?php endif; ?>
            <div class="pgggo-sort-collapse-button pgggo-sort-collapse-submit-b1">
              <?php echo esc_html__($settings['pgggo_filter_button_text']); ?>
            </div>
          </div>
          <div class="<?php if ($settings['pgggo_enable_sorter_collapse']== 'yes') {
              echo "pgggo-sort-collapase-content ";
          } ?> pgggo-sort-collapse-content-box">
            <form class="pgggo-sort-filter-form" id = "pgggo-sort-filter-form" name="pgggoformsub" action="" method="post">
              <div id="pgggo-sort-filter" class="pgggo-sort-filter">
                <ul>
                  <?php
                  // #2
                  if ($settings['pgggo_grid_sort_and_filter_layout']) {
                      $count = 0;
                      if (!empty($pgggo_args['tax_query'])) {
                          $pgggo_taxon_array = $pgggo_args['tax_query'];
                      } else {
                          $pgggo_taxon_array = array();
                      }
                      foreach ($settings['pgggo_grid_sort_and_filter_layout'] as $item) {
                          switch ($item['pgggo_grid_sort_and_filter_type']) {
                        case 'sort':
                        ?>
                        <li class="pgggo-li-checkbox">
                          <div class="pgggo-select-label pgggo-select-label-select">
                            <?php echo esc_html($item['pgggo_grid_sort_and_filter_label']); ?>
                          </div>
                          <label>
                            <input class="pgggo-check-boxer pgggocheckboxaccendinp" type="checkbox" name="pgggo-sort-acend" <?php if (!empty($pgggo_acend_var) && $pgggo_acend_var == 'on') {
                            echo "checked";
                        } elseif (empty($pgggo_decend_var) && empty($pgggo_acend_var) && !empty($pgggo_args['order'] && $pgggo_args['order']=='ASC')) {
                            echo "checked";
                        }  ?>>
                            <div class="icon-box pgggocheckboxaccend">
                              <?php \Elementor\Icons_Manager::render_icon( $settings['pgggo_acend_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                            </div>
                          </label>
                          <label>
                            <input class="pgggo-check-boxer pgggocheckboxdecendinp" type="checkbox" name="pgggo-sort-decend"<?php if (!empty($pgggo_decend_var) && $pgggo_decend_var == 'on') {
                            echo "checked";
                        } elseif (empty($pgggo_decend_var) && empty($pgggo_acend_var) && !empty($pgggo_args['order'] && $pgggo_args['order']=='DESC')) {
                            echo "checked";
                        }   ?>>
                            <div class="icon-box pgggocheckboxdecend">
                              <?php \Elementor\Icons_Manager::render_icon( $settings['pgggo_decend_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                            </div>
                          </label>
                        </li>
                        <?php
                        break;
                        case 'select':
                        $pgggo_current_taxon = $item['taxonomy'];

                        //inclution handling
                        $pgggo_include_data = $settings['pgggo_filter_button_text_include'];
                        if(!empty($pgggo_include_data)){
                          $include =  array_map(function($v){ return (int) trim($v, "'"); }, explode(",", $pgggo_include_data));
                          $pgggo_current_taxon_terms = $pgggo_user_data->pgggo_taxonomy_selector_terms($pgggo_current_taxon,$include);
                        }else{
                          $pgggo_current_taxon_terms = $pgggo_user_data->pgggo_taxonomy_selector_terms($pgggo_current_taxon);
                        }
                        //inclution handling ends

                        //exclution handling
                        $pgggo_exclude_data = $settings['pgggo_filter_button_text_exculde'];
                        if(!empty($pgggo_exclude_data)){
                            $pgggo_exclude_data =  array_map(function($v){ return (int) trim($v, "'"); }, explode(",", $pgggo_exclude_data));
                            $pgggo_exclude = $pgggo_exclude_data;
                            if(!empty($pgggo_current_taxon_terms)){
                              foreach ($pgggo_current_taxon_terms as $key => $value) {
                                if(in_array($value->term_id, $pgggo_exclude )){
                                  unset($pgggo_current_taxon_terms[$key]);
                                }
                              }
                            }
                        }
                        //exclusion handling ends
                        echo '<li>';
                        ?>
                        <div class="pgggo-ui pgggo-select-taxon">
                          <div class="pgggo-select-label pgggo-select-label-select">
                            <?php echo esc_html($item['pgggo_grid_sort_and_filter_label']); ?>
                          </div>
                          <select id="pgggo-select-ajax-pos" <?php echo esc_attr($item['pgggo_grid_sort_and_filter_multiselect']);  ?> name="<?php echo 'pgggo-taxon-select-'.$pgggo_current_taxon.'-'.$count.'[]' ?>" class="ui fluid pgggodropdown">
                            <?php
                            $pgggo_array_data = array();
                            foreach ($pgggo_taxon_array as $key => $value) {
                                if ($value['taxonomy'] == $pgggo_current_taxon) {
                                    $pgggo_array_data[] = $value['terms'];
                                }
                            }
                            echo '<option value="">'.esc_html($item['pgggo_grid_sort_and_filter_label']).'</option>';
                            foreach ($pgggo_current_taxon_terms as $key => $value) {
                                // create the array for checking
                                $pgggo_array_in =array();

                                if (!empty($pgggo_array_data)) {
                                    $pgggo_array_in = $pgggo_array_data[0];
                                }

                                if (in_array($value->term_id, $pgggo_array_in) && !empty($pgggo_array_in)) {
                                    echo '<option value="'.esc_html($value->term_id).'" selected>'.esc_html($value->name).'</option>';
                                } else {
                                    echo '<option value="'.esc_html($value->term_id).'">'.esc_html($value->name).'</option>';
                                }
                            }
                            ?>
                          </select>
                        </div>

                        <?php
                        echo '</li>';
                        break;
                        case 'list':
                        $pgggo_current_taxon = $item['taxonomy'];
                        //inclution handling
                        $pgggo_include_data = $settings['pgggo_filter_button_text_include'];
                        if(!empty($pgggo_include_data)){
                          $include =  array_map(function($v){ return (int) trim($v, "'"); }, explode(",", $pgggo_include_data));
                          $pgggo_current_taxon_terms = $pgggo_user_data->pgggo_taxonomy_selector_terms($pgggo_current_taxon,$include);
                        }else{
                          $pgggo_current_taxon_terms = $pgggo_user_data->pgggo_taxonomy_selector_terms($pgggo_current_taxon);
                        }
                        //inclution handling ends
                        //exclution handling
                       $pgggo_exclude_data = $settings['pgggo_filter_button_text_exculde'];
                       if(!empty($pgggo_exclude_data)){
                           $pgggo_exclude_data = $result = array_map(function($v){ return (int) trim($v, "'"); }, explode(",", $pgggo_exclude_data));
                       }else{
                         $pgggo_exclude_data = array();
                       }
                       $pgggo_exclude = $pgggo_exclude_data;
                       if(!empty($pgggo_current_taxon_terms)){
                         foreach ($pgggo_current_taxon_terms as $key => $value) {
                           if(in_array($value->term_id, $pgggo_exclude )){
                             unset($pgggo_current_taxon_terms[$key]);
                           }
                         }
                       }
                       //exclusion handling ends
                        $pgggo_array_data2 = array();
                        foreach ($pgggo_taxon_array as $key => $value) {
                            if ($value['taxonomy'] == $pgggo_current_taxon) {
                                $pgggo_array_data2[] = $value['terms'];
                            }
                        }
                        echo "<div class='pgggo-list-taxon-main'>";
                        ?>
                        <div class="pgggo-select-label pgggo-select-label-select">
                          <?php echo esc_html($item['pgggo_grid_sort_and_filter_label']); ?>
                        </div>
                        <?php
                        echo "<div class='pgggo-list-taxon'>";
                        foreach ($pgggo_current_taxon_terms as $key => $value) {
                            echo '<li>'; ?>
                          <label>
                            <?php
                            if (!empty($pgggo_array_data2[0]) && is_array($pgggo_array_data2)) {
                                $pgggo_store_array = $pgggo_array_data2[0];
                            } else {
                                $pgggo_store_array =array();
                            }

                            if (in_array(strval($value->term_id), $pgggo_store_array)): ?>
                              <input type="checkbox" name="<?php echo "pgggo-$pgggo_current_taxon-sep-$value->term_id[]"; ?>" checked>
                            <?php else: ?>
                              <input type="checkbox" name="<?php echo "pgggo-$pgggo_current_taxon-sep-$value->term_id[]"; ?>">
                            <?php endif; ?>
                            <div class="icon-box">
                                <?php
                                $pgggo_iconimage_url = '';
                                if($item['pgggo_filter_enable_acf_icons'] == 'yes'){
                                  if(class_exists('ACF')):
                                    $pgggo_iconimage_url = get_field($item['pgggo_filter_enable_acf_icons_acf'], $pgggo_current_taxon.'_'.$value->term_id);
                                  endif;
                                } ?>

                              <?php
                              if(!empty($pgggo_iconimage_url)){
                                echo '<div name="'.esc_html($value->slug).'"><img src="'.esc_url($pgggo_iconimage_url).'"></div>';
                              }else{
                                 echo '<div name="'.esc_html($value->slug).'">'.esc_html(ucwords($value->name)).'</div>';
                              }
                               ?>
                            </div>
                          </label>
                          <?php
                          echo '</li>';
                        }
                        echo "</div>";
                        echo "</div>";
                        break;
                      }
                          $count = $count+1;
                      }
                  } ?>
                </ul>
                <?php if ($settings['pgggo_enable_sorter_ajax'] != 'yes'): ?>
                  <div>
                <input class="pgggo-sort-collapse-submit pgggo-sort-collapse-submit-b2" type="submit" value="<?php echo esc_html__($settings['pgggo_filter_button_text2']); ?>">
                <?php endif; ?>
              </div>
            </form>
          </div>
        </div>
        <?php
      }


      public function pgggo_generator_select($settings, $pgggo_user_data)
      {
          if ($settings['pgggo_enable_sorter'] != 'yes') {
              return;
          }
          if (empty($settings['pgggo_grid_sort_and_filter_layout'])) {
              return;
          }

          $pgggo_current_taxon = array();
          $pgggo_current_taxon_list = array();
          foreach ($settings['pgggo_grid_sort_and_filter_layout'] as $key => $value) {
              switch ($value['pgggo_grid_sort_and_filter_type']) {
            case 'select':
            $pgggo_current_taxon[] = $value['taxonomy'];
            break;
            case 'list':
            $pgggo_current_taxon_list[] = $value['taxonomy'];
            break;
          }
          }
          $pgggo_selection_var =array();
          foreach ($pgggo_current_taxon as $key => $value) {
              for ($i=0; $i < 5; $i++) {
                  $pgggo_selection_var[$value][] = (get_query_var('pgggo-taxon-select-'.$value.'-'.$i.'')) ? get_query_var('pgggo-taxon-select-'.$value.'-'.$i.'') : array();
              }
          }

          if (!empty($pgggo_selection_var)) {
              $pgggo_array_module = [];
              foreach ($pgggo_selection_var as $key=>$value) {
                  $pgggo_array_module[$key]['taxonomy'] = $key;

                  $value= array_filter(array_map('array_filter', $value));
                  if (count($value) > 0) {
                      foreach ($value as $val) {
                          if (is_array($val)) {
                              $pgggo_array_module[$key]['data'] = (isset($pgggo_array_module[$key]['data'])) ? array_merge($pgggo_array_module[$key]['data'], $val) : $val;
                          }
                      }
                      $pgggo_array_module[$key]['data'] = array_unique($pgggo_array_module[$key]['data']);
                  } else {
                      $pgggo_array_module[$key]['data'] = '';
                  }
              }

              $pgggo_selection_var = array_values($pgggo_array_module);
          }

          $pgggo_arrayNameSelect = array();
          if (!empty($pgggo_selection_var) && is_array($pgggo_selection_var)) {
              foreach ($pgggo_selection_var as $key => $value) {
                  if (!empty($value['data'])) {
                      $pgggo_arrayNameSelect[] = array(
                'taxonomy' => $value['taxonomy'],
                 'field' => 'term_id',
                 'terms' => $value['data'],
              );
                  }
              }
          }




          //list module
          $pgggo_selection_var_tec =array();
          foreach ($pgggo_current_taxon_list as $key => $value) {
              $taxonomy = $value;
              $get_associated_terms = $pgggo_user_data->pgggo_taxonomy_selector_terms($value);
              foreach ($get_associated_terms as $key => $value_term) {
                  if (is_array(get_query_var('pgggo-'.$taxonomy.'-sep-'.$value_term->term_id))) {
                      if (get_query_var('pgggo-'.$taxonomy.'-sep-'.$value_term->term_id)[0] == 'on') {
                          $pgggo_selection_var_tec[$taxonomy][] = $value_term->term_id;
                      }
                  }
              }
          }

          $pgggo_arrayNameList = array();
          foreach ($pgggo_selection_var_tec as $key => $value) {
              $pgggo_arrayNameList[] = array(
             'taxonomy' => $key,
              'field' => 'term_id',
              'terms' => $value,
           );
          }

          return $pgggo_selection_var_data = array(
          'select_array' => $pgggo_arrayNameSelect,
          'list_array' => $pgggo_arrayNameList,
        );
      }
  }
  }
