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
        //Requirements::javascript('SSPaypalBasic/javascripts/simpleCart.js');
    }

}

class SSPaypalBasicController extends Controller
{
    static $allowed_actions = array(
        'checkout','thankyou'
    );

    public function index()
    {

    }
    public function thankyou($data){
        echo "Thank you we will be in touch soon!";
        echo "<script>localStorage.clear();location.href='../products';</script>";
    }

    public function checkout()
    {
        /**
         * All we are doing is checking over pricing
         */
        if($_POST) //Post Data received from product list page.
        {

        }
    }


}