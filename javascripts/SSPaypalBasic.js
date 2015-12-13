/**
 * Created by Luke Hardiman on 20/09/2015.
 *
 */
if (typeof jQuery == 'undefined')
    throw exception("SSPaypalBasic.js jQuery not loaded");

console.log("SSPaypalBasic.js : loaded ");

var Settings = {
    cmd			: "_cart"
    , upload		: "1"
    , currency_code : 'NZD'
    , business		: 'luke@hardiman.co.nz'
    , rm			: 2
    , tax_cart		: 0//(0*1).toFixed(2)
    , handling_cart : 0//(0*1).toFixed(2)
    , charset		: "utf-8"
    , notify_url    : 'http://persianfeast.nzhost.me/PersianFeast/sspaypalbasic/checkout'
};

var  $shoppingCart = null;
var cartItems = {};



$(document).ready(function () {

    //declare our shopping cart element
    $shoppingCart = $("#shoppingCart");

    //check cart
    if ($shoppingCart.length < 0)
        throw new Error("paypal.js Error missing shopping cart container");

    if (!$shoppingCart.is(':visible'))
        console.log("paypal.js cart is hidden");

    //adding item to cart
    $(".addToCart").click(addToCart);

    $("#checkOut").click(checkOut);

    processCart();
    checkCart();

});

//bind to non existing class
$(document.body).on('click', '.cartRemove', function () {
    console.log("cartRemove Clicked");
    //get product item
    var item_code = $(this).data('item_code');
    console.log("item_code",item_code);
    $("#" + item_code).remove();


    delete(cartItems[item_code]);

    console.log("item_code",cartItems);
    saveCart();
    //process the cart 5
    processCart();

});

function addToCart() {
    var $self = $(this);
    console.log("addToCart clicked")
    //check if showing
    if (!$shoppingCart.is(':visible'))
        $(".shoppingCartContainer").show();

    //lets get the data
    var data = $(this).data();
    console.log("addToCart data",data)

    var qnty = parseInt($('.sslModuleQty',$self.parent()).val());
    console.log("addToCart qnty",qnty)
    data.qty = qnty;

    //check the data
    if (typeof data.name != "string" || typeof data.price != "string") {
        console.log("data", data);
        throw new Error("Error missing data");
    }

    //check if we don't already exist
    if (typeof cartItems[data.code] == "undefined")
    cartItems[data.name] = data;
    else
        cartItems[data.name].qty =  parseInt(cartItems[data.code].qty) + parseInt(data.qty);



    //addRow(data);
    saveCart();
    window.location.href=window.location.href
    //processCart();
}




/**
 * Process Shopping Cart from Storage
 */
function processCart(){
    $shoppingCart.find(".item-list").remove();
    var items = localStorage.getItem('cartItems');

    if (items != null && typeof items == "string")
        cartItems = JSON.parse(items);
        else
        cartItems = {};

    console.log("Loaded cartItems from storage",cartItems)
    //loop items
    $.map(cartItems,addRow);
    $("#cartItemsPaypalTotal").text(calculateTotal());
    checkCart();
}

/**
 * Returns a total of the current cart
 */
function calculateTotal(){
    var currentTotal = 0;

        $.each(cartItems,function(itemName,item){
            currentTotal = currentTotal + parseFloat(item.price) * parseInt(item.qty)
        });

    return currentTotal.toFixed(2);
}

/**
 * Adds a row to the shopping cart parsed my $.map
 * @param data
 */
function addRow(item,index){

    var item_code = (item.code.length > 1) ? item.code : index;



    var cartRow = '<tr id="' +item_code + '" class="item-list">' +
        '<td width="70px"><p style="color: #555">' + item.name  + '</p></td>' +
        '<td width="20" style="padding-left: 10px;"><p style="color: #555;">' + item.qty + '</p></td>' +
        '<td width="10" style="padding-left: 10px;"><p style="color: #555">$' + item.price + '</p></td>' +
        '<td width="10"><i data-item_code="' + item_code + '" class="fa fa-remove cartRemove"></i></td>' +
        '</tr>';
    /*
     @see to checkOut
     */
    //append to last
    $("tr:last", $shoppingCart).after(cartRow);


}

/**
 * Saves cartItems state
 */
function saveCart(){
    localStorage.setItem('cartItems',JSON.stringify(cartItems));
    checkCart();
}
/**
 * Check if cart should be showing.
 */
function checkCart(){

    if ($('tr', $shoppingCart).length <= 1)   //hide shopping cart no items
        $(".shoppingCartContainer").hide();
}

/**
 * CheckOut Creates the form
 */
function checkOut(){
    var $form = $("<form></form>");

    $form.attr('style', 'display:none;');
    $form.attr('action', 'https://sandbox.paypal.com/cgi-bin/webscr');
    $form.attr('method', 'POST');

    var counter = 1;
    $.each(cartItems, function (index, item) {
      //name: "Chargrilled Eggplant Dip", price: "12.00", code: 5, qty: 1}

        console.log("item",item)
        var data = {};

        data["item_name_" + counter] = item.name;

        data["quantity_" + counter] = item.qty;
        data["amount_" + counter] = (item.price * 1).toFixed(2);
        data["item_number_" +counter++] = item.code;

        $form.append(createHiddenInput(data));

       // counter++;
    });
    $form.append(createHiddenInput(Settings));
    console.log("form",$form.html());
    //$shoppingCart.append(form);
    $("body").append($form);
    $form.submit();
}

function createHiddenInput(data){
    var emptyObj = $('<div>');
    $.each(data,function(item,val){
        console.log("adding item");
        var $input = $('<input>');

        emptyObj.append(
            $input.attr("type","hidden").attr("name",item).val(val)
        );

    });

    return emptyObj;
}
