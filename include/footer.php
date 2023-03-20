</main>
<footer>

  <section>
    <h6>Stayed tune with us</h6>
    <ul>
    <li>
      <a href="http://localhost/lumienux/blog">Visit our Blog</a>
    </li>
    <li>
      <form method="POST" id="newsletter">
        <fieldset form="newsletter">
          <legend>Suscribe to our newsletter for update on product</legend>
          <label class="zl-form-info" <?= FormFlashMsg('scb_name_err'); ?>>Name&nbsp;<input type="text" name="scb_name" placeholder="Alice Joy" value="<?= $scb_name; ?>"></label>
          <label class="zl-form-info" <?= FormFlashMsg('scb_email_err'); ?>>Email&nbsp;<input type="email" name="scb_email" placeholder="abc@domain.com" value="<?= $scb_email; ?>"></label>
          <input type="submit" value="Suscribe">
        </fieldset>
      </form>
    </li>
    </ul>
  </section>

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
<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="js/search2.js"></script>
<?php
if(_CURRENT_FILE_ == 'cart.php' || _CURRENT_FILE_ == 'checkout.php'){
  echo '<script src="js/cartCookie.js"></script>';
}
if(_CURRENT_FILE_ == 'cart.php'){
    echo '<script src="js/cart.js"></script>';
    echo '<script src="js/order.js"></script>';
}
?>
</body>
</html>