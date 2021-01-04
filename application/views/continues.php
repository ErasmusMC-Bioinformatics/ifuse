<?php $url = (file_exists($this->fileName) && is_file($this->fileName))?site_url('analyse'):base_url();
header('location: '.$url);
?>
