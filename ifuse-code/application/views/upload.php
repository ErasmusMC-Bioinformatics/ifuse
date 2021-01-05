 <?php
if (!empty($_POST) && !empty($_FILES) && ($_FILES['iFile']['error']  === UPLOAD_ERR_OK)) {
    // extended version of the origional format after Elizabeths R and Ivo's CVS to TDF
    $this->userfiles->add_file($_FILES["iFile"], array("format:".$_POST["iFormat"],"header:".$_POST["iHeader"],"reference:".$_POST["iRefGen"]));
    $this->fileName = $this->userfiles->getFile();
  if (isset($error)) {
    // error
    ?>
      <div id="restore" style="border:1px solid #000; margin-bottom: 1em;">
        <table style="width:100%;">
          <tr>
            <td style="padding:5px;"><?php echo($error); ?></td>
            <td style="width:25px; height: 25px; text-align:center;" onclick="javascript:window.location = '<?php echo(site_url('continues')); ?>';"><b>X</b></td>
          </tr>
        </table>
      </div>
<?php
  }
  if (!file_exists($this->fileName)) {
    // file does not exist
    ?>
      <div id="restore" style="border:1px solid #000; margin-bottom: 1em;">
        <table style="width:100%;">
          <tr>
            <td style="padding:5px;">The file could not be analysed</td>
            <td style="width:25px; height: 25px; text-align:center;" onclick="javascript:window.location = '<?php echo(site_url('continues')); ?>';"><b>X</b></td>
          </tr>
        </table>
      </div>
<?php
  }
  if (file_exists($this->fileName) && !isset($error)) {
    // all went well
    header("location: ".site_url("analyse"));
  }
} else {
  header("location: ".base_url());
} ?>