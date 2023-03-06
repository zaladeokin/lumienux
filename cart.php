<?php
require_once('config/autoload.php');

?>




<?php
//View
include_once('include/header.php');
?>
<section>
        <h1>Cart</h1>

        <div class="cart" id="cart_1">
            <img src="img/product/battery1.jpg" alt="5kw battery, Led-acid">
            <div>
                <span>3kw battery, Led-acid</span>
                <span>Price</strong>&nbsp;:&nbsp;$220</span>
                <span>65 stocks available</span>
            </div>
            <div>
                <strong>Quantities&nbsp;<input type="number" id="p_qty_1" min="1" max="20" value="1"></strong><button>Remove</button>
                <input type="hidden" id="p_id_1" value="1">
                <input type="hidden" id="p_price_1" value="220">
            </div>
        </div>

        <div class="cart" id="cart_2">
            <img src="img/product/battery1.jpg" alt="5kw battery, Led-acid">
            <div>
                <span>5kw battery, Led-acid</span>
                <span>Price</strong>&nbsp;:&nbsp;$180</span>
                <span>65 stocks available</span>
            </div>
            <div>
                <strong>Quantities&nbsp;<input type="number" id="p_qty_2" min="1" max="20" value="1"></strong><button>Remove</button>
                <input type="hidden" id="p_id_2" value="2">
                <input type="hidden" id="p_price_2" value="180">
            </div>
        </div>

        <div id="cart_summary" class="table-responsive-sm">
            <table class="table table-sm table-striped caption-top">
                <caption>Order Summary</caption>
                <thead class="table-dark">
                    <tr><th scope="col">s/n</th> <th scope="col">Product name</th> <th scope="col">Qty</th> <th scope="col">price/Qty($)</th> <th scope="col">Amount($)</th></tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr><td colspan="4">Total</td><td id="total"></td></tr>
                </tfoot>
            </table>
            <form>
                <input type="hidden" name="products_id">
                <input type="hidden" name="products_qty">
            <input type="submit" value="Continue to payment">
            </form>
        </div>
    </section>
<?php
include_once('include/footer.php');
?>