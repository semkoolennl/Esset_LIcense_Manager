/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/shoppingCart.css';
import $ from 'jquery';

$("#addToOrder").click(function(){
    debugger;
    var product_id = $("#addToOrder").data('product-id');
    var host = $(location).attr('host'); 
    $.ajax({url: "/addOrderLine", success: function(result){
        console.log(result)
    }});
    $.ajax('/addOrderLine', {
        type: 'POST',  // http method
        data: {'product_id' : product_id},  // data to submit
        success: function (data, status, xhr) {
            console.log(data)
        },
        error: function (jqXhr, textStatus, errorMessage) {
            console.log(errorMessage);
        }
    });
});

console.log('Hello Webpack Encore! Edit me in assets/shoppingCart.js');
