
  <div id="modalRegister" class="modal" style="font-family: Arial, Helvetica, sans-serif;">
    <div class="modal-content animate">
        <div class="imgcontainer title">
          <span onclick="document.getElementById('modalRegister').style.display='none'" class="close" title="Close register window">&times;</span>
          <img src="btc.png" alt="Avatar" class="avatar">
          <div style="font-size: 20pt; margin-left: 20px;">CM Calculator</div>
        </div>
        <div class="container">
          <form onsubmit="return register();" method="post">
            <div class="title bold">
              New user registration:
            </div>
            <br />

            <label for="register_name"><b>First Name</b></label>
            <input id="register_name" name="register_name" type="text" placeholder="Enter First Name" autocomplete="given-name" required>

            <label for="register_email"><b>Email Address</b></label>
            <input id="register_email" name="register_email" type="text" placeholder="Enter Email Address" autocomplete="username" required>

            <label for="password"><b>Set Password</b></label>
            <input id="register_password" name="register_password" type="password" autocomplete="new-password" placeholder="Enter New Password" required>

            <label for="password"><b>Confirm Password</b></label>
            <input id="register_confirm_password" name="register_confirm_password" type="password" autocomplete="new-password" placeholder="Confirm Password" required>

            <div class="g-recaptcha" data-sitekey="6LesXKYZAAAAAOg5KsgrKPyds_elGqXAnaZFDr6v" data-callback="captcha_solved_register" data-theme="<?php echo $_SESSION['theme']; ?>"></div>
            <!-- <button onclick="login()">Login</button> -->
            <input type="submit" value="Register" id="register_btnSubmit" class="disabled" disabled="disabled"/>
            <div id="register_button_mask" class="button-mask">
              <div class="animation load" id="register_loading">
                Registering...
              </div>
            </div>
          </form>
          <div id="register-msg" class="modal-msg"></div>
        </div>
        <div class="container">
          <button type="button" onclick="document.getElementById('modalRegister').style.display='none'" class="cancelbtn">Cancel</button>
        </div>
      </div>
    <script>
      window.register_captcha_response_token = "";
      function captcha_solved_register(register_captcha_response_token){
        window.register_captcha_response_token = register_captcha_response_token;
        if (document.getElementById("register_btnSubmit").disabled) {
            document.getElementById("register_btnSubmit").disabled = false;
              document.getElementById("register_btnSubmit").classList.remove("disabled");
        }
      }
      function register() {
        document.getElementById("register_btnSubmit").style.display = "none";
        document.getElementById("register_button_mask").style.display = "block";

        fetch('api/user/register/index.php', {
            method: 'post',
            headers: {
              'Accept': 'application/json, text/plain, */*',
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              register_name: document.getElementById('register_name').value,
              register_email: document.getElementById('register_email').value,
              register_password: document.getElementById('register_password').value,
              register_confirm_password: document.getElementById('register_confirm_password').value,
              register_captcha_response_token: window.register_captcha_response_token
            })
          })
          // .then(response => response.json())
          .then((response) => {
            return response.json();
           })
          .then((data) => {
            if (!data.registered) {
              document.getElementById("register-msg").innerHTML = "Unable to register user: "+(data.error)?data.errorMsg:"";
              document.getElementById("register-msg").style.display = "block";
              document.getElementById("register_btnSubmit").style.display = "block";
              document.getElementById("register_button_mask").style.display = "none";
            } else {
                // window.location.reload();
                document.querySelector('#modalRegister form').style.display = "none";
                document.getElementById("register-msg").style.display = "none";
                document.querySelector('#modalRegister .container').innerHTML = data.msg;
                document.querySelector('#modalRegister .container').classList.add("success");
                // document.getElementById("register-msg").classList.add('success');
            }
          });
        return false;
      }
    </script>
  </div>
  <!-- registerModal -->
