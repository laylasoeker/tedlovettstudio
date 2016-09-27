<?php

/* Back End */

  /* Disable jpg Compression */

      add_filter('jpeg_quality', function($quality) {
        return 100;
      });

  /* Portfolio Custom Post Type */

      function portfolio_register() {

        $labels = array(
          'name'               => __('Portfolio', 'post type general name'),
          'singular_name'      => __('Portfolio Piece', 'post type singular name'),
          'add_new'            => __('Add New', 'piece'),
          'add_new_item'       => __('Add New Portfolio Piece'),
          'edit_item'          => __('Edit Portfolio Piece'),
          'new_item'           => __('New Portfolio Piece'),
          'view_item'          => __('View Portfolio Piece'),
          'search_items'       => __('Search Portfolio'),
          'not_found'          => __('Nothing found'),
          'not_found_in_trash' => __('Nothing found in Trash'),
          'parent_item_colon'  => ''
        );

        $args = array(
          'labels'             => $labels,
          'public'             => true,
          'publicly_queryable' => true,
          'show_ui'            => true,
          'query_var'          => true,
          'menu_icon'          => get_stylesheet_directory_uri() . 'imageToReplace.png',
          'rewrite'            => true,
          'capability_type'    => 'post',
          'hierarchical'       => true,
          'menu_position'      => null,
          'supports'           => array('title', 'editor', 'thumbnail', 'page-attributes')
        );

        register_post_type('portfolio' , $args);
      }

      add_action('init', 'portfolio_register');

    /* Custom Slugs */

      function customPostType_slug($post_link, $post, $leavename) {

          if ('portfolio' != $post->post_type || 'publish' != $post->post_status) {

              return $post_link;
          }

          /* Remove post type from slug: */

             $post_link = str_replace('/' . $post->post_type . '/', '/', $post_link);

          return $post_link;
      }

      add_filter('post_type_link', 'customPostType_slug', 10, 3);

    /* Match to Template */

      function custom_parse_request_tricksy($query) {

          if (!$query->is_main_query()) {

              return;
          }

          if (2 != count( $query->query ) || ! isset( $query->query['page'])) {

              return;
          }

          if (!empty( $query->query['name'])) {

              $query->set('post_type', array('post', 'portfolio', 'page'));
          }
      }

      add_action('pre_get_posts', 'custom_parse_request_tricksy');

  /* Taxonomy for Client */

    function register_taxonomy_clients() {

        $labels = array(
            'name'                       => __('Client', 'clients'),
            'singular_name'              => __('Client', 'clients'),
            'search_items'               => __('Search Clients', 'clients'),
            'popular_items'              => __('Popular Clients', 'clients'),
            'all_items'                  => __('All Clients', 'clients'),
            'parent_item'                => __('Parent Client', 'clients'),
            'parent_item_colon'          => __('Parent Client:', 'clients'),
            'edit_item'                  => __('Edit Client', 'clients'),
            'update_item'                => __('Update Client', 'clients'),
            'add_new_item'               => __('Add New Client', 'clients'),
            'new_item_name'              => __('New Client', 'clients'),
            'separate_items_with_commas' => __('Separate clients with commas', 'clients'),
            'add_or_remove_items'        => __('Add or remove Clients', 'clients'),
            'choose_from_most_used'      => __('Choose from most used Clients', 'clients'),
            'menu_name'                  => __('Clients', 'clients'),
        );

        $args = array(
            'labels'            => $labels,
            'public'            => true,
            'show_in_nav_menus' => true,
            'show_ui'           => true,
            'show_tagcloud'     => true,
            'show_admin_column' => true,
            'hierarchical'      => true,
            'rewrite'           => true,
            'query_var'         => true,
            'supports'          => 'page-attributes'
        );

        register_taxonomy('clients', array('portfolio'), $args);
    }

    add_action('init', 'register_taxonomy_clients');

  /* Remove ACF from menu for non admin users */

    function remove_acf_menu() {

      $admins = array(
        'Daniel'
      );

      $current_user = wp_get_current_user();

      if (!in_array($current_user->user_login, $admins)) {

        remove_menu_page('edit.php?post_type=acf');
      }
    }

    add_action('admin_menu', 'remove_acf_menu', 999);

  /* Lazy Load image replacement */

    if (!class_exists('tlaLazyLoad')) {

        class tlaLazyLoad {

            const version = '1.0';

            static function init() {

                if (is_admin()) {

                    return;
                }

                add_filter('acf/load_value/name=project_body', array( __CLASS__, 'add_image_placeholders' ), 99 );
            }

            static function add_image_placeholders($content) {

                if (is_feed() || !is_page('clients')) {

                    return $content;
                }

                if (false !== strpos($content, 'data-lazy-src')) {

                    return $content;
                }

                $placeholder_image = apply_filters('tlaLazyLoad_placeholder_image', '/assets/img/tedLovett-agency-lazyLoad-placeholder.gif');

                $content = preg_replace(

                    '#<img([^>]+?)src=[\'"]?([^\'"\s>]+)[\'"]?([^>]*)>#',
                    sprintf(

                        '<img${1}src="%s" data-tla-lazy-src="${2}"${3}><noscript><img${1}src="${2}"${3}></noscript>',
                        $placeholder_image),
                    $content
                );

                return $content;
            }
        }

        function tlaLazyLoad_add_placeholders($content) {

            return tlaLazyLoad::add_image_placeholders($content);
        }

        tlaLazyLoad::init();
    }

    /* Prevent style injection from Vipers Video Quicktags plugin */

        global $VipersVideoQuicktags;

        remove_action('wp_head', array($VipersVideoQuicktags, 'Head'), 99);