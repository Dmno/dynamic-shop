$(document).ready(function () {

    // CART LOGIC
    function priceCounter(userId, itemCount, memberPrice, regularPrice) {
        return userId ? itemCount * memberPrice : itemCount * regularPrice;
    }

    function addToCart(productId, userId) {
        let cartHtml = '';
        let productHtml = '';
        let generalHtml = '';
        let totalPrice = 0;
        let productArray = {
            "id": productId,
            "title": $('#productTitle' + productId).text(),
            "image": $('#productImage' + productId).prop('src'),
            "regularPrice": $('#regularPrice' + productId).find('p').text(),
            "memberPrice": $('#memberPrice' + productId).find('p').text()
        };

        let cart = JSON.parse(sessionStorage.getItem("cart"));
        let cartItemCount = $.isEmptyObject(cart) ? 0 : cart['length'];

        let newCart = [];
        let isDuplicate = false;
        $.each(cart, function(index, value) {
            if (value['id'] === productId) {
                isDuplicate = true;
                let itemCount = value['count']+1;
                let currentItemPrice = priceCounter(userId, itemCount, value['memberPrice'], value['regularPrice']);
                totalPrice += currentItemPrice;

                let duplicateCartItem = value;
                duplicateCartItem['count'] = itemCount;
                duplicateCartItem['total'] = currentItemPrice;
                newCart.push(duplicateCartItem);
                $('#cartProductPrice' + value['id']).text(currentItemPrice.toFixed(2));
            } else {
                totalPrice += priceCounter(userId, value['count'], value['memberPrice'], value['regularPrice']);
                newCart.push(value);
            }
        });

        if (cartItemCount === 0) {
            cartHtml +=
                '<button class="btn btn-sm btn-success cartDisplay">Open cart</button>'

            $('.cart').append(cartHtml);
        }

        if (!isDuplicate) {
            productArray['count'] = 1;
            productArray['total'] = priceCounter(userId, productArray['count'], productArray['memberPrice'], productArray['regularPrice']);
            totalPrice += productArray['total'];
            newCart.push(productArray);

            productHtml +=
                '<div class="cartProduct">' +
                '<span>'+productArray["title"]+'</span>' +
                '<p id="cartProductPrice' + productArray['id'] +'">'+productArray['total'].toFixed(2)+'</p>' +
                '</div>'

            $('.cartProducts').append(productHtml);
        }

        if (cartItemCount === 0 && productHtml) {
            generalHtml +=
                '<span class="text-center totalPrice">Total price: '+totalPrice.toFixed(2)+'</span>' +
                '<div class="cartButtons">' +
                '<button class="btn btn-secondary btn-sm clearCart">Clear cart</button>' +
                '<button class="btn btn-primary btn-sm checkout">Checkout</button>' +
                '</div>';

            $('.cartContent').append(generalHtml);
        }

        $('.totalPrice').text('Total price: ' + totalPrice.toFixed(2));

        let newItemCount = newCart.length;
        $('.totalCartItems').text(newItemCount);

        sessionStorage.setItem("cart", JSON.stringify(newCart));
    }

    function removeFromCart(productId, userId) {
        let cart = JSON.parse(sessionStorage.getItem("cart"));

        let newCart = [];
        $.each(cart, function(index, value) {
            let itemCount;
            if (value['id'] === productId) {
                let newValue = [];
                if (value['count'] > 1) {
                    itemCount = value['count']-1;
                    newValue = value;
                    newValue['count'] = itemCount;
                    newValue['total'] = userId ? newValue['count'] * newValue['memberPrice'] : newValue['count'] * newValue['regularPrice'];

                    newCart.push(newValue);
                }
            } else {
                newCart.push(value);
            }
        });

        sessionStorage.setItem("cart", JSON.stringify(newCart));
    }

    $(".addToCart").on("click",function () {
        const userId = $('.userId').attr('id');
        const productId = this.id;

        addToCart(productId, userId);

        $.ajax({
            type: "POST",
            url: "/cart/add",
            data: {
                userId: userId,
                productId: productId
            },
            dataType: "json",
            success: function (response) {
                console.log('pridejom ' + productId, response)
            },
            // error: function (jqXHR, exception) {
            //     console.log('error - ' + exception)
            // }
        });
    });

    // Remove cart item and add it to session storage
    $(".removeFromCart").on("click",function () {
        let userId = $('.userId').attr('id');
        let productId = this.id;

        removeFromCart(productId, userId);

        $.ajax({
            type: "POST",
            url: "/cart/remove",
            data: {
                userId: userId,
                productId: productId
            },
            dataType: "json",
            success: function (response) {
                console.log('removinom ' + productId, response)
            },
            // error: function (jqXHR, exception) {
            //     console.log('error - ' + exception)
            // }
        });
    });

    $(".clearCart").on("click",function () {
        let userId = $('.userId').attr('id');

        sessionStorage.removeItem("cart");

        // Remove buttons, item count and total price, maybe the entire block, just hide it
        $('.cartProducts').empty();

        $.ajax({
            type: "POST",
            url: "/cart/clear",
            data: {
                userId: userId
            },
            dataType: "json",
            success: function (response) {
                console.log('cleared', response)
            },
        });
    });

    $("body").on("click", ".cartDisplay", function(){
        let cart = $('.cartContent');

        if (cart.is(':hidden')) {
            cart.slideDown("fast");
            $(this).text('Close cart');
        } else {
            cart.slideUp("slow");
            $(this).text('Open cart');
        }
    });
});
