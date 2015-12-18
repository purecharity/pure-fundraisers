<?php
  $options = get_option( 'purecharity_fundraisers_settings' );
  if(@self::$fundraiser){
    $has_one_time = (self::$fundraiser->funding_goal != NULL && self::$fundraiser->funding_goal != 'anonymous');
    $has_recurring = (self::$fundraiser->recurring_funding_goal != NULL && self::$fundraiser->recurring_funding_goal != 'anonymous');
    $has_both = ($has_one_time && $has_recurring);
    $has_none = (!$has_one_time && !$has_recurring);
    $has_one = (($has_one_time || $has_recurring) && !$has_both);
    $bar_span_class = ($has_both ? 'pure_span_12' : 'pure_span_24'); 
    $div_span_class = ($has_both ? 'pure_span_6' : 'pure_span_5 text-left'); 
    $tabs_span_class = (($has_both || $has_none) ? 'pure_span_20' : 'pure_span_24'); 

    $end_date = new DateTime(self::$fundraiser->end_date);
    $today = new DateTime;
    $date_diff = round(($end_date->format('U') - $today->format('U')) / (60*60*24))+1;
  }