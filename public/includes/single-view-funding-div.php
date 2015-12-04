<?php
  include('single-view-vars.php');

  $html = '';
  if($has_one_time || $has_recurring){
    $html .= '
      <div class="fr-single-info pure_col pure_span_24">
        <ul class="fr-single-stats pure_col pure_span_24">';
    if($has_one_time){
      $html .=' <li class="pure_col ' . $div_span_class . '"><strong>$'.number_format(self::$fundraiser->funding_goal, 0, '.', ',').'</strong><br/> <span class="fr-stat-title">One-time Goal</span></li>
                <li class="pure_col ' . $div_span_class . '"><strong>$'.number_format(self::$fundraiser->funding_needed, 0, '.', ',').'</strong><br/> <span class="fr-stat-title">One-time Still Needed</span></li>'; 
    }
    if($has_recurring){
      $html .=' <li class="pure_col ' . $div_span_class . '"><strong>$'.number_format(self::$fundraiser->recurring_funding_goal, 0, '.', ',').'</strong><br/> <span class="fr-stat-title">Recurring Goal</span></li>
                <li class="pure_col ' . $div_span_class . '"><strong>$'.number_format(self::$fundraiser->recurring_funding_needed, 0, '.', ',').'</strong><br/> <span class="fr-stat-title">Recurring Still Needed</span></li>';  
    }
    
    if(!$has_both){
      $html .='
        <li class="pure_col pure_span_10"><strong>'.$date_diff.'</strong><br/> <span class="fr-stat-title">Days to Go</span></li>
        <li class="pure_col pure_span_4 sharing_links">
          '.Purecharity_Wp_Base_Public::sharing_links(array(), self::$fundraiser->name." Fundraisers").'
          <a target="_blank" href="'.Purecharity_Wp_Base_Public::pc_url().'/'.self::$fundraiser->slug.'">
            <img src="' . plugins_url( '../images/share-purecharity.png', __FILE__ ) . '" >
            </a>
        </li>
      '; 
    }

    $html .='
        </ul>
      </div>
    ';
  }