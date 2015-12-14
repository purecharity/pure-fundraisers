<?php 
/**
 * Template tags for fundraisers
 *
 * @link       http://purecharity.com
 * @since      1.0.0
 *
 * @package    Purecharity_Wp_Fundraisers
 * @subpackage Purecharity_Wp_Fundraisers/includes
 */

/**
 * Fundraisers listing.
 *
 * For more information, please refer to the readme.
 *
 * @since    1.0.1
 */
function pc_last_fundraisers($options){
  $base_plugin = new Purecharity_Wp_Base();

  if(isset($_GET['fundraiser'])){
    $opt = array();
    $opt['fundraiser'] = $_GET['fundraiser'];
    return Purecharity_Wp_Fundraisers_Shortcode::fundraiser_shortcode($opt);
  }else{

    $query_var = array();

    if(isset($options['limit']) && $options['limit'] != ''){
      $query_var[] = 'limit='.$options['limit'];
    }else{
      $query_var[] = 'limit=4';
    }
    
    $fundraisers = $base_plugin->api_call('external_fundraisers?' . join('&', $query_var));

    if ($fundraisers && count($fundraisers) > 0) {
      Purecharity_Wp_Fundraisers_Public::$fundraisers = $fundraisers; 
      return Purecharity_Wp_Fundraisers_Public::listing_last_grid();
    }else{
      return Purecharity_Wp_Fundraisers_Public::list_not_found();        
    };
  }
}

/**
 * Fundraiser info.
 *
 * For more information, please refer to the readme.
 *
 * @since    1.0.1
 */
function pc_fundraiser_info($fundraiser){
  $base_plugin = new Purecharity_Wp_Base();
  $tt_fundraiser = $base_plugin->api_call('fundraisers/show?fundraiser='. $fundraiser)->fundraiser;
  return $tt_fundraiser;
}