<?php 
  include('single-view-vars.php');
  $funded = self::percent((self::$fundraiser->funding_goal-self::$fundraiser->funding_needed) ,self::$fundraiser->funding_goal);

  $html = '
    <div class="fr-body '. $tabs_span_class .' pure_col">
      <div id="fr-tabs" class="pure_col pure_span_24">
         <ul class="fr-tabs-list pure_col pure_span_24">
           <li><a class="fr-themed-link" href="#tab-1">About</a></li>
           '. (isset($options['updates_tab']) ?  '' : '<li><a class="fr-themed-link" href="#tab-2">Updates</a></li>') .'
           '. (isset($options['backers_tab']) ?  '' : '<li><a class="fr-themed-link" href="#tab-3">Backers</a></li>') .'
         </ul>
         <div id="tab-1" class="tab-div pure_col pure_span_24">'.self::$fundraiser->about.'</div>
         <div id="tab-2" class="tab-div pure_col pure_span_24">
            '.self::print_updates().'
         </div>
         <div id="tab-3" class="tab-div pure_col pure_span_24"><!-- we will need to be able check a box to hide this tab / info in the admin of the plugin -->

            '.self::print_backers().'

         </div>
       </div>
    </div>
  ';
  if($has_none || $has_both){

    $title = self::$fundraiser->name;
    if(isset($options['title']) && $options['title'] == 'owner_name'){
      $title = self::$fundraiser->owner->name;
    }
    if(isset($options['title']) && $options['title'] == 'title_and_owner_name'){
      $title = self::$fundraiser->name.' by '.self::$fundraiser->owner->name;
    }

    $html .= '
      <div class="fr-body pure_span_4 pure_col text-centered">
        <strong>'.$date_diff.'</strong><br/> <span class="fr-stat-title">Days to Go</span><br /><br />
        '.Purecharity_Wp_Base_Public::sharing_links(array(), self::$fundraiser->about, $title, self::$fundraiser->images->large).'
        <a target="_blank" href="'.Purecharity_Wp_Base_Public::pc_url().'/'.self::$fundraiser->slug.'">
          <img src="' . plugins_url( '../images/share-purecharity.png', __FILE__ ) . '" >
        </a>
      </div>
    ';
  }