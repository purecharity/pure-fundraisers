<?php

/**
 * Used on public display of the Fundraiser(s)
 *
 * @link       http://purecharity.com
 * @since      1.0.0
 *
 * @package    Purecharity_Wp_Fundraisers
 * @subpackage Purecharity_Wp_Fundraisers/includes
 */

/**
 * Used on public display of the Fundraiser(s).
 *
 * This class defines all the shortcodes necessary.
 *
 * @since      1.0.0
 * @package    Purecharity_Wp_Fundraisers
 * @subpackage Purecharity_Wp_Fundraisers/includes
 * @author     Pure Charity <dev@purecharity.com>
 */
class Purecharity_Wp_Fundraisers_Shortcode {


  /**
   * The Base Plugin.
   *
   * @since    1.0.0
   * @access   public
   * @var      Object    $base_plugin    The Base Plugin.
   */
  public static $base_plugin;

  /**
   * Initialize the class and Base Plugin functionality.
   *
   * @since    1.0.0
   */
  public function __construct() {
    $this->actions = array();
    $this->filters = array();

  }

  /**
   * Initialize the shortcodes to make them available on page runtime.
   *
   * @since    1.0.0
   */
  public static function init()
  {
    if(Purecharity_Wp_Fundraisers::base_present()){
      add_shortcode('fundraisers', array('Purecharity_Wp_Fundraisers_Shortcode', 'fundraisers_shortcode'));
      add_shortcode('fundraisers_search', array('Purecharity_Wp_Fundraisers_Shortcode', 'fundraisers_search_shortcode'));
      add_shortcode('last_fundraisers', array('Purecharity_Wp_Fundraisers_Shortcode', 'last_fundraisers_shortcode'));
      add_shortcode('fundraiser', array('Purecharity_Wp_Fundraisers_Shortcode', 'fundraiser_shortcode'));
      add_shortcode('fundraiser_funding_bar', array('Purecharity_Wp_Fundraisers_Shortcode', 'fundraiser_funding_bar_shortcode'));
      add_shortcode('featured_fundraiser', array('Purecharity_Wp_Fundraisers_Shortcode', 'featured_fundraiser_shortcode'));
      add_shortcode('pure_col', array('Purecharity_Wp_Fundraisers_Shortcode', 'pure_col_shortcode'));

      self::$base_plugin = new Purecharity_Wp_Base();
    }
  }

  /**
   * Initialize the Last Fundraisers Listing shortcode.
   *
   * @since    1.0.1
   */
  public static function last_fundraisers_shortcode($atts)
  {
    $options = shortcode_atts( array(
      'fundraiser' => false,
      'query' => get_query_var('query'),
      'dir' => get_query_var('dir'),
      'order' => get_query_var('order'),
      'title' => get_query_var('title'),
      'limit' => get_query_var('limit'),
      'layout' => get_query_var('layout') # [1, 2, 3, 4]
    ), $atts );
    if(isset($_GET['fundraiser'])){
      $opt = array();
      $opt['slug'] = $_GET['fundraiser'];
      $opt['layout'] = $options['layout'];
      return self::fundraiser_shortcode($opt);
    }else{

      $query_var = array();

      if(isset($options['limit']) && $options['limit'] != ''){
        $query_var[] = 'limit='.$options['limit'];
      }else{
        $query_var[] = 'limit=4';
      }

      if(isset($options['order']) && $options['order'] != ''){
        if(isset($options['title']) && $options['title'] == 'owner_name'){
          $query_var[] = 'sort=founder';
        }else{
          $query_var[] = 'sort='.$options['order'];
        }
      }

      if(isset($options['dir']) && $options['dir'] != ''){
        $query_var[] = 'dir='.$options['dir'];
      }

      if(isset($options['query']) && $options['query'] != ''){
        $query_var[] = 'query=' . urlencode($options['query']);
      }

      $fundraisers = self::$base_plugin->api_call('external_fundraisers?' . join('&', $query_var));

      if ($fundraisers && count($fundraisers) > 0) {
        Purecharity_Wp_Fundraisers_Public::$fundraisers = $fundraisers;
        return Purecharity_Wp_Fundraisers_Public::listing_last_grid();
      }else{
        return Purecharity_Wp_Fundraisers_Public::list_not_found();
      };
    }
  }

    /**
     * Initialize the Fundraisers Search shortcode.
     *
     * @since    2.5.0
     */

    public static function fundraisers_search_shortcode($atts)
    {
        $options = shortcode_atts([
            'page_id' => get_query_var('page_id')
        ], $atts);

        if ($options['page_id'] != 0 && get_permalink($options['page_id'])) {
            $html = '
            <form class="fundraisers-search-form" action="' . get_permalink($options['page_id']) . '">
                <input type="text" name="query" placeholder="Search for an Adopting Family">
                <button type="submit">Search</button>
            </form>';
        } else {
            $html = 'Sorry but you need to set page id where exist fundraisers plugin';
        }

        return $html;
    }

  /**
   * Initialize the Fundraisers Listing shortcode.
   *
   * @since    1.0.0
   */
  public static function fundraisers_shortcode($atts)
  {
    $options = shortcode_atts( array(
      'fundraiser' => false,
      'query' => get_query_var('query'),
      'grid' => get_query_var('grid'),
      'title' => get_query_var('title'),
      'campaign' => get_query_var('campaign'),
      'per_page' => get_query_var('per_page'),
      'dir' => get_query_var('dir'),
      'order' => get_query_var('order'),
      'hide_search' => get_query_var('hide_search'),
      'layout' => get_query_var('layout') # [1, 2, 3, 4]
    ), $atts );

    if(isset($_GET['fundraiser'])){
      $opt = array();
      $opt['slug'] = $_GET['fundraiser'];
      $opt['title'] = $options['title'];
      $opt['layout'] = $options['layout'];
      return self::fundraiser_shortcode($opt);
    }else{

      $query_var = array();
      if(isset($_GET['_page']) && $_GET['_page'] != ''){
        $query_var[] = 'page='.$_GET['_page'];
      }
      if(isset($options['per_page']) && $options['per_page'] != ''){
        $query_var[] = 'limit='.$options['per_page'];
      }
      if(isset($options['campaign']) && $options['campaign'] != ''){
        $query_var[] = 'campaign_id='.$options['campaign'];
      }
      if(isset($options['order']) && $options['order'] != ''){
        if(isset($options['title']) && $options['title'] == 'owner_name'){
          $query_var[] = 'sort=founder';
        }else{
          $query_var[] = 'sort='.$options['order'];
        }
      }
      if(isset($options['dir']) && $options['dir'] != ''){
        $query_var[] = 'dir='.$options['dir'];
      }

      if(isset($_GET['query']) && $_GET['query'] != ''){
        $query_var[] = 'query=' . urlencode($_GET['query']);
      }

      $fundraisers = self::$base_plugin->api_call('external_fundraisers?' . join('&', $query_var));

      if ($fundraisers && count($fundraisers) > 0) {
        Purecharity_Wp_Fundraisers_Public::$fundraisers = $fundraisers;
        Purecharity_Wp_Fundraisers_Public::$options = $options;
        if($options['grid'] == 'true'){
          return Purecharity_Wp_Fundraisers_Public::listing_grid($options);
        }else{
          return Purecharity_Wp_Fundraisers_Public::listing();
        }
      }else{
        return Purecharity_Wp_Fundraisers_Public::list_not_found();
      };
    }
  }

  /**
   * Featured Fundraiser shortcode.
   *
   * @since    2.4
   */
  public static function featured_fundraiser_shortcode($atts)
  {
    $options = shortcode_atts( array(
      'slug' => get_query_var('slug'),
      'title' => get_query_var('title')
    ), $atts );

    if ($options['slug']) {
      $fundraiser = self::$base_plugin->api_call('fundraisers/show?slug='. $options['slug']);
      if ($fundraiser) {
        $fundraiser = $fundraiser->fundraiser;
        Purecharity_Wp_Fundraisers_Public::$fundraiser = $fundraiser;
        Purecharity_Wp_Fundraisers_Public::$options = $options;
        return Purecharity_Wp_Fundraisers_Public::featured_fundraiser();
      }else{
        return Purecharity_Wp_Fundraisers_Public::not_found();
      }
    }
  }

  /**
   * Featured Fundraiser shortcode wrapper.
   *
   * @since    2.4
   */
  public static function pure_col_shortcode($atts, $content = null)
  {
    $options = shortcode_atts( array(
      'no_padding' => get_query_var('no_padding')
    ), $atts );
    $html .= '<div class="pure_col';
    $html .= $options['no_padding'] == true ? ' no-padding' : '';
    $html .= '">';
    $html .=   do_shortcode($content);
    $html .= '</div>';
  }

  /**
   * Initialize the Single Fundraiser shortcode.
   *
   * @since    1.0.0
   */
  public static function fundraiser_shortcode($atts)
  {
    $options = shortcode_atts( array(
      'slug' => get_query_var('slug'),
      'title' => get_query_var('title'),
      'layout' => get_query_var('layout')
    ), $atts );

    if ($options['slug']) {
      $fundraiser = self::$base_plugin->api_call('fundraisers/show?slug='. $options['slug']);
      if ($fundraiser) {
        $fundraiser = $fundraiser->fundraiser;
        Purecharity_Wp_Fundraisers_Public::$fundraiser = $fundraiser;
        Purecharity_Wp_Fundraisers_Public::$options = $options;
        return Purecharity_Wp_Fundraisers_Public::show();
      }else{
        return Purecharity_Wp_Fundraisers_Public::not_found();
      }
    }
  }

  /**
   * Initialize the Fundraiser Funding Bar shortcode.
   *
   * @since    1.2.1
   */
  public static function fundraiser_funding_bar_shortcode($atts)
  {
    $options = shortcode_atts( array(
      'fundraiser' => false,
      'standalone_bar' => true
    ), $atts );
    if ($options['fundraiser']) {
      $fundraiser = self::$base_plugin->api_call('fundraisers/show?slug='. $options['fundraiser']);

      if ($fundraiser) {
        $fundraiser = $fundraiser->fundraiser;
        Purecharity_Wp_Fundraisers_Public::$fundraiser = $fundraiser;
        Purecharity_Wp_Fundraisers_Public::$options = $options;
        return Purecharity_Wp_Fundraisers_Public::single_view_funding_bar();
      }else{
        return Purecharity_Wp_Fundraisers_Public::not_found();
      }

    }
  }

}
