<?php
  function RelativeTime($timestamp, $reverse = false){
        $difference = time() - $timestamp;
        $periods = array("sec", "min", "hour", "day", "week", "month", "years", "decade");
        $lengths = array("60","60","24","7","4.35","12","10");

        if ($difference > 0) { // this was in the past
            $ending = !$reverse?" ago":" to go";
        } else { // this is in the future
            $difference = -$difference;
            $ending = !$reverse?" to go":" ago";
        }
        for($j = 0; $difference >= $lengths[$j]; $j++)
        $difference /= $lengths[$j];
        $difference = round($difference);
        if($difference != 1) $periods[$j].= "s";
        $text = "{$difference} {$periods[$j]}{$ending}";
        return $text;
    }
?>