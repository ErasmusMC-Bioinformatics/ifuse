    <div class="wrapper">
      <form name="start" method="post" enctype="multipart/form-data" action="<?php echo(site_url("login")); ?>">

          <center>
            <table width="500px">
              <tr>
                <td colspan="3" style="background-color:#ccc; text-align:center; font-weight:bold; border:1px solid #000;">Login</td>
              </tr>
              <?php if (isset($_POST) && isset($_POST['username'])) { ?><tr>
                <td colspan="3" style="padding:5px;">
                  <div style="background-color:#fcc; width:100%; height:100%; border-radius:5px;">
                    <div style="padding:5px;">Error: username/password combination not found.</div>
                  </div>
                </td>
              </tr><?php } ?>
              <tr>
                <td style="text-align:right; padding-right:7px; padding-top:5px;"><label for="username">Email</label></td>
                <td colspan="2" style=" padding-top:5px;"><input type="text" name="username" id="username"<?php echo(isset($_POST) && isset($_POST['username']) ? " value='{$_POST['username']}'" : '');?>></td>
              </tr>
              <tr>
                <td style="text-align:right; padding-right:7px; padding-top:5px;"><label for="password">Password</label></td>
                <td colspan="2" style=" padding-top:5px;"><input type="password" name="password" id="password"></td>
              </tr>
              <tr>
                <td style="text-align:right; padding-right:7px; padding-top:5px;">Remember?</td>
                <td colspan="2" style=" padding-top:5px;">
                  <table style="border-collapse:collapse; width:100%;">
                    <tr>
                      <td width="50%">
                        <input type="radio" name="remember" id="remembery" value="yes"<?php echo(isset($_POST) && isset($_POST['remember']) && ($_POST['remember'] == 'yes') ? ' checked="checked"' : '');?>> <label for="remembery">yes</label>
                      </td>
                      <td>
                        <input type="radio" name="remember" id="remembern" value="no"<?php echo(isset($_POST) && isset($_POST['remember']) && ($_POST['remember'] == 'yes') ? '' :' checked="checked"');?>> <label for="remembern">no</label>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td width="100px;"></td>
                <td style="text-align:center; padding-top:5px;">
                  <input type="submit" value="Login">
                  <input type="reset" value="Reset">
                </td>
                <td style="text-align:right; padding-right:7px;width:100px; font-variant:small-caps; padding-top:5px;">
                  <a href="<?php echo(site_url("register")); ?>">register</a>
                </td>
              </tr>
            </table>
          </center>
        </div>
      </form>
      <div style="text-align:center; padding-top:3em"><small><a href="<?php echo(base_url("Documentation/manual.docx"));?>" target="_Blank">Documentation</a></small></div>
    </div>
