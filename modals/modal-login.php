
  <div id="modalLogin" class="modal" style="font-family: Arial, Helvetica, sans-serif;">
    <div class="modal-content animate">
      <div class="imgcontainer title">
        <span onclick="document.getElementById('modalLogin').style.display='none'" class="close" title="Close login window">&times;</span>
        <img src="btc.png" alt="Avatar" class="avatar">
        <div style="font-size: 20pt; margin-left: 20px;">CM Calculator</div>
      </div>
      <div class="container">
        <form onsubmit="return login();" method="post">
          <?php if (isSessionAction('login')) { ?>
          <div class="<?php handleSessionMessageType('login'); ?>">
            <?php handleSessionMessages('login'); ?>
          </div>
        <?php } ?>
          <div class="title bold">
            Existing user login:
          </div>
          <br />
          <label for="email"><b>Email Address</b></label>
          <input id="login_email" type="text" placeholder="Enter Email Address" autocomplete="username" name="email" value="<?php echo getSessionEmail(); ?>" required>

          <label for="password"><b>Password</b></label>
          <input id="login_password" type="password" autocomplete="current-password" placeholder="Enter Password" name="password" required>

          <!-- <button onclick="login()">Login</button> -->
          <input type="submit" value="Login" id="login_btnSubmit" />
          <div id="login_button_mask" class="button-mask">
            <div class="animation load" id="login_loading">
              Logging in...
            </div>
          </div>
        </form>
        <div id="login-msg" class="modal-msg"></div>
      </div>

      <div class="container" >
        <button type="button" onclick="document.getElementById('modalLogin').style.display='none'" class="cancelbtn">Cancel</button>
        <span class="psw">Forgot <a href="#" onclick="open_modal('modalForgotPassword')">password?</a></span>
      </div>
    </div>
    <script>

      function login() {
        document.getElementById("login_btnSubmit").style.display = "none";
        document.getElementById("login_button_mask").style.display = "block";

        fetch('api/user/login/index.php', {
            method: 'post',
            headers: {
              'Accept': 'application/json, text/plain, */*',
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              email: document.getElementById('login_email').value,
              password: document.getElementById('login_password').value
            })
          })
          .then(response => response.json())
          .then((data) => {
            if (!data.verified) {
              document.getElementById("login-msg").innerHTML = "Invalid email address or password.";
              document.getElementById("login-msg").style.display = "block";
              document.getElementById("login_btnSubmit").style.display = "block";
              document.getElementById("login_button_mask").style.display = "none";
            } else {
                window.location.reload();
            }
          });
        return false;
      }

      function logout(){
        fetch('api/user/logout/index.php').then(()=>{
            window.location.reload();
        });
      }
    </script>

  </div>
  <!-- loginModal -->
