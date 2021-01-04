<!DOCTYPE html>
<html>
  <head>
    <title>iFuse: Sort</title>
    <base href="<?php echo(base_url()); ?>" />
    <link rel="StyleSheet" href="<?php base_url()?>css/default.css" type="text/css" media="screen,print">
    <link rel="stylesheet" type="text/css" href="<?php base_url()?>css/jqcontextmenu.css" />
    <script type="text/javascript" src="<?php base_url()?>js/jQuery_v1.7.js"></script>
    <script type="text/javascript" src="<?php base_url()?>js/jqcontextmenu.js"></script>
  </head>
  <body class="test">
    <!--<?php
      $sortSegments = getOneURLSegment($this->uri->segment_array(),'sort:');
      $segments     = array();
      $ascdesc      = 'desc';
      
      foreach ($sortSegments as $seg) {
          if (preg_match("/=/",$seg)) {
              $ex = explode("=",$seg,2);
              if (sizeof($ex) == 2) {
                  $segments[$ex[0]] = $ex[1];
              } else {
                  $segments[$ex[0]] = (isset($segments[$ex[0]]) ? $segments[$ex[0]] : null);
              }
          } else {
              $segments[$ex] = (isset($segments[$ex[0]]) ? $segments[$ex[0]] : null);
          }
      }
      
      $left = array(
        "Row.names"              => "By Event ID",
        "Gene.Left.chrom"        => "By Chromosomes Left",
        "Gene.Right.chrom"       => "By Chromosomes Right",
        "Shared.Genes"           => "By Shared Genes",
        "Associated.Junctions"   => "By Associated Junctions",
        "Related.Junctions"      => "By Related Junctions",
        "Junction.LeftPosition"  => "By Junction Position Left",
        "Junction.RightPosition" => "By Junction Position Right",
        "Gene.Mismatch"          => "By Gene Mismatch",
        "Single.Event"           => "By Event Type",
        "Fusion.Gene"            => "By Gene Orientation",
        "Left.Position.in.CDS"   => "By Left Junction in CDS",
        "Right.Position.in.CDS"  => "By Right Junction in CDS",
        "Left.Position.in.Exon"  => "By Left Junction in Exon",
        "Right.Position.in.Exon" => "By Right Junction in Exon"
      );
    ?>-->
    <form onsubmit="return false;">
      <script type="text/javascript">
          var NS4 = (navigator.appName == "Netscape" && parseInt(navigator.appVersion) < 5);
            
          function addOption(theSel, theText, theValue) {
              var newOpt = new Option(theText, theValue);
              var selLength = theSel.length;
              theSel.options[selLength] = newOpt;
          }

          function deleteOption(theSel, theIndex) { 
              var selLength = theSel.length;
              if(selLength>0) {
                  theSel.options[theIndex] = null;
              }
          }

          function moveOptions(theSelFrom, theSelTo) {
              var selLength = theSelFrom.length;
              var selectedText = new Array();
              var selectedValues = new Array();
              var selectedCount = 0;
            
              var i;
           
            // Find the selected Options in reverse order
            // and delete them from the 'from' Select.
              for(i=selLength-1; i>=0; i--) {
                  if(theSelFrom.options[i].selected) {
                      selectedText[selectedCount] = theSelFrom.options[i].text;
                      selectedValues[selectedCount] = theSelFrom.options[i].value;
                      deleteOption(theSelFrom, i);
                      selectedCount++;
                  }
              }
            
            // Add the selected text/values in reverse order.
            // This will add the Options to the 'to' Select
            // in the same order as they were in the 'from' Select.
              for(i=selectedCount-1; i>=0; i--) {
                  addOption(theSelTo, selectedText[i], selectedValues[i]);
              }
          
              if(NS4) history.go(0);
          }
          
          function moveOptionsUp(selectId) {
               var selectList = document.getElementById(selectId);
               var selectOptions = selectList.getElementsByTagName('option');
               for (var i = 1; i < selectOptions.length; i++) {
                   var opt = selectOptions[i];
                   if (opt.selected) {
                       selectList.removeChild(opt);
                       selectList.insertBefore(opt, selectOptions[i - 1]);
                     }
               }
          }
          
          function moveOptionsDown(selectId) {
              var selectList = document.getElementById(selectId);
              var selectOptions = selectList.getElementsByTagName('option');
              for (var i = selectOptions.length - 2; i >= 0; i--) {
                  var opt = selectOptions[i];
                  if (opt.selected) {
                      var nextOpt = selectOptions[i + 1];
                      opt = selectList.removeChild(opt);
                      nextOpt = selectList.replaceChild(opt, nextOpt);
                      selectList.insertBefore(nextOpt, opt);
                  }
              }
          }
      </script>
      <table class="borders" width="100%">
        <tr style="background: #ccc; font-weight:bold;">
          <td colspan="2">Other columns</td>
          <td colspan="2">Sort by</td>
        </tr>
        <tr>
          <td style="text-align:left; width:49%;">
            <select multiple="multiple" style="width:100%; height:280px!important;" id="pre_order">
              <?php
                $right = array();
                foreach ($segments as $key => $value) {
                    if (isset($left[$key])) {
                        $right[$key] = $left[$key];
                        unset($left[$key]);
                    }
                    $ascdesc = $value;
                }
                
                //$left = array_values($left);
                foreach ($left as $key => $value) {
                    echo("<option value=\"{$key}\">{$value}</option>\n");
                }
              ?>
            </select>
          </td>
          <td colspan="2">
              <table>
                <tr>
                  <td colspan="2"><button onclick="javascript:moveOptionsUp(  'order');" style="width:25px; height:25px; overflow:hidden;" title="Up">&uarr;</button></td>
                </tr>
                <tr>
                  <td><button onclick="javascript:moveOptions(document.getElementById('order'),document.getElementById('pre_order'));" style="width:25px; height:25px; overflow:hidden;" title="Left">&larr;</button></td>
                  <td><button onclick="javascript:moveOptions(document.getElementById('pre_order'),document.getElementById('order'));" style="width:25px; height:25px; overflow:hidden;" title="Right">&rarr;</button></td>
                </tr>
                <tr>
                  <td colspan="2"><button onclick="javascript:moveOptionsDown('order');" style="width:25px; height:25px; overflow:hidden;" title="Down">&darr;</button></td>
                </tr>
              </table>
          </td>
          <td style="text-align:left; width:49%;">
            <select multiple="multiple" style="width: 100%; height:280px!important;" id="order">
              <?php
                foreach ($right as $key => $value) {
                    echo("<option value=\"{$key}\">{$value}</option>\n");
                }
              ?>
            </select>
          </td>
        </tr>
        <tr>
          <td colspan="4">
            <script type="text/javascript">
              function submitform() {
                  var sort = 'sort:';
                  var options = document.getElementById('order').options;
                  var ascdesc = '<?php echo($ascdesc); ?>';
                  
                  for (i=0; i<options.length; i++) {
                      sort += ((i!=0) ? '&' : '') + options[i].getAttribute('value');
                  }
                  
                  url = null;
                  try{
                      url = this.parent.document.URL;
                      var segments = url.split("/");
                      var newurl = new Array();
                      
                      for (i=0; i<segments.length; i++) {
                          if (segments[i].indexOf(sort) != 0) {
                              newurl[newurl.length] = segments[i];
                          }
                      }
                      
                      parent.document.location = newurl.join("/") + '/' + sort + (ascdesc!=''?'='+ascdesc:'');
                  } catch(e){
                      var ee = e.message || 0;
                      alert('Error: \n\n'+e+'\n'+ee);
                  }
              }
            </script>
            <input type="submit" value="Submit" onclick="javascript:submitform();">
          </td>
        </tr>
      </table>
    </form>
    <!-- page render time: {elapsed_time} -->
  </body>
</html>
