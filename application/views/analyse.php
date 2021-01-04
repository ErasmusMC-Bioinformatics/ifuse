<?php
  if (file_exists($this->fileName)) {
      function between($n, $range) {return (($n >= min($range)) && ($n <= max($range)));}
      ?>
        <!-- top level menu -->
        <div class="wrapper2" style="padding-bottom:1.5em;">
            <table class="borders" style="width:100%; background-color:#ccc; font-weight:bold;">
                <tr>
                    <td style="width:20%" onclick='parent.location="<?php echo(($this->uri->uri_string() == 'analyse') ? base_url() : site_url('analyse')); ?>"                                                                                                ' onmouseover="createHelp(this,'<?php echo(($this->uri->uri_string() == 'analyse') ? 'Back to the main/upload page' : 'Back to the analysis start page'); ?>')"><a href="javascript:void(0);">Home  </a></td>
                    <td style="width:20%" onclick='parent.location="<?php echo(site_url('logout')); ?>"                                                                                                                                                        ' onmouseover="createHelp(this,'Log yourself out from this system')"><a href="javascript:void(0);">Logout </a></td>
                    <td style="width:20%" onclick='javascript:iFrameBox("Sort"  ,"<?php $segs = $this->uri->segment_array(); array_shift($segs); echo(site_url("form/sort/"  .implode("/",$segs))); ?>","cancel",900,600);                                     ' onmouseover="createHelp(this,'Change columns to sort on...')     "><a href="javascript:void(0);">Sort  </a></td>
                    <td style="width:20%" onclick='javascript:iFrameBox("Files" ,"<?php                                                          echo(site_url("form/files/"                    )); ?>","Close" ,900,600, "javascript:location.reload(true);");' onmouseover="createHelp(this,'Switch between files...')          "><a href="javascript:void(0);">Files (<?php echo($this->userfiles->getFileCount()); ?>)</a></td>
                    <td style="width:20%" onclick='javascript:toggleDisplay(document.getElementById("legend"));                                                                                                                                                ' onmouseover="createHelp(this,'Toggle legend on/off...')          "><a href="javascript:void(0);">Legend</a></td>
                </tr>
            </table>
        </div>
        <div class="wrapper">
          <div style="position:absolute; right: -275px; width:250px;">
              <div style="margin-bottom:1.5em; position:fixed; width:250px;<?php $legend = $this->userfiles->getConfig('LEGEND_VISIBLE',''); echo !empty($legend) ? 'display:' . $legend : ''; ?>" id="legend">
                <?php echo !empty($legend) ? ($legend != 'none' ? '<style>.wrapper {margin-left:-36em!important;}</style>' : '') : '<style>.wrapper {margin-left:-36em!important;}</style>'; ?>
                <table style="width:100%; border-collapse:collapse;">
                  <tr style="background-color:#ddd; border:1px solid #000;">
                    <td style="text-align:center; font-weight:bold; padding:0px;">
                        Legend
                        <div onclick="javascript:window.open('<?php echo(site_url('fastaction').'/'.(empty($legend) ? 'LEGEND_VISIBLE:none' : 'LEGEND_VISIBLE:')); ?>','fastAction');" style="float: right; border-top: 20px solid rgb(0, 0, 0); border-left: 20px solid transparent;" onmouseover="createHelp(this,'Toggle legend on/off on all pages...')"></div>
                    </td>
                  </tr>
                  <tr style="border:1px solid #000; font-size:12px">
                    <td>
                      <table style="width:100%; font-variant:small-caps;">
                        <tr style="line-height:0px;">
                          <td style="width:25%;">&nbsp;</td>
                          <td style="width:37%;">&nbsp;</td>
                          <td style="width:37%;">&nbsp;</td>
                        </tr>
                        <tr>
                          <td style="text-align:right;">Color:</td>
                          <td><div style="background-color:#FF6600; width:80%; height:60%; color:#fff; text-align:center; border:1px solid #000;" onmouseover="createHelp(this,'Left sequence of the event: lowest coordinates. This may be a a donor if the right side also is a donor, or an acceptor.')">left</div></td>
                          <td><div style="background-color:#00A3C7; width:80%; height:60%; color:#fff; text-align:center; border:1px solid #000;" onmouseover="createHelp(this,'Right sequence of the event: highest coordinates. This may be a a donor if the left side also is a donor, or an acceptor.')">right</div></td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td><div style="background-color:#8CC700; width:80%; height:60%; color:#fff; text-align:center; border:1px solid #000;" onmouseover="createHelp(this,'Sequence that <i>donates</i> the promotor site to the other side.')">donor</div></td>
                          <td><div style="background-color:#C5007C; width:80%; height:60%; color:#fff; text-align:center; border:1px solid #000;" onmouseover="createHelp(this,'Sequence that <i>accepts</i> the promotor site from the other side.')">acceptor</div></td>
                        </tr>
                        <tr>
                          <td style="text-align:right;">Sequence:</td>
                          <td style="font-variant:normal;text-transform:uppercase;"><div style="width:80%; height:60%; text-align:center;" onmouseover="createHelp(this,'Left sequence of the event: lowest coordinates.')">left</div></td>
                          <td style="font-variant:normal;text-transform:lowercase;"><div style="width:80%; height:60%; text-align:center;" onmouseover="createHelp(this,'Right sequence of the event: highest coordinates.')">right</div></td>
                        </tr>
                        <tr>
                          <td style="text-align:right;">Exons:</td>
                          <td><div style="width:80%; height:60%; color:#000; text-align:center;" onmouseover="createHelp(this,'')">in event</div></td>
                          <td><div style="width:80%; height:60%; color:#888; text-align:center;" onmouseover="createHelp(this,'')">no event</div></td>
                        </tr>
                        <tr>
                          <td><center><svg version="1.1" viewBox="-10 -10 10 10" style="width:10px; height:10px;"><g transform="translate(-5,0)"><polygon style="fill:#0010A5;" points="-5,-7 -1,-5 -1,-10 1,-10 1,-5 5,-7 0,0"/></g></svg></center></td>
                          <td colspan="2">Breakpoint w/genes on the same strand</td>
                        </tr>
                        <tr>
                          <td><center><svg version="1.1" viewBox="-10 -10 10 10" style="width:10px; height:10px;"><g transform="translate(-5,-5)"><line style="stroke:#0010A5;" x1="-5" y1="-5" x2="5" y2="5"/><line style="stroke:#0010A5;" x1="-5" y1="5" x2="5" y2="-5"/></g></svg></center></td>
                          <td colspan="2">Breakpoint w/genes on different strands</td>
                        </tr>
                        <tr>
                          <td><center><svg version="1.1" viewBox="-10 -10 10 10" style="width:15px; height:15px;"><g transform="translate(-10, -15)"><polygon style="fill:#000000;" points="5,10 0,5 0,15"/></g></svg></center></td>
                          <td colspan="2">Positive stranded</td>
                        </tr>
                        <tr>
                          <td><center><svg version="1.1" viewBox="-10 -10 10 10" style="width:15px; height:15px;"><g transform="translate(-5, -15)"><polygon style="fill:#000000;" points="0,10 5,5 5,15"/></g></svg></center></td>
                          <td colspan="2">Negative stranded</td>
                        </tr>
                        <tr style="line-height:6px;">
                          <td colspan="3">&nbsp;</td>
                        </tr>
                        <tr style="border-bottom:1px solid #000;">
                          <td>&nbsp;</td>
                          <td colspan="2">Image explained</td>
                        </tr>
                        <tr>
                          <td colspan="3">In the image, the first line represents the left gene, the second line the right gene and the bottom line is the event. The top two genes both have on their left side the name of the gene and the start position. On their right side is the length of the gene and their stop position on the geneome. The event has on it's left side the names of both genes and on the right the length of the event.</td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr style="background-color:#ccc; border:1px solid #000;">
                    <td style="line-height:6px;">&nbsp;</td>
                  </tr>
                  <tr style="border:1px solid #000;">
                    <td id="helpLabel" style="line-height:19px;  font-size:12px; font-variant:small-caps;">
                      Tip: use your right mouse button to access more options.
                    </td>
                  </tr>
                </table>
              </div>
              <div style="width:250px; display:none;" id="Summary">
                <div style="width:100%; border-collapse:collapse;">
                  <table style="width:100%; border-collapse:collapse;">
                    <tr style="background-color:#ddd; border:1px solid #000;">
                      <td style="text-align:center; font-weight:bold; padding:0px;">
                         Summary
                         <div onclick="javascript:window.open('<?php echo(site_url('fastaction').'/'.(empty($legend) ? 'SUMMARY_VISIBLE:none' : 'SUMMARY_VISIBLE:')); ?>','fastAction');" style="float: right; border-top: 20px solid rgb(0, 0, 0); border-left    : 20px solid transparent;" onmouseover="createHelp(this,'Toggle summary on/off on all pages...')"></div>
                     </td>
                   </tr>
                   <tr style="border:1px solid #000; font-size:12px">
                     <td>
                       test
                     </td>
                   </tr>
                 </td>
               </table>
             </div>
           </div>
         </div>
   <?php
              $data  =& $this->ifuseloader->getData();
              $error =  $this->ifuseloader->getError();
              $this->ifuseloader->organize($this->ifuseloader->getData(false));
              
              if ($error[0] && (sizeof($error[1])>0)) {
                  echo("<div style=\"background-color:#ffcccc; border:1px solid #000; margin-bottom:1.5em;\" onclick='javascript:msgbox(\"Error\", \"<ol>");
                  foreach ($error[1] as $message) {
                      echo("<li>$message</li>");
                  }
                  echo("</ol>\", undefined, 800, 400);'><i>There ".(sizeof($error[1])>1?"are":"is")." ".sizeof($error[1])." error".(sizeof($error[1])>1?"s":""). " in this file. Click here to take a closer look.</i></div>");
              }
              
              if (is_array($data) && (sizeof($data) > 0)) { ?>
        <div>
          <?php 
          $onlyOne = (sizeof($data) == 1);
          
          // pagination {
          $pageSegment = getOneURLSegment($this->uri->uri_string(),"page:");
          $pageSegment = preg_split("/-/",$pageSegment[0],3);
          if (!(is_array($pageSegment) && sizeof($pageSegment) == 3)) {$pageSegment = array(0,10,'');}
          else {$pageSegment[0] = (int)$pageSegment[0];$pageSegment[1] = (int)$pageSegment[1];$pageSegment[2] = (string)$pageSegment[2];}
          
          
          $cur    = intval((empty($_POST) && !isset($pageSegment[0])) ?  '1' : $pageSegment[0]);
          $per    = intval((empty($_POST) && !isset($pageSegment[1])) ? '10' : $pageSegment[1]);
          $act    = substr(strtolower(isset($pageSegment[2])?$pageSegment[2]:''),0,1);
          
          $pages  = (floor((sizeof($data)-1)/$per)+1);
          $cur    = $act=='p'?$cur-1:($act=='n'?$cur+1:($act=='f'?1:($act=='l'?$pages:$cur)));
          $cur    = $cur<1?1:($cur>$pages?$pages:$cur);
          
          $offset = (($cur-1)*$per);
          // } pagination
          
          for ($i = $offset; (($i < ($offset+$per)) && ($i < sizeof($data))); $i++) {
              // SVG
$svg = '<?xml version="1.0" encoding="utf-8"?>' . (preg_replace("/[\n\r\t]/",'',$this->svg_gene->getSVG($this->svg_gene->getGeneInformationSVG($data[$i]))));
try {
    $image=new imagick();
    $image->readImageBlob($svg);
    $image->setImageFormat("png32");//or png24

    $data[$i]['svg'] = '<img src="data:image/png;base64,'. base64_encode($image).'">';
} catch (Exception $e) {
    $data[$i]['svg'] = ($svg . "<!-- {$e->getMessage()} -->");
}

              ?>
          <!-- right mouse button menus -->
          <script type="text/javascript">
              jQuery(document).ready(function($){
                  $('table#obj_<?php echo($i); ?>').addcontextmenu('contextmenu<?php echo($i); ?>') //apply context menu to all images on the page
              });
          </script>
          <ul id="contextmenu<?php echo($i); ?>" class="jqcontextmenu">
              <li><a href="javascript:toggleDisplay(document.getElementById('jid_<?php echo($i); ?>'));" onmouseover="createHelp(this,'Toggle display of detailed information')">Show/Hide</a>
                  <ul>
                      <li><a href="javascript:toggleDisplay(document.getElementById('jid_<?php echo($i); ?>'));" onmouseover="createHelp(this,'Toggle display of detailed information')">Details</a></li>
                      <li><a></a></li>
                      <li><a href="javascript:toggleDisplay(document.getElementById('SequenceInfo<?php echo($i); ?>'));" onmouseover="createHelp(this,'Toggle display of Event Sequences section (part of details)')">Event Sequences</a></li>
                      <li><a href="javascript:toggleDisplay(document.getElementById('ExonInfo<?php echo($i); ?>'));" onmouseover="createHelp(this,'Toggle display of Exons section (part of details)')">Exons</a></li>
                  </ul>
              </li>
              <li onmouseover="createHelp(this,'Filter events using different parameters')"><a>Filter</a>
                  <ul>
                      <li><a href="<?php echo(filterURL('only','Row.names',$data[$i]['Row.names'])); ?>">Show Only this Item</a></li>
                      <li><a href="<?php echo(filterURL('not', 'Row.names',$data[$i]['Row.names'])); ?>">Hide this Item</a></li>
                      <li><a></a></li>
                      <li><a>Using This Item</a>
                          <ul>
                              <li><a title="Junctions that are on the same chromosome">Shared Chromosomes</a>
                                  <ul>
                                      <li><a href="<?php echo(filterURL('only',  'Gene.Left.chrom',$data[$i][ 'Gene.Left.chrom'])); ?>">Left Chromosome is <?php  echo($data[$i]['Gene.Left.chrom' ]); ?></a></li>
                                      <?php if ($data[$i]['Gene.Left.chrom'] != $data[$i]['Gene.Right.chrom' ]) { ?><li><a href="<?php echo(filterURL('only',  'Gene.Left.chrom',$data[$i]['Gene.Right.chrom'])); ?>">Left Gene is <?php  echo($data[$i]['Gene.Right.chrom']); ?></a></li><?php } ?>
                                      <li><a href="<?php echo(filterURL('only', 'Gene.Right.chrom',$data[$i]['Gene.Right.chrom'])); ?>">Right Chromosome is <?php echo($data[$i]['Gene.Right.chrom']); ?></a></li>
                                      <?php if ($data[$i]['Gene.Left.chrom'] != $data[$i]['Gene.Right.chrom' ]) { ?><li><a href="<?php echo(filterURL('only', 'Gene.Right.chrom',$data[$i][ 'Gene.Left.chrom'])); ?>">Right Gene is <?php echo($data[$i]['Gene.Left.chrom' ]); ?></a></li><?php } ?>
                                      <li><a></a></li>
                                      <li><a href="<?php echo(filterURL('not',  'Gene.Left.chrom',$data[$i][ 'Gene.Left.chrom'])); ?>">Left Chromosome is not <?php  echo($data[$i]['Gene.Left.chrom' ]); ?></a></li>
                                      <?php if ($data[$i]['Gene.Left.chrom'] != $data[$i]['Gene.Right.chrom' ]) { ?><li><a href="<?php echo(filterURL('not',  'Gene.Left.chrom',$data[$i]['Gene.Right.chrom'])); ?>">Left Gene is not <?php  echo($data[$i]['Gene.Right.chrom']); ?></a></li><?php } ?>
                                      <li><a href="<?php echo(filterURL('not', 'Gene.Right.chrom',$data[$i]['Gene.Right.chrom'])); ?>">Right Chromosome is not <?php echo($data[$i]['Gene.Right.chrom']); ?></a></li>
                                      <?php if ($data[$i]['Gene.Left.chrom'] != $data[$i]['Gene.Right.chrom' ]) { ?><li><a href="<?php echo(filterURL('not', 'Gene.Right.chrom',$data[$i][ 'Gene.Left.chrom'])); ?>">Right Gene is not <?php echo($data[$i]['Gene.Left.chrom' ]); ?></a></li><?php } ?>
                                  </ul>
                              </li>
                              <li><a title="Junctions that have the same genes on either the left or right side">Shared Genes</a></a>
                                  <ul>
                                      <li><a href="<?php echo(filterURL('not',  'Shared.Genes',$data[$i]['Shared.Genes'])); ?>">Hide Shared Genes</a></li>
                                      <li><a href="<?php echo(filterURL('only', 'Shared.Genes',$data[$i]['Shared.Genes'])); ?>">Only These Shared Genes</a></li>
                                  </ul>
                              </li>
                              <li><a title="Junctions that land within the same gene">Associated Junctions</a></a>
                                  <ul>
                                      <li><a href="<?php echo(filterURL('not',  'Associated.Junctions',$data[$i]['Associated.Junctions'])); ?>">Hide Associated Junctions</a></li>
                                      <li><a href="<?php echo(filterURL('only', 'Associated.Junctions',$data[$i]['Associated.Junctions'])); ?>">Only Associated Junctions</a></li>
                                  </ul>
                              </li>
                              <li><a title="Junctions that are within 100bp of other junctions">Related Junctions</a></a>
                                  <ul>
                                      <li><a href="<?php echo(filterURL('not',  'Related.Junctions',$data[$i]['Related.Junctions'])); ?>">Hide Related Junctions</a></li>
                                      <li><a href="<?php echo(filterURL('only', 'Related.Junctions',$data[$i]['Related.Junctions'])); ?>">Only Related Junctions</a></li>
                                  </ul>
                              </li>
                          </ul>
                      </li>
                      <li><a>Using General Properties</a>
                          <ul>
                              <li><a>Gene Mismatch</a>
                                  <ul>
                                      <li><a href="<?php echo(filterURL('only', 'Gene.Mismatch', 'yes')); ?>">Yes</a></li>
                                      <li><a href="<?php echo(filterURL('only', 'Gene.Mismatch', 'no' )); ?>">No</a></li>
                                      <li><a href="<?php echo(filterURL('only', 'Gene.Mismatch', 'NA' )); ?>">N/A</a></li>
                                  </ul>
                              </li>
                              <li><a>Event Type</a>
                                  <ul>
                                      <li><a href="<?php echo(filterURL('only', 'Single.Event', 'interchromosomal'     )); ?>">Interchromosomal</a></li>
                                      <li><a href="<?php echo(filterURL('only', 'Single.Event', 'inversion or deletion')); ?>">Inversion or Deletion</a></li>
                                  </ul>
                              </li>
                              <li><a>Gene Orientation</a>
                                  <ul>
                                      <li><a href="<?php echo(filterURL('not' , 'Fusion.Gene', 'NA')); ?>">Both on same strand</a></li>
                                      <li><a href="<?php echo(filterURL('only', 'Fusion.Gene', 'NA')); ?>">Different strands</a></li>
                                  </ul>
                              </li>
                              <li><a>Junction in CDS</a>
                                  <ul>
                                      <li><a href="<?php echo(filterURL('only',  'Left.Position.in.CDS', 'yes')); ?>">Left in CDS</a></li>
                                      <li><a href="<?php echo(filterURL('only',  'Left.Position.in.CDS', 'no' )); ?>">Left not in CDS</a></li>
                                      <li><a href="<?php echo(filterURL('only', 'Right.Position.in.CDS', 'yes')); ?>">Right in CDS</a></li>
                                      <li><a href="<?php echo(filterURL('only', 'Right.Position.in.CDS', 'no' )); ?>">Right not in CDS</a></li>
                                  </ul>
                              </li>
                              <li><a>Junction in Exon</a>
                                  <ul>
                                      <li><a href="<?php echo(filterURL('only',  'Left.Position.in.Exon', 'yes')); ?>">Left in Exon</a></li>
                                      <li><a href="<?php echo(filterURL('only',  'Left.Position.in.Exon', 'no' )); ?>">Left not in Exon</a></li>
                                      <li><a href="<?php echo(filterURL('only', 'Right.Position.in.Exon', 'yes')); ?>">Right in Exon</a></li>
                                      <li><a href="<?php echo(filterURL('only', 'Right.Position.in.Exon', 'no' )); ?>">Right not in Exon</a></li>
                                  </ul>
                              </li>
                          </ul>
                      </li>
                  </ul>
              </li>
              <li onmouseover="createHelp(this,'Sort events using different parameters')"><a>Sort</a>
                  <ul>
                      <li><a href="<?php echo(filterURL('sort', 'Row.names'                                   , 'ASC')); ?>">By Event ID</a></li>
                      <li><a href="<?php echo(filterURL('sort', 'Gene.Left.chrom&Gene.Right.chrom'            , 'ASC')); ?>">By Shared Chromosomes</a></li>
                      <li><a href="<?php echo(filterURL('sort', 'Shared.Genes'                                , 'ASC')); ?>">By Shared Genes</a></li>
                      <li><a href="<?php echo(filterURL('sort', 'Associated.Junctions'                        , 'ASC')); ?>">By Associated Junctions</a></li>
                      <li><a href="<?php echo(filterURL('sort', 'Related.Junctions'                           , 'ASC')); ?>">By Related Junctions</a></li>
                      <li><a>By Junction Position</a>
                          <ul>
                              <li><a href="<?php echo(filterURL('sort', 'Junction.LeftPosition'               , 'ASC')); ?>">Left</a></li>
                              <li><a href="<?php echo(filterURL('sort', 'Junction.RightPosition'              , 'ASC')); ?>">Right</a></li>
                          </ul>
                      </li>
                      <li><a href="<?php echo(filterURL('sort', 'Gene.Mismatch'                               , 'ASC')); ?>">By Gene Mismatch</a></li>
                      <li><a href="<?php echo(filterURL('sort', 'Single.Event'                                , 'ASC')); ?>">By Event Type</a></li>
                      <li><a href="<?php echo(filterURL('sort', 'Fusion.Gene'                                 , 'ASC')); ?>">By Gene Orientation</a></li>
                      <li><a href="<?php echo(filterURL('sort', 'Left.Position.in.CDS&Right.Position.in.CDS'  , 'ASC')); ?>">By Junction in CDS</a></li>
                      <li><a href="<?php echo(filterURL('sort', 'Left.Position.in.Exon&Right.Position.in.Exon', 'ASC')); ?>">By Junction in Exon</a></li>
                  </ul>
              </li>
          </ul>
          
          <!-- everything else -->
          <table style="width:100%; font-size:75%; border:1px solid #000; margin-bottom:1.5em;" id="obj_<?php echo($i); ?>">
            <tr><td colspan="2" id="title" style="background-color: #bbb; font-size: 4px; border-bottom:1px solid #000;">&nbsp;</td></tr>
            <tr>
              <td title="Row number" style="width: 10%;font-variant:small-caps; border-right:1px solid #000;"><?php echo($data[$i]['Row.names']); ?></td>
              <td rowspan="4">
                <?php
                  echo($data[$i]['svg']);
                ?>
              </td>
            </tr>
            <tr><td title="Shared genes" style="width: 10%;font-variant:small-caps; border-right:1px solid #000;"><?php echo($data[$i]['Shared.Genes']); ?></td></tr>
            <tr><td title="Associated junctions" style="font-variant:small-caps; border-right:1px solid #000;"><?php echo($data[$i]['Associated.Junctions']); ?></td></tr>
            <tr><td title="Related junctions" style="font-variant:small-caps; border-right:1px solid #000;"><?php echo($data[$i]['Related.Junctions']); ?></td></tr>
            <tr id="jid_<?php echo($i); ?>" class="junction_info"<?php if(!$onlyOne) {echo(" style=\"display: none;\"");} ?>>
              <td colspan="2" style="padding:0px">
                <table style="border-collapse:collapse; width: 100%; border:0px solid #000; border-top-width:1px;">
                  <tr>
                    <td colspan="2" class="title" width="50%" style="border-right:1px solid #000; border-bottom:1px solid #000; vertical-align: middle; line-height:20px">
                      Left
                      <div style="border-left:20px solid transparent; border-top:20px solid <?php echo($data[$i][ 'left.color']); ?>; width:0px; float:right;"></div>
                    </td>
                    <td colspan="2" class="title" style="border-bottom:1px solid #000; vertical-align: middle; line-height:20px">
                      Right
                      <div style="border-left:20px solid transparent; border-top:20px solid <?php echo($data[$i]['right.color']); ?>; width:0px; float:right;"></div>
                    </td>
                  </tr>
                  <tr>
                    <td width="15%">Gene Name</td>
                    <td style="border-right:1px solid #000;"><?php echo((strtolower($data[$i]['Gene.Left.name2']) == 'na') ? '-' : $data[$i]['Gene.Left.name2'] . " (<a href=\"http://www.ncbi.nlm.nih.gov/nuccore/{$data[$i]['Gene.Left.name']}\" target=\"_blank\">{$data[$i]['Gene.Left.name']}</a>)"); ?></td>
                    <td width="15%">Gene Name</td>
                    <td><?php echo((strtolower($data[$i]['Gene.Right.name2']) == 'na') ? '-' : $data[$i]['Gene.Right.name2'] . " (<a href=\"http://www.ncbi.nlm.nih.gov/nuccore/{$data[$i]['Gene.Right.name']}\" target=\"_blank\">{$data[$i]['Gene.Right.name']}</a>)"); ?></td>
                  </tr>
                  <tr>
                    <td title="CDS">Coding Sequence</td>
                    <td style="border-right:1px solid #000;"><?php echo((strtolower($data[$i]['Gene.Left.name2']) == 'na') ? '-' : $data[$i]['Gene.Left.chrom'] . ":" .$data[$i]['Gene.Left.cdsStart'] . "-" . $data[$i]['Gene.Left.cdsEnd'] . "(" . $data[$i]['Gene.Left.strand'] . ")"); ?></td>
                    <td title="CDS">Coding Sequence</td>
                    <td><?php echo((strtolower($data[$i]['Gene.Right.name2']) == 'na') ? '-' : $data[$i]['Gene.Right.chrom'] . ":" .$data[$i]['Gene.Right.cdsStart'] . "-" . $data[$i]['Gene.Right.cdsEnd'] . "(" . $data[$i]['Gene.Right.strand'] . ")"); ?></td>
                  </tr>
                  <tr>
                    <td title="TX">Transcript</td>
                    <td style="border-right:1px solid #000;"><?php echo((strtolower($data[$i]['Gene.Left.name2']) == 'na') ? '-' : $data[$i]['Gene.Left.chrom'] . ":" .$data[$i]['Gene.Left.txStart'] . "-" . $data[$i]['Gene.Left.txEnd'] . "(" . $data[$i]['Gene.Left.strand'] . ")"); ?></td>
                    <td title="TX">Transcript</td>
                    <td><?php echo((strtolower($data[$i]['Gene.Right.name2']) == 'na') ? '-' : $data[$i]['Gene.Right.chrom'] . ":" .$data[$i]['Gene.Right.txStart'] . "-" . $data[$i]['Gene.Right.txEnd'] . "(" . $data[$i]['Gene.Right.strand'] . ")"); ?></td>
                  </tr>
                  <tr>
                    <td>Junction Site</td>
                    <td style="border-right:1px solid #000;"><?php echo($data[$i]['Junction.LeftChr'] . ":" .$data[$i]['Junction.LeftStart'] . "-" . $data[$i]['Junction.LeftEnd'] . "(" . $data[$i]['Junction.LeftStrand'] . ")"); ?></td>
                    <td>Junction Site</td>
                    <td><?php echo($data[$i]['Junction.RightChr'] . ":" .$data[$i]['Junction.RightStart'] . "-" . $data[$i]['Junction.RightEnd'] . "(" . $data[$i]['Junction.RightStrand'] . ")"); ?></td>
                  </tr>
                  <tr>
                    <td>Junction Position</td>
                    <td style="border-right:1px solid #000;"><?php echo($data[$i]['Junction.LeftChr'] . ":" .$data[$i]['Junction.LeftPosition']); ?></td>
                    <td>Junction Position</td>
                    <td><?php echo($data[$i]['Junction.RightChr'] . ":" .$data[$i]['Junction.RightPosition']); ?></td>
                  </tr>
                  <tr>
                    <td colspan="4" style="background-color:#CCC; border-top:1px solid #000; font-weight: bold; text-align: center;">Event Sequences</td>
                  </tr>
                  <tr <?php echo(" id=\"SequenceInfo{$i}\" class=\"Info{$i}\""); ?> style="border-top:1px solid;"><!-- <?php if(!$onlyOne) {echo("display: none; ");} ?> -->
                    <td colspan="4" style="vertical-align: top; padding:0px;">
                      <?php
                        # Yea! Start with the Sequences
                        $glseq = $data[$i][    'Gene.Left.Sequence'];  $glost = $data[$i][  'Gene.Left.txStart'];  $glstr = $data[$i][    'Gene.Left.strand'];
                        $grseq = $data[$i][   'Gene.Right.Sequence'];  $grost = $data[$i][ 'Gene.Right.txStart'];  $grstr = $data[$i][   'Gene.Right.strand'];
                        $jlseq = $data[$i][ 'JunctionLeft.Sequence'];  $jlost = $data[$i][ 'Junction.LeftStart'];  $jlstr = $data[$i][ 'Junction.LeftStrand'];  $ldir = intval($glstr . '1') * intval($jlstr . '1');
                        $jrseq = $data[$i]['JunctionRight.Sequence'];  $jrost = $data[$i]['Junction.RightStart'];  $jrstr = $data[$i]['Junction.RightStrand'];  $rdir = intval($grstr . '1') * intval($jrstr . '1');
                        
                        $NameNorm = ">{$data[$i]['Gene.Left.name2']}_{$jlstr}_{$data[$i]['Gene.Left.chrom']}:{$data[$i]['left.emin']},{$data[$i]['left.emax']}&{$data[$i]['Gene.Right.name2']}_{$jrstr}_{$data[$i]['Gene.Right.chrom']}:{$data[$i]['right.emin']},{$data[$i]['right.emax']}";
                        
                        $DNA     = null;
                        $DNA500  = null;
                        $mRNA    = null;
                        $mRNA500 = null;
                        $protein = null;
                        
                        
                        //TODO: junction sequence strand (look to other gene's strand)
                        /* DNA */
                        // Left
                        $DNAl = ($glseq == '' ) ? $jlseq : substr($glseq, (intval($data[$i][ 'left.emin'])-$glost), intval($data[$i][ 'left.elength']));
                        $DNAl = strtoupper(($glstr == '-') ? Reverse(Complement($DNAl)) : $DNAl);
                        // Right
                        $DNAr = ($grseq == '' ) ? $jrseq : substr($grseq, (intval($data[$i]['right.emin'])-$grost), intval($data[$i]['right.elength']));
                        $DNAr = strtolower(($grstr == '-') ? Reverse(Complement($DNAr)) : $DNAr);
                        // Event
                        if ((($ldir ==  1) || ($ldir != $rdir)) && ($ldir != 0)) {
                            $DNA    .= "{$NameNorm}_DNA(".strand($ldir).")\n"                  .        $DNAl       .        (($ldir != $rdir) ? Reverse(Complement($DNAr)) : $DNAr)          . "\n\n";
                            $DNA500 .= "{$NameNorm}_DNA(".strand($ldir).")(500bp_each_side)\n" . substr($DNAl,-500) . substr((($ldir != $rdir) ? Reverse(Complement($DNAr)) : $DNAr), 0, 500) . "\n\n";
                        }
                        if ((($rdir == -1) || ($ldir != $rdir)) && ($rdir != 0)) {
                            $DNA    .= "{$NameNorm}_DNA(".strand($rdir).")\n"                  .        (($ldir != $rdir) ? Reverse(Complement($DNAr)) : $DNAr)        .        $DNAl         . "\n\n";
                            $DNA500 .= "{$NameNorm}_DNA(".strand($rdir).")(500bp_each_side)\n" . substr((($ldir != $rdir) ? Reverse(Complement($DNAr)) : $DNAr), -500) . substr($DNAl,0, 500) . "\n\n";
                        }
                        
                        
                        /* RNA - Cut Exons from */
                        $tmp  = array(0=>null,1=>null);
                        // Left
                        $LeftCdsLengthL = 0;
                        $LeftCdsLengthR = 0;
                        if ($glseq != '') {
                            // Basically everything between 'left.emin' and 'left.emax'
                            $tmp[0] = "";
                            if (is_array( $data[$i]['Gene.Left.exonStarts']) && is_array($data[$i]['Gene.Left.exonStarts']) && (sizeof($data[$i]['Gene.Left.exonStarts']) == sizeof($data[$i]['Gene.Left.exonStarts']))) {
                                $starts = $data[$i]['Gene.Left.exonStarts'];
                                $ends   = $data[$i]['Gene.Left.exonEnds'  ];
                                $range  = array($data[$i][      'left.emin'    ],$data[$i][      'left.emax'  ]); // event range
                                $crange = array($data[$i][ 'Gene.Left.cdsStart'],$data[$i][ 'Gene.Left.cdsEnd']); // coding region (protein)
                                
                                $left = true;
                                for ($e = 0; $e < sizeof($starts); $e++) {
                                    $start = $starts[$e];
                                    $end   = $ends[  $e];
                                    if (($start != '') && ($end != '') && (between($start, $range) || between($end, $range))) {
                                        $start = (between($start, $range)) ? $start: $data[$i][ 'left.emin'] ;
                                        $end   = (between($end  , $range)) ? $end  : $data[$i][ 'left.emax'] ;
                                        
                                        // Protein start position
                                        if       ( between($start, $crange) &&  between($end  , $crange)) { // cds between start and end 
                                            $LeftCdsLengthL += ($end - $start);
                                        } elseif (!between($start, $crange) &&  between($end  , $crange)) { // cds start position
                                            $LeftCdsLengthL += ($end - min($crange));
                                        } elseif ( between($start, $crange) && !between($end  , $crange)) { // cds end position
                                            $LeftCdsLengthL += (max($crange) - $start);
                                        }
                                        
                                        // mRNA
                                        $tmp[0] .= substr($glseq, ($start - $glost), ($end - $start));
                                    }
                                }
                                $LeftCdsLengthR = strlen($tmp[0]) - $LeftCdsLengthL;
                            }
                            
                            
                            $tmp[0] = ($glstr == '-') ? Reverse(Complement($tmp[0])) : $tmp[0] ;
                        }
                        // Right
                        $RightCdsLengthL = 0;
                        $RightCdsLengthR = 0;
                        if ($grseq != '') {
                            // Basically everything between 'left.emin' and 'left.emax'
                            $tmp[1] = "";
                            if (is_array( $data[$i]['Gene.Right.exonStarts']) && is_array($data[$i]['Gene.Right.exonStarts']) && (sizeof($data[$i]['Gene.Right.exonStarts']) == sizeof($data[$i]['Gene.Right.exonStarts']))) {
                                $starts = $data[$i]['Gene.Right.exonStarts'];
                                $ends   = $data[$i]['Gene.Right.exonEnds'  ];
                                $range  = array($data[$i][     'right.emin'    ],$data[$i][     'right.emax'  ]); // event range
                                $crange = array($data[$i]['Gene.Right.cdsStart'],$data[$i]['Gene.Right.cdsEnd']); // coding region (protein)
                                
                                $left = true;
                                for ($e = 0; $e < sizeof($starts); $e++) {
                                    $start = $starts[$e];
                                    $end   = $ends[  $e];
                                    if (($start != '') && ($end != '') && (between($start, $range) || between($end, $range))) {
                                        $start = (between($start, $range)) ? $start: $data[$i]['right.emin'] ;
                                        $end   = (between($end  , $range)) ? $end  : $data[$i]['right.emax'] ;
                                        
                                        // Protein start position
                                        if       ( between($start, $crange) &&  between($end  , $crange)) { // cds between start and end 
                                            $RightCdsLengthL += ($end - $start);
                                        } elseif (!between($start, $crange) &&  between($end  , $crange)) { // cds start position
                                            $RightCdsLengthL += ($end - min($crange));
                                        } elseif ( between($start, $crange) && !between($end  , $crange)) { // cds end position
                                            $RightCdsLengthL += (max($crange) - $start);
                                        }
                                        
                                        // mRNA
                                        $tmp[1] .= substr($grseq, ($start - $grost), ($end - $start));
                                    }
                                }
                                $RightCdsLengthR = (strlen($tmp[1]) - $RightCdsLengthL);
                            }
                            $tmp[1] = ($grstr == '-') ? Reverse(Complement($tmp[1])) : $tmp[1] ;
                        }
                        
                        // Event (as of now there may be two directions to read)
                        $temp = array(-1 => null, 1 => null);
                        if ((($ldir ==  1) || ($ldir != $rdir)) && ($ldir != 0)) {
                            $temp[ 1][0] = strtoupper(       $tmp[0]      ) . strtolower(       (($ldir != $rdir) ? Reverse(Complement($tmp[1])) : $tmp[1])       );
                            $temp[ 1][1] = strtoupper(substr($tmp[0],-500)) . strtolower(substr((($ldir != $rdir) ? Reverse(Complement($tmp[1])) : $tmp[1]),0,500));
                        }
                        if ((($rdir == -1) || ($ldir != $rdir)) && ($rdir != 0)) {
                            $temp[-1][0] = strtoupper(       (($ldir != $rdir) ? Reverse(Complement($tmp[1])) : $tmp[1])      ) . strtolower(       $tmp[0]       );
                            $temp[-1][1] = strtoupper(substr((($ldir != $rdir) ? Reverse(Complement($tmp[1])) : $tmp[1]),-500)) . strtolower(substr($tmp[0],0,500));
                        }
                        unset($tmp);
                        
                        
                        
                        
                        // Right (Correct) strand (Donor strand)
                        $k = array_keys($temp);
                        for ($j = 0; ($j < sizeof($temp)) && (is_bool($data[$i]['left.donor']) || $data[$i]['both']); $j++) {
                            if ($temp[$k[$j]] != NULL) {
                                // mRNA
                                $mRNA    .= "{$NameNorm}_mRNA(".strand($k[$j]).")\n"                  . DNAtoRNA($temp[$k[$j]][0]) . "\n\n";
                                $mRNA500 .= "{$NameNorm}_mRNA(".strand($k[$j]).")(500bp_each_side)\n" . DNAtoRNA($temp[$k[$j]][1]) . "\n\n";
                                
                                // Protein
                                $poffset = ($k[$j] == -1) ? 
                                               ($data[$i]['right.direction'] == -1 ? 
                                                   $RightCdsLengthR : // Correct
                                                    $LeftCdsLengthL): // ??
                                               ($data[$i]['left.direction' ] == -1 ? 
                                                   $RightCdsLengthL : // ??
                                                    $LeftCdsLengthR); // Correct
                                $prot = DNAtoProtein(substr($temp[$k[$j]][0],$poffset));
                                $poffset = ($k[$j] == -1) ? strlen($prot) + (-1 * $poffset) : $poffset;
                                $prot = substr($prot,0,(strpos($prot,"*")));
                                $prot = (strtolower(substr($prot,0,1)) == 'm') ? $prot : null;
                                
                                $protein .= "{$NameNorm}_mRNA(".strand($k[$j]).")_to_protein\n" . $prot ."\n\n";
                            }
                        }
                        
                      ?>
                      <table width="100%">
                        <tr>
                          <th colspan="2" style="background-color:#eee; border-bottom:1px solid #000;">DNA</th>
                        </tr>
                        <tr>
                          <td>
                            <textarea rows="2" style="width:100%; font-family:Courier New;" onmouseover="createHelp(this,'Textarea containing the DNA sequence of the event. If genes are on both strands, two sequences are given.')"><?php echo($DNA); ?></textarea>
                          </td>
                          <td style="border-left:1px solid #000; width:50%;" id="shortseq<?php echo($i)?>">
                            <textarea rows="2" style="width:100%; font-family:Courier New;" onmouseover="createHelp(this,'Textarea containing 500bp of either side of the DNA sequence of the event, unless one is shorter or a position is missing.')"><?php echo($DNA500); ?></textarea>
                          </td>
                        </tr>
                        <tr>
                          <th colspan="2" style="background-color:#eee; border-bottom:1px solid #000; border-top:1px solid #000;">RNA</th>
                        </tr>
                        <tr>
                          <td>
                            <textarea rows="2" style="width:100%; font-family:Courier New;" onmouseover="createHelp(this,'Textarea containing the mRNA sequence of the event, according to the DNA.')"><?php echo(empty($mRNA) ? 'There is no mRNA from these genes.' : $mRNA); ?></textarea>
                          </td>
                          <td style="border-left:1px solid #000;">
                            <textarea rows="2" style="width:100%; font-family:Courier New;" onmouseover="createHelp(this,'Textarea containing 500bp of either side of the mRNA sequence of the event')"><?php echo(empty($mRNA500) ? 'There is no mRNA from these genes.' : $mRNA500); ?></textarea>
                          </td>
                        </tr>
                        <tr>
                          <th colspan="2" style="background-color:#eee; border-bottom:1px solid #000; border-top:1px solid #000;">Predicted Protein</th>
                        </tr>
                        <tr>
                          <td colspan="2">
                            <textarea rows="2" style="width:100%; font-family:Courier New;" onmouseover="createHelp(this,'Textarea containing the predicted protein sequence of the event according to the mRNA.')"><?php echo((empty($prot) || $prot == null) ? 'There is no mRNA and thus no protein to encode.' : $protein); ?></textarea>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <?php 
                        $accentColor = "#888";
                        
                        $leftS  = $data[$i]['Gene.Left.exonStarts' ];
                        $leftE  = $data[$i]['Gene.Left.exonEnds'   ];
                        $rightS = $data[$i]['Gene.Right.exonStarts'];
                        $rightE = $data[$i]['Gene.Right.exonEnds'  ];
                        
                        if ((is_array($leftS ) && is_array($leftE ) && (sizeof($leftS ) == sizeof($leftE ))) || 
                            (is_array($rightS) && is_array($rightE) && (sizeof($rightS) == sizeof($rightE)))) { ?>
                  <tr style="background-color:#ddd;">
                    <td colspan="2" style="border-right:1px solid #000; border-top:1px solid #000;  font-weight:bold; text-align: center;">
                      <?php echo(is_array($leftS ) ? sizeof(array_filter($leftS )) : 'No'); ?> Exons
                    </td>
                    <td colspan="2" style="border-top: 1px solid #000; font-weight: bold; text-align: center;">
                      <?php echo(is_array($rightS) ? sizeof(array_filter($rightS)) : 'No'); ?> Exons
                    </td>
                  </tr>
                  <tr <?php echo(" id=\"ExonInfo{$i}\" class=\"Info{$i}\""); ?> style="border-top:1px solid;"><!-- <?php if(!$onlyOne) {echo("display: none; ");} ?> -->
                    <!-- start -->
                    <td colspan="2" style="vertical-align: top; border-right:1px solid #000;">
                      <table width="100%">
                        <?php
                          if (is_array($leftS) && is_array($leftE)) {
                            if ($data[$i]['Gene.Left.strand'] == '-') { //rev
                              $tmp_s = array_reverse($leftS);
                              $tmp_e = array_reverse($leftE);
                              $leftS = $tmp_e;
                              $leftE = $tmp_s;
                            }
                            
                            $n = 1;
                            for ($j = 0; ($j < sizeof($leftS)) && ($j < sizeof($leftE)); $j++) {
                              if ($leftS[$j] != '' && $leftE[$j] != '') {
                                $p = $j;//($data[$i]['Gene.Left.strand'] == '-') ? (sizeof($leftE) - $n) : $n;
                            ?>
                        <tr>
                          <td width="10%">
                            <?php echo($n++); ?>
                          </td>
                          <td>
                            <?php echo("<span" . (((!between(intval($leftS[$p]), array($data[$i]['left.emax'],$data[$i]['left.emin']))) && (!between(intval($leftE[$p]), array($data[$i]['left.emax'],$data[$i]['left.emin']))))?" style=\"color:$accentColor;\">":">") . $data[$i]['Gene.Left.chrom'] . ":<span" . ((!between(intval($leftS[$p]), array($data[$i]['left.emax'],$data[$i]['left.emin'])))?" style=\"color:$accentColor;\">":">") . $leftS[$p] . "</span>-<span" . ((!between(intval($leftE[$p]), array($data[$i]['left.emax'],$data[$i]['left.emin'])))?" style=\"color:$accentColor;\">":">") . $leftE[$p] ."</span>(" . $data[$i]['Gene.Left.strand'] . ")</span>"); ?>
                          </td>
                        </tr>
                        <?php
                              }
                            }
                          } else {
                              echo("<tr><td>-</td></tr>");
                          } ?>
                     </table>
                    </td>
                    <!-- end & start -->
                    <td colspan="2" style="vertical-align: top;">
                      <table width="100%">
                        <?php
                          if (is_array($rightS) && is_array($rightE)) {
                            if ($data[$i]['Gene.Right.strand'] == '-') { //rev
                              $tmp_s = array_reverse($rightS);
                              $tmp_e = array_reverse($rightE);
                              $rightS = $tmp_e;
                              $rightE = $tmp_s;
                            }
                            
                            $n=1;
                            for ($j = 0; ($j < sizeof($rightS)) && ($j < sizeof($rightE)); $j++) {
                              if ($rightS[$j] != '' && $rightE[$j] != '') {
                                  $p = $j;//($data[$i]['Gene.Right.strand'] == '-') ? (sizeof($rightE) - $n) : $n;
                             ?>
                        <tr>
                          <td width="10%">
                            <?php echo($n++); ?>
                          </td>
                          <td>
                            <?php echo("<span" . (((!between(intval($rightS[$p]), array($data[$i]['right.emax'],$data[$i]['right.emin']))) && (!between(intval($rightE[$p]), array($data[$i]['right.emax'],$data[$i]['right.emin']))))?" style=\"color:$accentColor;\">":">") . $data[$i]['Gene.Right.chrom'] . ":<span" . ((!between(intval($rightS[$p]), array($data[$i]['right.emax'],$data[$i]['right.emin'])))?" style=\"color:$accentColor;\">":">") . $rightS[$p] . "</span>-<span" . ((!between(intval($rightE[$p]), array($data[$i]['right.emax'],$data[$i]['right.emin'])))?" style=\"color:$accentColor;\">":">") . $rightE[$p] ."</span>(" . $data[$i]['Gene.Right.strand'] . ")</span>"); ?>
                          </td>
                        </tr>
                        <?php
                              }
                            }
                          } else {
                              echo("<tr><td>-</td></tr>");
                          } ?>
                     </table>
                    </td>
                    <!-- end -->
                  </tr>
                  <?php } ?>
                </table>
              </td>
            </tr>
          </table>
          <?php } ?>
        </div>
        <?php if ($pages>1) { ?>
        <script type="text/javascript">
          function doPage(cur, per, action) {
              var url = this.document.location.href;
              var segments = url.split("/");
              var newurl = new Array();
              
              for (i=0; i<segments.length; i++) {
                  if (segments[i].indexOf("page:") != 0) {
                      newurl[newurl.length] = segments[i];
                  }
              }
              
              this.document.location.href = newurl.join("/")+"/page:"+cur+"-"+per+"-"+action;
          }
          
          function customPage() {
              var newPage = prompt("To which page would you like to go?", "<?php echo $cur ?>");
              if (isNumeric(newPage,"'"+newPage+"' is not numeric")) {
                  <?php echo "doPage(newPage, $per, '');"; ?>
              }
          }
        </script>
        <table class="borders" style="width:100%; background-color:#ccc; font-weight:bold;">
            <tr>
                <td>
                    <a href="javascript:void(0);" style="display: inline-table; width:25px;" onclick="doPage(<?php echo $cur . "," . $per; ?>,'f');"> </a>
                    <a href="javascript:void(0);" style="display: inline-table; width:25px;" onclick="doPage(<?php echo $cur . "," . $per; ?>,'p');">&#139;</a>
                    <a href="javascript:void(0);" style="display: inline-table; font-size:small;" onclick="customPage()"><?php echo("{$cur}/{$pages}"); ?></a>
                    <a href="javascript:void(0);" style="display: inline-table; width:25px;" onclick="doPage(<?php echo $cur . "," . $per; ?>,'n');">&#155;</a>
                    <a href="javascript:void(0);" style="display: inline-table; width:25px;" onclick="doPage(<?php echo $cur . "," . $per; ?>,'l');"> </a>
                </td>
            </tr>
        </table>
              <?php }} else {
                  echo("There are none to describe.");
              }
          ?>
      </div>
<?php


 } else {
    header('Location: '.site_url("continues"));
} ?>
