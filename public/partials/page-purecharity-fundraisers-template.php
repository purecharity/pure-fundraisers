<?php 
/*
Template Name: Custom Purecharity Rundraisers Template
*/
  get_header();
  get_template_part( 'head'); 
?>

  <div class="wide-container">
    <div class="container">
      <div class="row">
        <div class="page-content col span_24">
          <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
          <div class="post-body">
            <?php 
              $pattern = get_shortcode_regex();
              $content = get_the_content();
              
              preg_match_all( '/'. $pattern .'/s', $content, $matches );

              // echo "<pre>";
              // var_dump($matches);
              // exit;

              if( is_array( $matches ) && array_key_exists( 2, $matches ) && in_array( 'fundraisers', $matches[2] ) )
              {
                foreach ($matches[0] as $value) {
                  $value = wpautop( $value, true );
                  echo do_shortcode($value);
                }
              }
            ?>
          </div>
          <?php endwhile; else: ?>
          <?php _e('Sorry, no posts matched your criteria.'); ?>
          <?php endif; ?>
        </div>
        
      </div><!-- end row-->
    </div> <!-- end container-->
  </div> 

<?php get_footer() ?>