<?php

/**
 * Pagination generator class
 *
 * @link       http://purecharity.com
 * @since      1.0.0
 *
 * @package    Purecharity_Wp_Fundraisers
 * @subpackage Purecharity_Wp_Fundraisers/includes
 */

/**
 * Pagination generator class.
 *
 * @package    Purecharity_Wp_Fundraisers
 * @subpackage Purecharity_Wp_Fundraisers/includes
 * @author     Pure Charity <dev@purecharity.com>
 */
class Purecharity_Wp_Fundraisers_Paginator {
  const DEFAULT_PER_PAGE = 10;

  /**
   * Generates pagination html for given collection of objects.
   *
   * TODO: Document possible options.
   *
   * @since    1.0.0
   */
  public static function page_links($meta = array(), $options = array()){
    if((int)$meta->num_pages == 1){
      return '';
    }
    $opts = self::sanitize_options($options);

    $html = '<div class="pagination">';
    $html .= '<ul class="page-numbers">';

    if($meta->current_page > 1){
      $html .= '<li><a class="page-numbers" href="?_page='.($meta->current_page-1).'">Previous</a></li>';
    }

    if($meta->num_pages < 10){
      for($i = 1; $i <= $meta->num_pages; $i++){
        if($meta->current_page == $i){
          $html .= '<li><span class="page-numbers current">'.$i.'</span></li>';
        }else{
          $html .= '<li><a class="page-numbers" href="?_page='.$i.'">'.$i.'</a></li>';
        }
      }
    }else{
      # More than 10 pages, limit to 10
      $lower_limit = $meta->current_page > 3 ? 3 : 5;
      $upper_limit = $meta->current_page < ($meta->num_pages-3) ? 3 : 5;

      for($i = 1; $i <= $lower_limit; $i++){
        if($meta->current_page == $i){
          $html .= '<li><span class="page-numbers current">'.$i.'</span></li>';
        }else{
          $html .= '<li><a class="page-numbers" href="?_page='.$i.'">'.$i.'</a></li>';
        }
      }
      $html .= '<li><a class="divider">...</a></li>';

      if($meta->current_page == 4){
        $html .= '<li><span class="page-numbers current">4</span></li>';
        $html .= '<li><a class="divider">...</a></li>';
      }

      if((int)$meta->current_page > 4 && (int)$meta->current_page < ($meta->num_pages-4)){
        for($i = ($meta->current_page-1); $i <= ($meta->current_page+1); $i++){
          if($meta->current_page == $i){
            $html .= '<li><span class="page-numbers current">'.$i.'</span></li>';
          }else{
            $html .= '<li><a class="page-numbers" href="?_page='.$i.'">'.$i.'</a></li>';
          }
        } 
        $html .= '<li><a class="divider">...</a></li>';
      }

      if($meta->current_page == ($meta->num_pages-4)){
        $html .= '<li><span class="page-numbers current">'. $meta->current_page .'</span></li>';
      }

      for($i = ($meta->num_pages-$upper_limit); $i <= $meta->num_pages; $i++){
        if($meta->current_page == $i){
          $html .= '<li><span class="page-numbers current">'.$i.'</span></li>';
        }else{
          $html .= '<li><a class="page-numbers" href="?_page='.$i.'">'.$i.'</a></li>';
        }
      }
    }
    
    if($meta->current_page < $meta->num_pages){
      $html .= '<li><a class="page-numbers" href="?_page='.($meta->current_page+1).'">Next</a></li>';
    }

    $html .= '</ul>';
    $html .= '</div>';
    
    return $html;
  }

  /**
   * Convert options into usable options.
   *
   * @since    1.0.0
   */
  public static function sanitize_options($options){
    $sanitized = array();
    foreach($options as $key => $value){
      if($key == '' || $value == ''){ continue; }
      $sanitized[$key] = $value;
    }
    if(!isset($sanitized['per_page'])){ 
      $sanitized['per_page'] = self::DEFAULT_PER_PAGE; 
    }
    
    return $sanitized;
  }

}