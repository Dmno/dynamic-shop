$(document).ready(function () {

    // Save cart item and add it to session storage
    $(".addToCart").on("click",function () {
        let userId = $('.userId').attr('id');
        let productId = this.id;

        console.log(userId, productId)

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

        console.log(userId, productId)

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


});