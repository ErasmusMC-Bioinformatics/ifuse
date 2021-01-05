    <div class="wrapper">
      <?php if ((file_exists($this->fileName) && is_file($this->fileName)) || $this->userfiles->getConfig('FILES_AMOUNT',-1) >=0) { ?>
      <div id="restore" style="border:1px solid #000; margin-bottom: 1em;">
        <div style="width:100%; display:block; height:20px; background-color:#ccc; text-align:center;font-weight:bold;border-bottom:1px solid #000;">Files in session</div>
        <iframe src="<?php echo(site_url("form/files")); ?>" frameborder='0' style="width:100%; height:250px; margin:0px; padding:0px; scrolling:auto; border:0px solid #000;"></iframe>
        <div style="text-align:center; width:100%; border-top:1px solid #000"><a href="<?php echo(site_url("continues")); ?>" title="Go to the activated file...">[continue]</a> <a href="javascript:void(0);" onclick="javascript:if(confirm('Are you sure you want to delete this session?')){window.location = '<?php echo(site_url("delete/session")); ?>';}" title="Delete entire session...">[remove]</a></div>
        <!--<table style="width:100%;">
          <tr>
            <td style="padding:5px;"><a href="<?php echo(site_url("continues")); ?>"><?php echo((file_exists($this->fileName) && is_file($this->fileName))?"Go to saved file for this session ('<i>".$this->userfiles->getFile(null,"ORG_NAME")."</i>')":"<a href=\"javascript:void(0);\" onclick='javascript:iFrameBox(\"Files\" ,\"" . site_url("form/files/") . "\",\"cancel\",900,600);'>Show Files in this Session</a>"); ?></a></td>
            <td style="width:25px; height: 25px; text-align:center;" onclick="javascript:if(confirm('Are you sure you want to delete this session?')){window.location = '<?php echo(site_url("delete/session")); ?>';}"><b>X</b></td>
          </tr>
        </table>-->
      </div><br /><br /><?php } ?>

      <form name="start" method="post" enctype="multipart/form-data" action="<?php echo(site_url("upload")); ?>">
        <input type="hidden" name="MAX_FILE_SIZE" value="25000000" /><!-- +/-25mb -->
        <div style="width:100%; padding:0px;">
          <center>
            <table width="500px">
              <tr>
                <td colspan="2">
                  <fieldset width="100%">
                    <legend>
                      <label for="iFile">{main_iFile}</label>
                    </legend>
                    <div onclick="javascript:msgbox('{help_ifile_t}', '{help_ifile_d}');" style="width:15px; height:0px; float:right; font-weight:bold;"><div style="position:relative; right:-10px; top:-10px;">{help_ifile}</div></div>
                    <input type="file" name="iFile" id="iFile" size="60" onchange="javascript:var ext = this.value.substring(this.value.lastIndexOf('.')+1);var fld = document.start.iFormat; for (i = 0; i < fld.length; i++) {if (fld[i].value.toLowerCase() == ext.toLowerCase()) {fld[i].selected = '1';break;}}" />
                  </fieldset>
                </td>
              </tr>
              <tr>
                <td width="50%">{main_iFormat}</td>
                <td>
                  <!--<div style="position:absolute; width:250px">&nbsp;</div>-->
                  <select name="iFormat" id="iFormat" style="clear: both;">
                    <option value="tdf" id="tdf">iFuse Raw data file</option>
                    <option value="fm" id="fm" >Fusionmap</option>
                    <option value="cge" id="cge" selected="selected">Complete Genomics Events file</option>
                    <!--<option value="" id=""></option>-->
                  </select>
                </td>
              </tr>
              <tr>
                <td>{main_iHeader_label}</td>
                <td>
                  <table style="width:100%; border-collapse:collapse; border-width:0px;">
                    <tr>
                      <td style="width:50%; padding:0px;"><input type="radio" name="iHeader" id="iHeaderT" value="true" checked="checked"><label for="iHeaderT">{main_iHeaderT}</label></td>
                      <td style="width:50%; padding:0px;"><input type="radio" name="iHeader" id="iHeaderF" value="false"><label for="iHeaderF">{main_iHeaderF}</label></td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td>{main_iRefGen_label}</td>
                <td>
                  <table style="width:100%; border-collapse:collapse; border-width:0px;">
                    <tr style="display: none;">
                      <td style="width:50%; padding:0px;">
                        <input type="radio" name="HG" id="HGc" value="HGc" onchange="javascript:document.getElementById('assembly').setAttribute('style', (this.checked?'display:none;':''));"><label for="HGc">{main_iRefGen_oColumn}</label>
                      </td>
                      <td style="width:50%; padding:0px;">
                        <input type="radio" name="HG" id="HGi" value="HGi" checked="checked" onchange="javascript:document.getElementById('assembly').setAttribute('style', (!this.checked?'display:none;':''));"><label for="HGi">{main_iRefGen_oAssembly}</label>
                      </td>
                    </tr>
                    <tr id="assembly">
                      <td colspan="2" style="padding:0px;">
                        <div style="clear: left; width: 100%;">
                          <select name="iRefGen" style="clear: both;">
                            <option value="hg19">Feb. 2009 (GRCh37/hg19)</option>
                            <option value="hg18" selected="selected">Mar. 2006 (NCBI36/hg18)</option>
                          </select>
                        </div>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <center><input type="submit" value="{main_Submit}" onclick='return submitmsg();'/><input type="reset" value="{main_Reset}" /></center>
                </td>
              </tr>
            </table>
          </center>
        </div>
      </form>
    </div>
    <script type="text/javascript">
      function submitmsg() {
          //alert ("Form submitted - Thank you!");
          msgbox("Submitting", "The file is being uploaded and analysed. Use your browsers stop loading button or [Esc]-button to stop this process.", "<style>.MSGClose{display:none;}</style>", 300, 150);
          return true;
      }
      
      function openSession() {
          var session = prompt("To open an old session, please enter the session ID:", '');
          if (isNumeric(session,"'"+session+"' is not numeric")) {
              this.document.location.href = '<?php echo(site_url('open')); ?>' + '/' + session;
          }
      }
    </script>


