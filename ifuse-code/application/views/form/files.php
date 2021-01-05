<!DOCTYPE html>
<html>
  <head>
    <title>iFuse: Files</title>
    <base href="<?php echo(base_url()); ?>" />
    <link rel="StyleSheet" href="<?php base_url()?>css/default.css" type="text/css" media="screen, print">
    <link rel="stylesheet" type="text/css" href="<?php base_url()?>css/jqcontextmenu.css" />
    <script type="text/javascript" src="<?php base_url()?>js/jQuery_v1.7.js"></script>
    <script type="text/javascript" src="<?php base_url()?>js/jqcontextmenu.js"></script>
  </head>
  <body style="padding-bottom:35px;">
    <?php
      if ($do = $this->uri->segment(3)) {
          $do = preg_split("/:/",$do,2);
          
          if (sizeof($do) == 2) {
              if (strtolower($do[0]) == 'active') {
                  $this->userfiles->setConfig('CURRENT_FILE', $do[1]);
              } elseif (strtolower($do[0]) == 'delete') {
                  $this->userfiles->removeFile($do[1]);
              }
          }
      }
    ?>
    <table class="borders" width="100%">
      <tr style="background: #ccc; font-weight:bold;">
        <td style="width:30px;">#</td>
        <td>Name</td>
        <td style="width:300px;">Options</td>
        <td style="width:150px;">Upload Time</td>
      </tr>
      <?php
        $n   = $this->userfiles->getConfig('FILES_AMOUNT');
        $cn  = $this->userfiles->getConfig('CURRENT_FILE');
        $row = 0;
        if ($n >=0) {
            //for ($i=0; $i <= $n; $i++) {
            for ($i=$n; (($i <= $n) && ($i >= 1)); $i--) {
                $cur_name  = $this->userfiles->getConfig("FILES[$i][CUR_NAME]" );
                $timestamp = $this->userfiles->getConfig("FILES[$i][TIMESTAMP]");
                $org_name  = $this->userfiles->getConfig("FILES[$i][ORG_NAME]" );
                $options   = $this->userfiles->getConfig("FILES[$i][OPTIONS]"  );
                
//		echo $i . ": (" . file_exists($this->userfiles->getFile($i)) . ") " . $this->userfiles->getFile($i) . "<br>\n";
		
                if (!empty($cur_name) && file_exists($this->userfiles->getFile($i))) {
                    $row++;
                    echo("<tr id=\"obj_$i\"".($i==$cn?" style=\"background-color:#efe\"":"")."><td>$row");
                    ?>
        <!-- right mouse button menus -->
        <script type="text/javascript">
            jQuery(document).ready(function($){
                $('tr#obj_<?php echo($i); ?>').addcontextmenu('contextmenu<?php echo($i); ?>') //apply context menu to all images on the page
            });
        </script>
        <ul id="contextmenu<?php echo($i); ?>" class="jqcontextmenu">
          <li><a href="<?php echo(site_url("form/files/active:$i")); ?>">Set as active</a></li>
          <li><a href="javascript:void(0);" onclick="javascript:if(confirm('Are you sure you want to delete this file?')){window.location = '<?php echo(site_url("form/files/delete:$i")); ?>';}">Delete</a></li>
          <li><a href="<?php echo(base_url("/TMP/" . $_SESSION["TIMESTAMP"] . "/" . $cur_name )); ?>">Download Fuse Raw data file</a></li>
        </ul>
                    
                    <?php
                    echo("</td><td>$org_name</td><td>");
                    $options = preg_split("/;/",$options);
                    
                    $header = null;
                    $value  = null;
                    
                    for ($j=0; $j<sizeof($options); $j++) {
                        $options[$j] = preg_split("/:/",$options[$j],2);
                        if (sizeof($options[$j]) == 2) {
                            $header .= "<td>{$options[$j][0]}</td>";
                            $value  .= "<td style=\"width:".floor(100/sizeof($options[$j]))."px;\">{$options[$j][1]}</td>";
                        }
                    }
                    
                    echo(sizeof($options)?"<small><table style=\"width:100%;\" class=\"borders\"><tr style=\"background:#e8e8e8; font-style: italic; text-transform:capitalize;\">{$header}</tr><tr style=\"background-color:#fff;\">{$value}</tr></table></small>":"<small><i>none</i></small>");
                    echo("</td><td><small>".RelativeTime(strtotime($timestamp))."</small></td></tr>\n");
                }
            }
        }
        if ($row == 0) {
            echo("<tr><td colspan=\"4\"><small><i>No files to display</i></small></td></tr>");
        }
      ?>
    </table>
    <?php if ($row > 0) { echo("<div style=\"position:fixed; bottom:0px; left:0px;padding-left:5px;width:100%;border-top:1px solid #000; background-color:#fff;\"><small>Use the right mouse button to activate or delete a file.</small></div>");} ?>
    <!-- page render time: {elapsed_time} -->
  </body>
</html>
