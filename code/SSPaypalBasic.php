<?php

class SSPaypalBasic extends DataExtension
{    
    protected static $_sandBox = false;
    protected static $_items = array();
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
     * @param array $payPalApi details
     */
    public static function setPaypal($payPalApi = array()){}

    /**
     * @param array $cartItem | Add item to cart
     */
    public static function addCart($cartItem = array()){}

    /**
     * @param array $cartItem Remove item from cart
     */
    public static function removeCart($cartItem = array()){}

    /**
     * @param array $cartItem | Emptyies cart
     */
    public static function emptyCart($cartItem = array()){}

    function contentcontrollerInit($controller) {
        Requirements::javascript(MODULE_SSPAYPALBASIC_DIR . '/javascripts/SSPaypalBasic.js');
    }

}