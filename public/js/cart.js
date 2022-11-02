$(document).ready(function () {

    // Add session items to cart on load
    window.onload = function() {
        const userId = $('.userId').length;

        if (!userId && window.location.pathname === "/") {
            let cart = JSON.parse(sessionStorage.getItem("cart"));
            let cartItemCount = $.isEmptyObject(cart) ? 0 : cart['length'];

            if (cartItemCount) {
                let cartHtml = '';
                let productHtml = '';
                let generalHtml = '';
                let totalPrice = 0;
                $('.totalCartItems').text(cartItemCount);

                // Add cart button
                cartHtml +=
                    '<button class="btn btn-sm btn-success cartDisplay">Open cart</button>'
                $('.cart').append(cartHtml);

                $.each(cart, function(index, value) {
                    productHtml +=
                        '<div class="cartProduct" id="cartProduct'+ value['id'] +'">' +
                        '<span>'+value["title"]+'</span>' +
                        '<p id="cartProductPrice' + value['id'] +'">'+value['total'].toFixed(2)+'</p>' +
                        '</div>'

                    totalPrice += value['total'];
                });

                $('.cartProducts').append(productHtml);

                generalHtml +=
                    '<span class="text-center totalPrice">Total price: '+totalPrice.toFixed(2)+'</span>' +
                    '<div class="cartButtons">' +
                    '<button class="btn btn-secondary btn-sm clearCart">Clear cart</button>' +
                    '<button class="btn btn-primary btn-sm checkout">Checkout</button>' +
                    '</div>';

                $('.cartContent').append(generalHtml);
            }
        } else {
            let cartProductClass = $('.cartProducts');

            // Create a session storage for cart products of a logged in user
            if (cartProductClass.children().length > 0) {
                cartProductClass.each(function () {
                    console.log($(this));
                });
            }
        }
    };

    // Count item price
    function priceCounter(userId, itemCount, memberPrice, regularPrice) {
        return userId ? itemCount * memberPrice : itemCount * regularPrice;
    }

    // Add clicked item to cart
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

        if (cartItemCount) {
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
        }

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
                '<div class="cartProduct" id="cartProduct'+ productArray['id'] +'">' +
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

    // Remove clicked item from cart
    function removeFromCart(productId, userId) {
        let cart = JSON.parse(sessionStorage.getItem("cart"));
        let cartItemCount = $.isEmptyObject(cart) ? 0 : cart['length'];
        let totalPrice = 0;

        let onlyCartItemCount = 0;
        if (cartItemCount === 1) {
            onlyCartItemCount = cart[0]['count'];
        }

        if (onlyCartItemCount > 1 || onlyCartItemCount === 0) {
            let newCart = [];
            $.each(cart, function(index, value) {
                if (value['id'] === productId) {
                    if (value['count'] > 1) {
                        let newValue = value;
                        newValue['count'] = value['count']-1;
                        newValue['total'] = priceCounter(userId, newValue['count'], newValue['memberPrice'], newValue['regularPrice']);

                        totalPrice += newValue['total'];
                        $('#cartProductPrice' + value['id']).text(newValue['total']);
                        newCart.push(newValue);
                    } else {
                        $('#cartProduct' + value['id']).remove();
                    }
                } else {
                    totalPrice += value['total'];
                    newCart.push(value);
                }
            });

            $('.totalPrice').text('Total price: ' + totalPrice.toFixed(2));

            let newItemCount = newCart.length;
            $('.totalCartItems').text(newItemCount);

            sessionStorage.setItem("cart", JSON.stringify(newCart));
        } else {
            clearSessionCart(userId);
        }
    }

    // Clear the cart completely
    function clearSessionCart(userId) {
        sessionStorage.removeItem("cart");

        $('.cartContent').slideUp("fast");
        $('.cartProducts').empty();
        $('.totalPrice').remove();
        $('.cartButtons').remove();
        $('.cartDisplay').fadeOut("fast").remove();
        $('.totalCartItems').text(0);

        if (userId) {
            $.ajax({
                type: "POST",
                url: "/cart/clear",
                data: {
                    userId: userId
                },
                dataType: "json",
                success: function (response) {},
            });
        }
    }

    // Cart click functions

    // Add
    $(".addToCart").on("click",function () {
        const userId = $('.userId').attr('id');
        const productId = this.id;

        addToCart(productId, userId);

        if (userId) {
            $.ajax({
                type: "POST",
                url: "/cart/add",
                data: {
                    userId: userId,
                    productId: productId
                },
                dataType: "json",
                success: function (response) {},
            });
        }
    });

    // Remove single item
    $(".removeFromCart").on("click",function () {
        let userId = $('.userId').attr('id');
        let productId = this.id;

        removeFromCart(productId, userId);

        if (userId) {
            $.ajax({
                type: "POST",
                url: "/cart/remove",
                data: {
                    userId: userId,
                    productId: productId
                },
                dataType: "json",
                success: function (response) {},
            });
        }
    });

    // Clear cart
    $("body").on("click", ".clearCart", function(){
        let userId = $('.userId').attr('id');

        clearSessionCart(userId);
    });

    // Display cart
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
