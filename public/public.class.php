<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://purecharity.com
 * @since      1.0.0
 *
 * @package    Purecharity_Wp_Fundraisers
 * @subpackage Purecharity_Wp_Fundraisers/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Purecharity_Wp_Fundraisers
 * @subpackage Purecharity_Wp_Fundraisers/public
 * @author     Pure Charity <dev@purecharity.com>
 */
class Purecharity_Wp_Fundraisers_Public {

  /**
   * The Fundraise.
   *
   * @since    1.0.0
   * @access   public
   * @var      string    $fundraiser    The Fundraiser.
   */
  public static $fundraiser;

  /**
   * The Fundraisers collection.
   *
   * @since    1.0.0
   * @access   public
   * @var      string    $fundraisers    The Fundraisers collection.
   */
  public static $fundraisers;

  /**
   * The options.
   *
   * @since    1.0.0
   * @access   public
   * @var      string    $options    The options.
   */
  public static $options;

  /**
   * The ID of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $plugin_name    The ID of this plugin.
   */
  private $plugin_name;

  /**
   * The version of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $version    The current version of this plugin.
   */
  private $version;

  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   * @var      string    $plugin_name       The name of the plugin.
   * @var      string    $version    The version of this plugin.
   */
  public function __construct( $plugin_name, $version ) {

    $this->plugin_name = $plugin_name;
    $this->version = $version;

  }

  /**
   * Register the stylesheets for the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function enqueue_styles() {
    wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/public.css', array(), $this->version, 'all' );
  }

  /**
   * Register the stylesheets for the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function enqueue_scripts() {
    wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/public.js', array( 'jquery' ), $this->version, false );
  }

  /**
   * Not found layout for listing display.
   *
   * @since    1.0.0
   */
  public static function list_not_found($default = true){
    $html = '<p class="fr-not-found" style="'. ( $default ? '' : 'display:none' ) .'">No Fundraisers Found.</p>' . ($default ? Purecharity_Wp_Base_Public::powered_by() : '');
    return $html;
  }

  /**
   * Not found layout for single display.
   *
   * @since    1.0.0
   */
  public static function not_found(){
    return "<p>Fundraiser Not Found.</p>" . Purecharity_Wp_Base_Public::powered_by();;
  }

  /**
   * Live filter for table.
   *
   * @since    1.0.0
   */
  public static function live_search(){

    $options = get_option( 'purecharity_fundraisers_settings' );

    // var_dump($options);
    // exit;

    if(isset($options["live_filter"]) && (empty(self::$options['hide_search']) || self::$options['hide_search'] != 'true')){
      $html = '
        <div class="fr-filtering">
          <form method="get">
            <fieldset class="livefilter fr-livefilter">
              <legend>
                <label for="livefilter-input">
                  <strong>Search Fundraisers:</strong>
                </label>
              </legend>
              <input id="livefilter-input" class="fr-livefilter-input" value="'.@$_GET['query'].'" name="query" type="text">
              <button class="fr-filtering-button" type="submit">Filter</button>
              '. (@$_GET['query'] != '' ? '<a href="#" onclick="jQuery(this).prev().prev().val(\'\'); jQuery(this).parents(\'form\').submit(); return false;">Clear filter</a>' : '') .'
            </fieldset>
          </form>
        </div>
      ';
    }else{
      $html = '';
    }
    return $html;
  }

  /**
   * List of Fundraisers, grid option.
   *
   * @since    1.0.0
   */
  public static function listing(){

    $options = get_option( 'purecharity_fundraisers_settings' );

    $html = self::print_custom_styles() ;
    $html .= '
      <div class="fr-list-container">
        '.self::live_search().'
        <table class="fundraiser-table option1">
          <tr>
              <th colspan="2">Fundraiser Name</th>
            </tr>
    ';
    $i = 0;

    $used = array();
    foreach(self::$fundraisers->external_fundraisers as $fundraiser){
      if(!in_array($fundraiser->id, $used)){
        array_push($used, $fundraiser->id);
        $title = $fundraiser->name;
        if(isset(self::$options['title']) && self::$options['title'] == 'owner_name'){
          $title = $fundraiser->owner->name;
        }
        if(isset(self::$options['title']) && self::$options['title'] == 'title_and_owner_name'){
          $title = $fundraiser->name.' by '.$fundraiser->owner->name;
        }

        $class = $i&1 ? 'odd' : 'even';
        $i += 1;
        $html .= '
          <tr class="row '.$class.' fundraiser_'.$fundraiser->id.'">
              <td>'.$title.'</td>
              <td>
                <a class="fr-themed-link" href="?fundraiser='.$fundraiser->slug.'">More Info</a>
                <a class="donate
                " href="'.Purecharity_Wp_Base_Public::pc_url().'/fundraisers/'.$fundraiser->id.'/fund">Donate Now</a>
            </td>
           </tr>
        ';
      }
    }

      $html .= '
      </table>
        '.self::list_not_found(false).'
      </div>
    ';
    $html .= Purecharity_Wp_Base_Public::powered_by();

    return $html;
  }

  /**
   * List of Fundraisers.
   *
   * @since    1.0.0
   */
  public static function listing_grid($opts){

    $layout = empty($opts['layout']) ? 1 : $opts['layout'];

    self::$options = get_option( 'purecharity_fundraisers_settings' );

    switch ((int) $layout) {
      case 1:
        return self::grid_option_1();
        break;
      case 2:
        return self::grid_option_2();
        break;
      case 3:
        return self::grid_option_3();
        break;
      case 4:
        return self::grid_option_4();
        break;
      default:
        return self::grid_option_1();
        break;
    }
  }

  /**
   * Grid listing layout option 1.
   *
   * @since    2.0
   */
  public static function grid_option_1(){
    $html = self::print_custom_styles() ;
    $html .= '<div class="fr-list-container pure_centered pure_row is-grid">'.self::live_search();
    $html .= '<div>'; 

    $used = array();
    $counter = 1;
    foreach(self::$fundraisers->external_fundraisers as $fundraiser){
      if(!in_array($fundraiser->id, $used)){
        array_push($used, $fundraiser->id);

        $title = $fundraiser->name;
        if(isset(self::$options['title']) && self::$options['title'] == 'owner_name'){
          $title = $fundraiser->owner->name;
        }
        if(isset(self::$options['title']) && self::$options['title'] == 'title_and_owner_name'){
          $title = $fundraiser->name.'<br /> by '.$fundraiser->owner->name;
        }

        if ($fundraiser->images->medium == NULL) {
          $image = $fundraiser->images->large;
        }else{
          $image = $fundraiser->images->medium;
        }

        $funded = self::percent(($fundraiser->funding_goal-$fundraiser->funding_needed) ,$fundraiser->funding_goal);
        $html .= '
          <div class="fr-grid-list-item pure_span_6 pure_col fundraiser_'.$fundraiser->id.'">
            <div class="fr-grid-list-content">
        ';

        $html .= '
          <div class="fr-listing-avatar-container pure_span24">
            <div class="fr-listing-avatar" href="#" style="background-image: url('.$image.')">
              <a href="?fundraiser='.$fundraiser->slug.'" class="overlay-link"></a>
            </div>
          </div>
        ';

        $html .='
          <div class="fr-grid-item-content pure_col pure_span_24">
            <div class="fr-grid-title-container">
              <p class="fr-grid-title">'.$title.'</p>
              <p class="fr-grid-desc">'.strip_tags(truncate($fundraiser->about, 100)).'</p>
            </div>
            '.self::grid_funding_stats($fundraiser).'
          </div>
          <div class="fr-actions pure_col pure_span_24">
            <a class="fr-themed-link" href="?fundraiser='.$fundraiser->slug.'">More</a>
            <a class="fr-themed-link" target="_blank" href="'.Purecharity_Wp_Base_Public::pc_url().'/fundraisers/'.$fundraiser->id.'/fund">Donate</a>
          </div>
        ';

        $html .= '
            </div>
          </div>
        ';
        if($counter %4 == 0){
          $html .= '<hr class="hidden"></hr>';
        }
        $counter ++;
      }
    }

    $html .= self::list_not_found(false);
    $html .= '</div>';
    $html .= '</div>';
    $html .= Purecharity_Wp_Fundraisers_Paginator::page_links(self::$fundraisers->meta);
    $html .= Purecharity_Wp_Base_Public::powered_by();

    return $html;
  }

  /**
   * Grid listing layout option 2.
   *
   * @since    2.0
   */
  public static function grid_option_2(){
    $html = self::print_custom_styles() ;
    $html .= '<div class="fr-list-container pure_centered pure_row is-grid">'.self::live_search();
    $html .= '<div>'; 

    $used = array();
    $counter = 1;
    foreach(self::$fundraisers->external_fundraisers as $fundraiser){
      if(!in_array($fundraiser->id, $used)){
        array_push($used, $fundraiser->id);

        $title = $fundraiser->name;
        if(isset(self::$options['title']) && self::$options['title'] == 'owner_name'){
          $title = $fundraiser->owner->name;
        }
        if(isset(self::$options['title']) && self::$options['title'] == 'title_and_owner_name'){
          $title = $fundraiser->name.'<br /> by '.$fundraiser->owner->name;
        }

        if ($fundraiser->images->medium == NULL) {
          $image = $fundraiser->images->large;
        }else{
          $image = $fundraiser->images->medium;
        }

        $funded = self::percent(($fundraiser->funding_goal-$fundraiser->funding_needed) ,$fundraiser->funding_goal);
        $html .= '
          <div class="fr-grid-list-item pure_span_8 pure_col fundraiser_'.$fundraiser->id.'">
            <div class="fr-grid-list-content">
              <div class="fr-listing-avatar-container extended pure_span24">
                <div class="fr-listing-avatar" href="#" style="background-image: url('.$image.')">
                  <a href="?fundraiser='.$fundraiser->slug.'" class="overlay-link"></a>
                </div>
              </div>
              <div class="fr-grid-item-content pure_col pure_span_24">
                <div class="fr-grid-title-container">
                  <p class="fr-grid-title extended">'.$title.'</p>
                  <p class="fr-grid-desc extended">'.strip_tags(truncate($fundraiser->about, 150)).'</p>
                </div>
                '.self::grid_funding_stats($fundraiser, 2).'
              </div>
              <div class="fr-actions extended pure_col pure_span_24">
                <a class="fr-themed-link" href="?fundraiser='.$fundraiser->slug.'">More</a>
                <a class="fr-themed-link" target="_blank" href="'.Purecharity_Wp_Base_Public::pc_url().'/fundraisers/'.$fundraiser->id.'/fund">Donate</a>
              </div>
            </div>
          </div>
        ';
        if($counter %3 == 0){
          $html .= '<div class="clearfix"></div>';
        }
        $counter ++;
      }
    }

    $html .= self::list_not_found(false);
    $html .= '</div>';
    $html .= '</div>';
    $html .= Purecharity_Wp_Fundraisers_Paginator::page_links(self::$fundraisers->meta);
    $html .= Purecharity_Wp_Base_Public::powered_by();

    return $html;
  }

  /**
   * Grid listing layout option 3.
   *
   * @since    2.0
   */
  public static function grid_option_3(){
    $html = self::print_custom_styles() ;
    $html .= '<div class="fr-list-container pure_centered pure_row is-grid">'.self::live_search();
    $html .= '<div>'; 

    $used = array();
    $counter = 1;
    foreach(self::$fundraisers->external_fundraisers as $fundraiser){
      if(!in_array($fundraiser->id, $used)){
        array_push($used, $fundraiser->id);

        $title = $fundraiser->name;
        if(isset(self::$options['title']) && self::$options['title'] == 'owner_name'){
          $title = $fundraiser->owner->name;
        }
        if(isset(self::$options['title']) && self::$options['title'] == 'title_and_owner_name'){
          $title = $fundraiser->name.'<br /> by '.$fundraiser->owner->name;
        }

        if ($fundraiser->images->medium == NULL) {
          $image = $fundraiser->images->large;
        }else{
          $image = $fundraiser->images->medium;
        }

        $funded = self::percent(($fundraiser->funding_goal-$fundraiser->funding_needed) ,$fundraiser->funding_goal);
        $html .= '
          <div class="fr-grid-list-item pure_span_8 pure_col no-border fundraiser_'.$fundraiser->id.'">
            <div class="fr-grid-list-content">
              <div class="fr-listing-avatar-container extended pure_span24">
                <div class="fr-listing-avatar" href="#" style="background-image: url('.$image.')">
                  <a href="?fundraiser='.$fundraiser->slug.'" class="overlay-link"></a>
                </div>
              </div>
              <div class="fr-grid-item-content simplified pure_col pure_span_24">
                <div class="fr-grid-title-container">
                  <p class="fr-grid-title extended simplified">'.$title.'</p>
                  <p class="fr-grid-desc extended simplified">'.strip_tags(truncate($fundraiser->about, 150)).'</p>
                </div>
              </div>
              <div class="fr-actions extended simplified no-border pure_col pure_span_24">
                <a class="fr-themed-link" href="?fundraiser='.$fundraiser->slug.'">More</a>
                <a class="fr-themed-link" target="_blank" href="'.Purecharity_Wp_Base_Public::pc_url().'/fundraisers/'.$fundraiser->id.'/fund">Donate</a>
              </div>
            </div>
          </div>
        ';
        if($counter %3 == 0){
          $html .= '<div class="clearfix"></div>';
        }
        $counter ++;
      }
    }

    $html .= self::list_not_found(false);
    $html .= '</div>';
    $html .= '</div>';
    $html .= Purecharity_Wp_Fundraisers_Paginator::page_links(self::$fundraisers->meta);
    $html .= Purecharity_Wp_Base_Public::powered_by();

    return $html;
  }

  /**
   * Grid listing layout option 4.
   *
   * @since    2.4
   */
  public static function grid_option_4(){

    $html = self::print_custom_styles() ;
    $html .= '<div class="fr-list-container pure_centered pure_row is-grid">'.self::live_search().'</div>'; 
    $html .= '<div class="pure_col no-padding">';

    $used = array();
    $counter = 1;
    foreach(self::$fundraisers->external_fundraisers as $fundraiser){
      if(!in_array($fundraiser->id, $used)){
        array_push($used, $fundraiser->id);

        $title = $fundraiser->name;
        if(isset(self::$options['title']) && self::$options['title'] == 'owner_name'){
          $title = $fundraiser->owner->name;
        }
        if(isset(self::$options['title']) && self::$options['title'] == 'title_and_owner_name'){
          $title = $fundraiser->name.'<br /> by '.$fundraiser->owner->name;
        }

        if ($fundraiser->images->large == NULL) {
          $image = $fundraiser->images->medium;
        }else{
          $image = $fundraiser->images->large;
        }

        $funded = self::percent(($fundraiser->funding_goal-$fundraiser->funding_needed) ,$fundraiser->funding_goal);
        $html .= '    
          <div class="pure_span_8 pure_col no-border fundraiser_'.$fundraiser->id.'"">
            <div class="family">
              <a href="?fundraiser='. $fundraiser->slug .'" class="cover" style="background-image: url('. $image .');">
              </a>
              <div class="caption">
                <h3><a href="?fundraiser='. $fundraiser->slug .'">'. $title .'</a></h3>
                <span class="location">is adopting from '. $fundraiser->country .'</span>
                <span class="raised">'. money_format('$%i', $fundraiser->funding_goal-$fundraiser->funding_needed).' Raised</span>
              </div>
            </div>
          </div>
        ';

        if($counter %3 == 0){
          $html .= '<div class="clearfix"></div>';
        }
        $counter ++;
      }
    }
    $html .= '</div>';
    $html .= self::list_not_found(false);
    $html .= Purecharity_Wp_Fundraisers_Paginator::page_links(self::$fundraisers->meta);
    $html .= Purecharity_Wp_Base_Public::powered_by();
    
    return $html;
  }

  /**
   * Single fundraiser list item - layout 4
   *
   * @since    2.4
   */
  public static function featured_fundraiser(){

    $used = array();
    $counter = 1;

    $title = self::$fundraiser->name;
    if(isset(self::$options['title']) && self::$options['title'] == 'owner_name'){
      $title = self::$fundraiser->owner->name;
    }
    if(isset(self::$options['title']) && self::$options['title'] == 'title_and_owner_name'){
      $title = self::$fundraiser->name.'<br /> by '.self::$fundraiser->owner->name;
    }

    if (self::$fundraiser->images->large == NULL) {
      $image = self::$fundraiser->images->medium;
    }else{
      $image = self::$fundraiser->images->large;
    }

    $funded = self::percent((self::$fundraiser->funding_goal-self::$fundraiser->funding_needed) ,self::$fundraiser->funding_goal);
    $html .= '    
      <div class="pure_span_8 pure_col no-border fundraiser_'.self::$fundraiser->id.'"">
        <div class="family">
          <a href="?fundraiser='. self::$fundraiser->slug .'" class="cover" style="background-image: url('. $image .');">
          </a>
          <div class="caption">
            <h3><a href="?fundraiser='. self::$fundraiser->slug .'">'. $title .'</a></h3>
            <span class="location">is adopting from '. self::$fundraiser->country .'</span>
            <span class="raised">'. money_format('$%i', self::$fundraiser->funding_goal-self::$fundraiser->funding_needed).' Raised</span>
          </div>
        </div>
      </div>
    ';
    
    return $html;
  }

  /**
   * List of Last Fundraisers.
   *
   * @since    1.0.1
   */
  public static function listing_last_grid(){

    $options = get_option( 'purecharity_fundraisers_settings' );

    $html = self::print_custom_styles() ;
    $html .= '<div class="fr-list-container is-grid">';

    $used = array();
    foreach(self::$fundraisers->external_fundraisers as $fundraiser){
      if(!in_array($fundraiser->id, $used)){
        array_push($used, $fundraiser->id);

        $title = $fundraiser->name;
        if(isset(self::$options['title']) && self::$options['title'] == 'owner_name'){
          $title = $fundraiser->owner->name;
        }
        if(isset(self::$options['title']) && self::$options['title'] == 'title_and_owner_name'){
          $title = $fundraiser->name.'<br /> by '.$fundraiser->owner->name;
        }

        $html .= '
          <div class="fr-grid-list-item fundraiser_'.$fundraiser->id.'">
            <div class="fr-grid-list-content">
              <div class="fr-listing-avatar-container">
                  <div class="fr-listing-avatar" href="#" style="background-image: url('.$fundraiser->images->large.')"></div>
                </div>
              <div class="fr-grid-item-content">
              <p class="fr-grid-title">'.$title.'</p>
              <p class="fr-grid-desc">'.$fundraiser->about.'</p>
              '.self::grid_funding_stats($fundraiser).'
            </div>
            <div class="fr-actions pure_col pure_span_24">
              <a class="fr-themed-link" href="?fundraiser='.$fundraiser->slug.'">More</a>
              <a class="fr-themed-link" target="_blank" href="'.Purecharity_Wp_Base_Public::pc_url().'/fundraisers/'.$fundraiser->id.'/fund">Donate</a>
            </div>
          </div>
          </div>
        ';
      }      
    }

    $html .= self::list_not_found(false);
    $html .= '</div>';

    return $html;
  }

  /**
   * Single Fundraisers.
   *
   * @since    1.0.0
   */
  public static function show(){

    $title = self::$fundraiser->name;
    if(isset(self::$options['title']) && self::$options['title'] == 'owner_name'){
      $title = self::$fundraiser->owner->name;
    }
    if(isset(self::$options['title']) && self::$options['title'] == 'title_and_owner_name'){
      $title = self::$fundraiser->name.' by '.self::$fundraiser->owner->name;
    }

    $url = Purecharity_Wp_Base_Public::pc_url().'/fundraisers/'.$fundraiser->id.'/fund';
    $share_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $summary = $fundraiser->name .' is raising money for their adoption from '. $fundraiser->country; 

    $options = get_option( 'purecharity_fundraisers_settings' );

    $html = self::print_custom_styles() ;

    if(isset(self::$options["layout"]) && ((int)self::$options['layout'] == 4)){
      # ADOPTIONS ONLY
      $html .= '
        <div class="wrapper">
          <div class="pure_row families show">
            <div class="pure_col pure_span_16">
              <div class="family-details">
                <img  alt="'. self::$fundraiser->name .' is raising money on AdoptTogether for their adoption from '. self::$fundraiser->country .'." 
                      class="img-responsive" 
                      src="'. self::$fundraiser->images->large .'">
                <h1 class="title">'. self::$fundraiser->name .'</h1>
                <h2 class="subtitle">is adopting a child from '. self::$fundraiser->country .'</h2>
                <div class="description"><p>'. self::$fundraiser->descrition .'</p></div>
                <div class="pure_col">
                  '. self::grid_4_pieces('adoption_status') .'
                  '. self::grid_4_pieces('adoption_agency') .'
                </div>
                '. self::grid_4_pieces('about') .'
                '. self::grid_4_pieces('updates') .'
              </div>
            </div>

            <aside class="pure_col pure_span_8">
              <div class="raised">
                <h3>Raised</h3>
                <span class="total-raised"> '. money_format('$%i', (self::$fundraiser->funding_goal-self::$fundraiser->funding_needed)) .'</span>
                <span class="goal">of '. money_format('$%i', (self::$fundraiser->funding_goal)) .' Goal</span>
                <a class="pcbtn pcbtn-primary pcbtn-lg pcbtn-block" href="'. $url .'">Give to this Adoption</a>
              </div>
              <div class="share-buttons">
                <div class="pure_row no-padding">
                  <div class="pure_col pure_span_12 share-button">
                    <a class="facebook" href="https://www.facebook.com/sharer/sharer.php?u='. $share_url .'">Facebook</a>
                  </div>
                  <div class="pure_col pure_span_12 share-button">
                    <a class="twitter" href="https://twitter.com/home?status='. $summary .'%21%20'. $share_url .'">Twitter</a>
                  </div>
                </div>
                <div class="pure_row no-padding">
                  <div class="pure_col pure_span_12 share-button">
                    <a class="google-plus" href="https://plus.google.com/share?url='. $share_url .'">Google +</a>
                  </div>
                  <div class="pure_col pure_span_12 share-button">
                    <a class="linkedin" href="https://www.linkedin.com/shareArticle?mini=true&url='. $share_url .'&summary='. $summary .'&source=">LinkedIn</a>
                  </div>
                </div>
                <div class="pure_row no-padding">
                  <div class="pure_col pure_span_12 share-button">
                  <a class="pinterest" href="https://pinterest.com/pin/create/button/?url='. $url .'&media='. self::$fundraiser->images->large .'&description='. $summary .'}">Pinterest</a>
                  </div>
                  <div class="pure_col pure_span_12 share-button">
                  <a class="email" href="mailto:?&subject=Check the '. self::$fundraiser->name .' fundraising campaign&body='. $summary .'%0A%0AThanks!">Email</a>
                  </div>
                </div>
                <input type="text" name="url" id="url" value="'. $share_url .'" class="form-control" onclick="this.setSelectionRange(0, this.value.length)">
              </div>
              '. self::grid_4_pieces('backers') .'
            </aside>
            
          </div>
        </div>
      ';
    }else{
      $html .= '
        <div class="pure_row">
          <div class="fr-top-row pure_col pure_span_24">
            <div class="fr-name pure_col pure_span_18">
              <h3>'.$title.'</h3>
            </div>
            <div class="fr-donate mobile-hidden fr-donate-top pure_col pure_span_6">
              <a class="fr-pure-button" href="'.Purecharity_Wp_Base_Public::pc_url().'/fundraisers/'.self::$fundraiser->id.'/fund">Donate</a>
            </div>
          </div>
          <div class="fr-container pure_col pure_span_24 fundraiser_'.self::$fundraiser->id.'">
            <div class="fr-header pure_col pure_span_24">
              <img src="'.self::$fundraiser->images->large.'">
            </div>
            <div class="fr-middle-row pure_col pure_span_24">
              <div class="fr-avatar-container pure_col pure_span_5">
                <div class="fr-avatar" href="#" style="background-image: url('.self::$fundraiser->images->small.')"></div>
              </div>
              <div class="fr-info pure_col pure_span_13">
                <p class="fr-location">'.self::$fundraiser->country.'</p>
                  <p class="fr-organizer">
                    Organized by <a class="fr-themed-link" href="'.Purecharity_Wp_Base_Public::pc_url().'/'.self::$fundraiser->field_partner->slug.'">'.self::$fundraiser->field_partner->name.'</a>
                  </p>
              </div>
              <div class="fr-donate pure_col pure_span_6">
                <a class="fr-pure-button" href="'.Purecharity_Wp_Base_Public::pc_url().'/fundraisers/'.self::$fundraiser->id.'/fund">Donate</a>
                '. (isset($options['fundraise_cause']) ?  '' : '<a class="fr-p2p" href="'.Purecharity_Wp_Base_Public::pc_url().'/'.self::$fundraiser->slug.'/copies/new">Start a Fundraiser for this Cause</a>') .'
              </div>
            </div>
            '. self::single_view_funding_bar() .'
            '. self::single_view_funding_div() .'
            '. self::single_view_tabs() .'
          </div>
        </div>
      ';
    }

    $html .= Purecharity_Wp_Base_Public::powered_by();
    return $html;
  }

  /**
   * Confitional layout pieces for layout 4 (grid, single view)
   *
   * @since    2.4
   */
  public static function grid_4_pieces($piece = null){
    if($piece == null) { return ''; }
    $html = '';
    switch($piece){

      case 'adoption_status':
        if(!empty(self::$fundraiser->adoption_status)){ 
          $html = '
            <div class="pure_span_12">
              <h4>Adoption Status</h4>
              <h3>'. self::$fundraiser->adoption_status .'</h3>
            </div><hr>'; 
        }
        break;

      case 'adoption-agency':
        if(!empty(self::$fundraiser->adoption_agency)){ 
          $html = '
          <div class="pure_span_12 agency">
            <h4>Adoption Agency</h4>
            <h3>'. self::$fundraiser->adoption_agency .'</h3>
            <span class="website"><a target="_blank" href="http://www.bethany.org">http://www.bethany.org</a></span>
          </div><hr>';
        }
        break;

      case 'about':
        if(!empty(self::$fundraiser->about)){ 
          $html = '
            <hr>
            <div class="row">
              <div class="pure_span_24">
                <h2>About</h2>
                <p>'. self::$fundraiser->about .'</p>
              </div>
            </div>';
          }
        break;

      case 'updates':
        $html = '';
        foreach(self::$fundraiser->updates as $update){ 
          $html .= '
            <div class="row">
              <div class="pure_span_24">
                <hr>
                <h3>
                  <a href="'. $update->title .'">'. $update->title .'</a>
                  <small>by '. $update->author->name .'</small>
                </h3>
                <p class="overflow-hidden">'. $update->body .'</p>
              </div>
            </div>
          '; 
        }
        break;

      case 'backers':
        $count = count(self::$fundraiser->backers);
        if($count > 0){
          $html = '<h2>'. $count . ' ' . pluralize(count(self::$fundraiser->backers), 'Donation') .' <small>from:</small></h2>'; 
          $html .= '<ul class="list-unstyled">';
          foreach(self::$fundraiser->backers as $backer){ 
            $html .= '
              <li class="donor">
                <h5>'. $backer->name .'</h5>
              </li>
            '; 
          }
          $html .= '</ul>';
        }
        break;
    }

    return $html;

  }

  /**
   * Funding stats for grid listing.
   *
   * @since    1.0.5
   */
  public static function grid_funding_stats($fundraiser, $layout = 1){
    $klass = ($fundraiser->funding_goal != 'anonymous' && ($fundraiser->recurring_funding_goal != NULL && $fundraiser->recurring_funding_goal != 'anonymous')) ? 'pure_span_12' : 'pure_span_24';
    $html = '';
    if($fundraiser->funding_goal != 'anonymous'){
      $funded = self::percent(($fundraiser->funding_goal-$fundraiser->funding_needed) ,$fundraiser->funding_goal);
      $html .= '
        <div class="pure_col '.$klass.'">
          <div class="fr-grid-status-title pure_col pure_span_24" title="'.$funded.'">
            <span>One-time donations:</span>
          </div>
          <div class="fr-grid-status pure_col pure_span_24" title="'.$funded.'">
            <div class="fr-grid-progress" style="width:'.$funded.'%"></div>
          </div>
          <div class="fr-grid-stats '.( $layout == 2 ? 'extended' : '' ).' pure_col pure_span_24">
            <p>
              $'.number_format(($fundraiser->funding_goal-$fundraiser->funding_needed), 0, '.', ',').'
              of
              $'.number_format($fundraiser->funding_goal, 0, '.', ',').'
              raised
            </p>
          </div>
        </div>
      ';
    }

    if($fundraiser->recurring_funding_goal != NULL && $fundraiser->recurring_funding_goal != 'anonymous'){
      $funded = self::percent(($fundraiser->recurring_funding_goal-$fundraiser->recurring_funding_needed) ,$fundraiser->recurring_funding_goal);
      $html .= '
        <div class="pure_col '.$klass.'">
          <div class="fr-grid-status-title pure_col pure_span_24" title="'.$funded.'">
            <span>Recurring donations:</span>
          </div>
          <div class="fr-grid-status pure_col pure_span_24" title="'.$funded.'">
            <div class="fr-grid-progress" style="width:'.$funded.'%"></div>
          </div>
          <div class="fr-grid-stats '.( $layout == 2 ? 'extended' : '' ).' pure_col pure_span_24">
            <p>
              $'.number_format(($fundraiser->funding_goal-$fundraiser->funding_needed), 0, '.', ',').'
              of
              $'.number_format($fundraiser->funding_goal, 0, '.', ',').'
              raised
            </p>
          </div>
        </div>
      ';
    }
    return $html;
  }

  /**
   * Funding bar for single view.
   *
   * @since    1.0.5
   */
  public static function single_view_funding_bar(){ 
    include('includes/single-view-funding-bar.php'); 
    return $html; 
  }

  /**
   * Funding stats for single view.
   *
   * @since    1.0.5
   */
  public static function single_view_funding_div(){
    include('includes/single-view-funding-div.php');
    return $html;
  }

  /**
   * Sharing links for single view.
   *
   * @since    1.0.5
   */
  public static function single_view_tabs(){
    include('includes/single-view-tabs.php');
    return $html;
  }



  /**
   * Backers list.
   *
   * @since    1.0.0
   */
  public static function print_backers(){
    if(sizeof(self::$fundraiser->backers) == 0){
      $html = '<p>There are no backers at this time.</p>';
    }else{
      $html = '<ul class="fr-backers pure_col pure_span_24">';
      foreach(self::$fundraiser->backers as $backer){
        $html .= '
          <li class="pure_col pure_span_6">
            <span class="fr-avatar fr-backer-avatar" href="#" style="background-image: url('.$backer->avatar.')"></span>
            <span class="fr-backer-name"><a class="fr-themed-link" href="'.Purecharity_Wp_Base_Public::pc_url().'/'.$backer->slug.'">'.$backer->name.'</a></span>
          </li>
        ';
      }
      $html .= '</ul>';
    }
    return $html;
  }

  /**
   * Updates list.
   *
   * @since    1.0.0
   */
  public static function print_updates(){
    if(sizeof(self::$fundraiser->updates) == 0){
      $html = '<p>There are no updates at this time.</p>';
    }else{
      $html = '<ul class="fr-updates">';
      foreach(self::$fundraiser->updates as $update){
        $html .= '
          <li>
              <h4><a class="fr-themed-link" href="'.$update->url.'">'.$update->title.'</a></h4>
              <p class="date">Posted a week ago</p>
              <p>'.$update->body.'</p>
              <span class="fr-author">
                <p>Posted by:<br/><a class="fr-themed-link" href="'.Purecharity_Wp_Base_Public::pc_url().'/'.$update->author->slug.'">'.$update->author->name.'</a></p>
              </span>
              <span class="fr-read-more">
                <a class="fr-read-more" href="'.$update->url.'">Read More</a><!-- links to update on pure charity -->
              </span>
            </li>
        ';
      }
      $html .= '</ul>';
    }
    return $html;
  }


  public static function print_custom_styles(){
    $base_settings = get_option( 'pure_base_settings' );
    $pf_settings = get_option( 'purecharity_fundraisers_settings' );

    // Default theme color
    if($pf_settings['plugin_color'] == NULL || $pf_settings['plugin_color'] == ''){
      if($base_settings['main_color'] == NULL || $base_settings['main_color'] == ''){
        $color = '#CA663A';
      }else{
        $color = $base_settings['main_color'];
      }
    }else{
      $color = $pf_settings['plugin_color'];
    }

    $html = '<style>';
    $html .= '
      .fundraiser-table a.donate { background: '.$color.' !important; }
      .fr-grid-progress { background: '.$color.' !important; }
      .fr-grid-list-item ul.fr-list-actions li a:hover { background: '.$color.' !important; }
      a.fr-pure-button { background: '.$color.' !important; }
      .fr-single-progress { background: '.$color.' !important; }
      #fr-tabs ul.fr-tabs-list li.active a, #fr-tabs ul.fr-tabs-list li a:hover {border-color: '.$color.' !important;}
      .fr-themed-link { color: '.$color.' !important; }
      .fr-filtering button { background: '.$color.' }
    ';
    $html .= '</style>';

    return $html;
  }


  /**
   * Updates list.
   *
   * @since    1.0.0
   */


  /**
   * Percentage calculator.
   *
   * @since    1.0.0
   */
  public static function percent($num_amount, $num_total) {
    if($num_total == 0){ return 100; }
    return number_format((($num_amount / $num_total) * 100), 0);
  }
}
