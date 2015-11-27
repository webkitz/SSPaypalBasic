<?php

class SSPaypalBasic extends DataExtension
{    
    protected static $_sandBox = false;
    protected static $_items = array();
    protected static $_cartID = 0;
    protected static $payPalData = array(
        'username'  =>          '',
        'password'  =>          '',
        'signature' =>          ''
    );

    /**
     * @param $sandbox bool sets sandbox mode
     */
    public static function enableSandboxMode()
    {
        self::$_sandBox = true;
    }

    /**
     * Update the cart ID
     */
    public static function cartId()
    {
        return self::$_cartID++;
    }
    /**
     * @param array $payPalApi details
     */
    public static function setPaypal($payPalApi = array()){}

    /**
     * @param array $addCartButton | Add item to cart
     * @return String button
     */
    public static function addCartButton($name = '',$price = '', $code = false, $button = 'Add to card',$qnty = false){

        $code = ($code == false)?  self::cartId() : $code;

        $qntySpan = ($qnty == true)? '<span> Qty : <input class="sslModuleQty" type="text" size="3" value="1" required type="number" min="1" /> x </span>' : '';


        return "<span class='addToCart' data-qty='1' data-code='$code' data-price='$price' data-name='$name'>$qntySpan $button</span>";
    }


    /**
     * @param array $cartItem | Empties cart
     */
    public static function emptyCart($cartItem = array()){}

    public function contentcontrollerInit()
    {
        Requirements::javascript('SSPaypalBasic/javascripts/SSPaypalBasic.js?1');
    }

}

class SSPaypalBasicController extends Controller
{
    static $allowed_actions = array(
        'checkout'
    );

    public function index()
    {

    }

    public function checkout()
    {
        if($_POST) //Post Data received from product list page.
        {
            $ShippinCost        = 3.00; //Although you may change the value later, try to pass in a shipping amount that is reasonably accurate.

            //we need 4 variables from an item, Item Name, Item Price, Item Number and Item Quantity.
            $paypal_data = '';
            $ItemTotalPrice = 0;

            //loop through POST array
            foreach($_POST['item_name'] as $key=>$itmname)
            {
                //get actual product price from database using product code
                $product_code    = filter_var($_POST['item_code'][$key], FILTER_SANITIZE_STRING);
                //retrive the actual price from dbase as this will be bad if user has access
                //$results = $mysqli->query("SELECT price FROM products WHERE product_code='$product_code' LIMIT 1");
                //$obj = $results->fetch_object();
                $item_price = $_POST['item_price'][$key];
                $paypal_data .= '&L_PAYMENTREQUEST_0_NAME'.$key.'='.urlencode($_POST['item_name'][$key]);
                $paypal_data .= '&L_PAYMENTREQUEST_0_NUMBER'.$key.'='.urlencode($_POST['item_code'][$key]);
                $paypal_data .= '&L_PAYMENTREQUEST_0_AMT'.$key.'='.urlencode($item_price);
                $paypal_data .= '&L_PAYMENTREQUEST_0_QTY'.$key.'='. urlencode($_POST['item_qty'][$key]);

                // item price X quantity
                $subtotal = ($item_price * $_POST['item_qty'][$key]);

                //total price
                $ItemTotalPrice = ($ItemTotalPrice + $subtotal);
            }

            //parameters for SetExpressCheckout
            $padata =   '&METHOD=SetExpressCheckout'.
                '&RETURNURL='.urlencode($PayPalReturnURL ).
                '&CANCELURL='.urlencode($PayPalCancelURL).
                '&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE").
                $paypal_data.
                '&NOSHIPPING=0'. //set 1 to hide buyer's shipping address
                '&PAYMENTREQUEST_0_ITEMAMT='.urlencode($ItemTotalPrice).
                //'&PAYMENTREQUEST_0_TAXAMT='.urlencode($TotalTaxAmount).
                '&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($ShippinCost).
                //'&PAYMENTREQUEST_0_HANDLINGAMT='.urlencode($HandalingCost).
                //'&PAYMENTREQUEST_0_SHIPDISCAMT='.urlencode($ShippinDiscount).
                //'&PAYMENTREQUEST_0_INSURANCEAMT='.urlencode($InsuranceCost).
                '&PAYMENTREQUEST_0_AMT='.urlencode($ItemTotalPrice).
                '&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode('NZ').
                '&LOCALECODE=GB'. //PayPal pages to match the language on your website.
                '&LOGOIMG=http://persianfeast.co.nz/index.php/themes/persian-feast/images/logos/logo_main_orange_line.png'. //site logo
                '&CARTBORDERCOLOR=FFFFFF'. //border color of cart
                '&ALLOWNOTE=1';


        }
    }


}