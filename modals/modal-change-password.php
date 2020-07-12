
  <div id="modalChangePassword" class="modal" style="font-family: Arial, Helvetica, sans-serif;">
    <div class="modal-content animate">
      <div class="imgcontainer title">
        <span onclick="document.getElementById('modalChangePassword').style.display='none'" class="close" title="Close change password window">&times;</span>
        <img src="btc.png" alt="Avatar" class="avatar">
        <div style="font-size: 20pt; margin-left: 20px;">CM Calculator</div>
      </div>
      <div class="container">
        <form onsubmit="return changePassword();" method="post">
          <?php if (isSessionAction('change-password')) { ?>
          <div class="<?php handleSessionMessageType('change-password'); ?>">
            <?php handleSessionMessages('change-password'); ?>
          </div>
          <?php } ?>
          <div class="title bold">
            Change Password
          </div>
          <br />

          <label for="password"><b>Set Password</b></label>
          <input id="changePassword_password" name="changePassword_password" type="password" autocomplete="new-password" placeholder="Enter New Password" required>

          <label for="password"><b>Confirm Password</b></label>
          <input id="changePassword_confirm_password" name="changePassword_confirm_password" type="password" autocomplete="new-password" placeholder="Confirm Password" required>

          <div class="g-recaptcha" data-sitekey="6LesXKYZAAAAAOg5KsgrKPyds_elGqXAnaZFDr6v" data-callback="captcha_solved_changePassword" data-theme="<?php echo $_SESSION['theme']; ?>"></div>
          <!-- <button onclick="login()">Login</button> -->
          <input type="submit" value="Submit" id="changePassword_btnSubmit" class="disabled" disabled="disabled"/>
          <div id="changePassword_button_mask" class="button-mask">
            <div class="animation load" id="changePassword_loading">
              Changing password...
            </div>
          </div>
        </form>
        <div id="changePassword-msg" class="modal-msg"></div>
      </div>
      <div class="container">
        <button type="button" onclick="document.getElementById('modalChangePassword').style.display='none'" class="cancelbtn">Cancel</button>
      </div>
    </div>
    <script>

      window.changePassword_captcha_response_token = "";
      function captcha_solved_changePassword(changePassword_captcha_response_token){
        window.changePassword_captcha_response_token = changePassword_captcha_response_token;
        if (document.getElementById("changePassword_btnSubmit").disabled) {
            document.getElementById("changePassword_btnSubmit").disabled = false;
              document.getElementById("changePassword_btnSubmit").classList.remove("disabled");
        }
      }
      function changePassword(){
        document.getElementById("changePassword_btnSubmit").style.display = "none";
        document.getElementById("changePassword_button_mask").style.display = "block";

        fetch('api/user/change-password/index.php', {
            method: 'post',
            headers: {
              'Accept': 'application/json, text/plain, */*',
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              changePassword_password: document.getElementById('changePassword_password').value,
              changePassword_confirm_password: document.getElementById('changePassword_confirm_password').value,
              changePassword_captcha_response_token: window.changePassword_captcha_response_token
            })
          })
          // .then(response => response.json())
          .then((response) => {
            return response.json();
           })
          .then((data) => {
            if (!data.changed) {
              document.getElementById("changePassword-msg").innerHTML = "Unable change password: "+(data.error)?data.errorMsg:"";
              document.getElementById("changePassword-msg").style.display = "block";
              document.getElementById("changePassword_btnSubmit").style.display = "block";
              document.getElementById("changePassword_button_mask").style.display = "none";
            } else {
                // window.location.reload();
                document.querySelector('#modalChangePassword form').style.display = "none";
                document.getElementById("changePassword-msg").style.display = "none";
                document.querySelector('#modalChangePassword .container').innerHTML = data.msg;
                document.querySelector('#modalChangePassword .container').classList.add("success");
                // document.getElementById("register-msg").classList.add('success');
                reloadIn3Seconds();
            }
          });

        return false;
      }

      function reloadIn3Seconds(){
        document.querySelector('#modalChangePassword .container').innerHTML += "Redirecting in <span id='redirectCountdown'>3</span> second(s)...";
        window.seconds = 3;
        window.intervalReload = setInterval(()=>{
          window.seconds--;
            if(window.seconds==0){
              window.location.reload();
              clearInterval(window.intervalReload);
            } else {
              document.getElementById('redirectCountdown').innerHTML=window.seconds;
            }
        },1000);
      }

    </script>
  </div>
  <!-- change password -->
