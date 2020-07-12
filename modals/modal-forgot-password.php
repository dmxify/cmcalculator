
  <div id="modalForgotPassword" class="modal" style="font-family: Arial, Helvetica, sans-serif;">
    <div class="modal-content animate">
      <div class="imgcontainer title">
        <span onclick="document.getElementById('modalForgotPassword').style.display='none'" class="close" title="Close forgot password window">&times;</span>
        <img src="btc.png" alt="Avatar" class="avatar">
        <div style="font-size: 20pt; margin-left: 20px;">CM Calculator</div>
      </div>
      <div class="container">
        <form onsubmit="return forgotPassword();" method="post">
          <?php if (isSessionAction('forgot-password')) { ?>
          <div class="<?php handleSessionMessageType('forgot-password'); ?>">
            <?php handleSessionMessages('forgot-password'); ?>
          </div>
          <?php } ?>
          <div class="title bold">
            Forgot password?
          </div>
          <br />
          <label for="forgotPassword_email"><b>Email Address</b></label>
          <input id="forgotPassword_email" name="forgotPassword_email" type="text" placeholder="Enter Email Address" autocomplete="username" required>

          <div class="g-recaptcha" data-sitekey="6LesXKYZAAAAAOg5KsgrKPyds_elGqXAnaZFDr6v" data-callback="captcha_solved_forgotPassword" data-theme="<?php echo $_SESSION['theme']; ?>"></div>
          <!-- <button onclick="login()">Login</button> -->
          <input type="submit" value="Send password reset email" id="forgotPassword_btnSubmit" class="disabled" disabled="disabled"/>
          <div id="forgotPassword_button_mask" class="button-mask">
            <div class="animation load" id="forgotPassword_loading">
              Sending email...
            </div>
          </div>
        </form>
        <div id="forgotPassword-msg" class="modal-msg"></div>
      </div>
      <div class="container">
        <button type="button" onclick="document.getElementById('modalForgotPassword').style.display='none'" class="cancelbtn">Cancel</button>
      </div>
    </div>
    <script>

      window.forgotPassword_captcha_response_token = "";
      function captcha_solved_forgotPassword(forgotPassword_captcha_response_token){
        window.forgotPassword_captcha_response_token = forgotPassword_captcha_response_token;
        if (document.getElementById("forgotPassword_btnSubmit").disabled) {
            document.getElementById("forgotPassword_btnSubmit").disabled = false;
              document.getElementById("forgotPassword_btnSubmit").classList.remove("disabled");
        }
      }
      function forgotPassword(){
        document.getElementById("forgotPassword_btnSubmit").style.display = "none";
        document.getElementById("forgotPassword_button_mask").style.display = "block";

        fetch('api/user/forgot-password/index.php', {
            method: 'post',
            headers: {
              'Accept': 'application/json, text/plain, */*',
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              forgotPassword_email: document.getElementById('forgotPassword_email').value,
              forgotPassword_captcha_response_token: window.forgotPassword_captcha_response_token
            })
          })
          // .then(response => response.json())
          .then((response) => {
            return response.json();
           })
          .then((data) => {
            if (!data.submitted) {
              document.getElementById("forgotPassword-msg").innerHTML = "Unable to submit password reset request: "+(data.error)?data.errorMsg:"";
              document.getElementById("forgotPassword-msg").style.display = "block";
              document.getElementById("forgotPassword_btnSubmit").style.display = "block";
              document.getElementById("forgotPassword_button_mask").style.display = "none";
            } else {

                document.querySelector('#modalForgotPassword form').style.display = "none";
                document.getElementById("forgotPassword-msg").style.display = "none";
                document.querySelector('#modalForgotPassword .container').innerHTML = data.msg;
                document.querySelector('#modalForgotPassword .container').classList.add("success");
                // document.getElementById("register-msg").classList.add('success');
            }
          });

        return false;
      }
    </script>
  </div>
  <!-- forgot password -->
