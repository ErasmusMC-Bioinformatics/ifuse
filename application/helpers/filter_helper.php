<?php
  function filterURL($filter,$column,$data) {
      $CI = get_instance();
      return site_url(implode("/",array_merge($CI->uri->segment_array(),array("$filter:$column=$data"))));
  }
?>
