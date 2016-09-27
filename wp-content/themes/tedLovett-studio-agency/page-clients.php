<?php

 $taxonomy = 'clients';

 $args = array(
  'hide_empty' => false,
  'orderby'    => 'menu_order'
 );

 $terms = get_terms($taxonomy, $args);

 if (!empty($terms) && !is_wp_error($terms)) {

  echo '<ul>';

  foreach ($terms as $term) {

    $clientName = $term->name;
    $clientSlug = $term->slug;

    $args = array(
      'post_type' => 'portfolio',
      $taxonomy   => $clientSlug,
      'orderby'   => 'menu_order',
      'order'     => 'ASC'
    );

    $the_query = new WP_Query($args);

    if ($the_query->have_posts()) {

      echo '<li class="clientContainer container"><article id="' . $clientSlug . '" class="client"><h1 class="clientName name">' . $clientName . '</h1>';

      while ($the_query->have_posts()) {

        $the_query->the_post();

        $projectPermalink   = get_permalink();
        $projectTitle       = get_the_title();
        $projectRole        = get_field('project_role');
        $projectDescription = get_field('project_description');
        $projectBody        = get_field('project_body');
        $baseUrl            = get_site_url();
        $formattedPieceUrl  = trim(str_replace($baseUrl, '', $projectPermalink), '/');

        echo '<section id="' . $clientSlug . '-' . $formattedPieceUrl . '" class="clientProjectContainer container"><h2 class="clientProject name" data-project-nice-name="' . $clientName . ' ' . $projectTitle . '">' . $projectTitle . '</h2><h3 class="clientRole name">' . $projectRole . '</h3>';

        echo '<div class="clientProjectDescription">' . $projectDescription . '</div>';

        echo '<div class="clientProjectBody">' . $projectBody . '</div>';

        echo '</section>';
      }

      echo '</article></li>';
    }

    wp_reset_postdata();
  }

  echo '</ul>';
 }

?>