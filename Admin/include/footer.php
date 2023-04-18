</main>
<footer>
<section>
  <h6>Legal Information</h6>
  <ul>
    <li><a href="">Terms & Conditions</a></li>
    <li><a href="">Privacy Policy</a></li>
  </ul>
</section>

<section>
  <h6>Contact Information</h6>
  <ul>
  <li><address><i class="fa-solid fa-location-dot" id="location"></i>&nbsp; Odo-ona kekere, New garage, Challenge Ibadan, Oyo state, Nigeria.</address></li>
  <li><i class="fa-solid fa-phone" id="phone"></i> &nbsp;<?= _ADMIN_PHONE_; ?></li>
  <li><a href="mailto:<?= _ADMIN_; ?>"><i class="fa-regular fa-envelope" id="gmIco"></i>&nbsp;<?= _ADMIN_; ?></a></li>
  </ul>
</section>

<section>
  <h6>Follow Us</h6>
  <a href="<?= _WHATSAPP_; ?>"><i class="fa-brands fa-whatsapp fa-2x" id="waIco"></i></a>
  <a href="<?= _FACEBOOK_; ?>"><i class="fa-brands fa-facebook fa-2x" id="fb"></i></a>
  <a href="<?= _INSTAGRAM_; ?>"><i class="fa-brands fa-instagram fa-2x" id="inst"></i></a>
</section>

<div> &copy; lumienux <?= date("Y", time());?></div>

</footer>
<script src="<?= _DOMAIN_;?>/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php if(_CURRENT_FILE_== 'index.php'){ ?>
 <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
    async defer></script>
  <script type="text/javascript">
     var Submit= false;//to make reCaptcha required.

    var Verify= function(response){
        if(response == grecaptcha.getResponse()){
            Submit= true;
        }
    }

  var onloadCallback = function() {
    grecaptcha.render('reV2', {
        'sitekey': '<?= _V2_SITE_KEY_; ?>',
        'callback': Verify,
        'theme': 'dark',
    });
  }
  document.getElementById('auth').addEventListener("submit", function(event){//Make reCaptcha required
    if(Submit){
        document.getElementsByTagName('iframe')[0].removeAttribute("style");
    }else{
        event.preventDefault();
        document.getElementsByTagName('iframe')[0].style.border= "1px solid red";
    }
  });
  </script>
<?php } ?>