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
     * @param array $addCartButton | Add item to cart
     * @return button
     */
    public static function addCartButton($cartItem = array()){
        $cartItem = (object)$cartItem;
        $defaultBtn = isset($cartItem->button) ? $cartItem->button : 'Add to card';
        return "<span class='addToCart' data-code='$cartItem->code' data-price='$cartItem->price' data-name='$cartItem->name'>$defaultBtn</span>";
    }

    /**
     * @param array $cartItem Remove item from cart
     */
    public static function removeCart($cartItem = array()){}

    /**
     * @param array $cartItem | Emptyies cart
     */
    public static function emptyCart($cartItem = array()){}

    public function contentcontrollerInit()
    {
        Requirements::javascript('SSPaypalBasic/javascripts/SSPaypalBasic.js?1');
    }

}