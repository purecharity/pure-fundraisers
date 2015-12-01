<?php
  include('single-view-vars.php');
  if($has_none){ return ''; }
  
  $klass = self::$options['standalone_bar'] ? 'no-border' : '';

  $html = '<div class="fr-intro pure_col pure_span_24 '.$klass.'">';

  if(self::$fundraiser->funding_goal != 'anonymous'){
    $funded = self::percent((self::$fundraiser->funding_goal-self::$fundraiser->funding_needed) ,self::$fundraiser->funding_goal);
    $html .= '
      <div class="fr-single-status-section pure_col ' . $bar_span_class . '">
        <span class="title">One-time donations:</span>
        <div class="fr-single-status pure_col pure_span_24">
          <div class="fr-single-progress" style="width:'.$funded.'%"></div>
          <div class="fr-raised pure_col pure_span_24">
            <span class="fr-raised-label">Amount Raised: </span><span class="fr-raised-amount">$'.number_format((self::$fundraiser->funding_goal-self::$fundraiser->funding_needed), 0, '.', ',').'</span>
          </div>
        </div>
      </div>
    ';
  }

  if($has_recurring){
    $funded = self::percent((self::$fundraiser->recurring_funding_goal-self::$fundraiser->recurring_funding_needed) ,self::$fundraiser->recurring_funding_goal);
    $html .= '
      <div class="fr-single-status-section pure_col ' . $bar_span_class . '">
        <span class="title">Recurring donations:</span>
        <div class="fr-single-status pure_col pure_span_24">
          <div class="fr-single-progress" style="width:'.$funded.'%"></div>
          <div class="fr-raised pure_col pure_span_24">
            <span class="fr-raised-label">Amount Raised: </span><span class="fr-raised-amount">$'.number_format((self::$fundraiser->recurring_funding_goal-self::$fundraiser->recurring_funding_needed), 0, '.', ',').'</span>
          </div>
        </div>
      </div>
    ';
  }

  $html .= '</div>';