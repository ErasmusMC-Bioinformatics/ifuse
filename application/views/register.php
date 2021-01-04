    <div class="wrapper">
      <form name="start" method="post" enctype="multipart/form-data" action="<?php echo(site_url("register")); ?>">

          <center>
            <table width="500px">
              <tr>
                <td colspan="3" style="background-color:#ccc; text-align:center; font-weight:bold; border:1px solid #000;">Register</td>
              </tr>
              <?php if (isset($_POST) && isset($_POST['username'])) { ?><tr>
                <td colspan="3" style="padding:5px;">
                  <div style="background-color:#fcc; width:100%; height:100%; border-radius:5px;">
                    <div style="padding:5px;">{currenterror}</div>
                  </div>
                </td>
              </tr><?php } ?>
              <tr>
                <td style="text-align:right; padding-right:7px; padding-top:5px; width:30%"><label for="username">Username</label></td>
                <td style="padding-top:5px;"><input type="text" name="username" id="username"></td>
              </tr>
              <tr>
                <td style="text-align:right; padding-right:7px; padding-top:5px;"><label for="email">E-Mail address</label></td>
                <td style="padding-top:5px;"><input type="text" name="email" id="email"></td>
              </tr>
              <tr>
                <td style="text-align:right; padding-right:7px; padding-top:5px;"><label for="password">Password</label></td>
                <td style="padding-top:5px;"><input type="password" name="password" id="password"></td>
              </tr>
              <tr>
                <td style="text-align:right; padding-right:7px; padding-top:5px;"><label for="passwordr">Retype password</label></td>
                <td style="padding-top:5px;"><input type="password" name="passwordr" id="passwordr"></td>
              </tr>
              <tr>
                <td style="text-align:center; padding-top:5px;" colspan="2">
                  <input type="submit" value="Register">
                  <input type="reset" value="Reset">
                </td>
              </tr>
            </table>
          </center>
        </div>
      </form>
    </div>
