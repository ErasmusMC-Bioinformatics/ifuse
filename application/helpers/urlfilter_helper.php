<?php
  /**
  * put your comment there...
  * 
  * @param mixed $filter
  * @param mixed $column
  * @param mixed $data
  * @return string
  */
  function filterURL($filter,$column,$data) {
      $CI = get_instance();
      return cleanupURL(site_url(implode("/",array_merge($CI->uri->segment_array(),array("$filter:$column=$data")))));
  }
  
  
  /**
  * put your comment there...
  * 
  * @param mixed $url
  * @return string
  */
  function cleanupURL ($url) {
      $url = is_array($url) ? implode("/",$url):(is_string($url)?$url:"");
      $cleanup = "/^(not:|only:|sort:|group:|page:)/";
      $segments = preg_split("/[\/|\\\]/",$url);
      $url      = array();
      $addtourl = array();
      
      
      for ($i=0; $i<sizeof($segments); $i++) {
          if (preg_match($cleanup,$segments[$i],$matches)) {
              $addtourl[strtolower($matches[0])][] = (($i==0)?$segments[$i]:preg_replace("/^".$matches[0]."/","",$segments[$i],1));
          } else {
              $url[] = $segments[$i];
          }
      }
      
      $key = array_keys($addtourl);
      for ($i=0; $i<sizeof($addtourl); $i++) {
          $addtourl[$key[$i]] = $key[$i].implode("&",$addtourl[$key[$i]]);
      }
      
      return implode("/",array_merge($url,$addtourl));
  }
  
  /**
  * put your comment there...
  * 
  * @param string $segment
  */
  function getOneURLSegment($url, $segmentid) {
      $url = is_array($url) ? implode("/",$url):(is_string($url)?$url:"");
      $cleanup  = "/^(not:|only:|sort:|group:|page:)/";
      $segments = preg_split("/[\/|\\\]/",$url);;
      $segs     = array();
      
      for ($i=0; $i<sizeof($segments); $i++) {
          if (preg_match($cleanup,$segments[$i],$matches)) {
              $segs[strtolower($matches[0])][] = preg_replace("/^".$matches[0]."/","",$segments[$i],1);
          }
      }
      
      $key = array_keys($segs);
      for ($i=0; $i<sizeof($segs); $i++) {
          $segs[$key[$i]] = implode("&",$segs[$key[$i]]);
      }
      
      return explode("&",(isset($segs[$segmentid]) ? $segs[$segmentid] : ""));
  }
