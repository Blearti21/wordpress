jQuery(document).ready(function($) {
    $('.add-to-cart').on('click', function() {
        var productID = $(this).data('id');
        var button = $(this);

        button.prop('disabled', true).text('Shtohet...');

        $.ajax({
            url: marketplace_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'add_to_cart',
                product_id: productID
            },
            success: function(response) {
                if (response.success) {
                    $('#cart-count').html('<i class="fa-solid fa-cart-shopping"></i> ' + response.data.cart_count);
                    button.text('U shtua ✅');
                } else {
                    alert('Gabim gjatë shtimit në shportë.');
                }
            },
            error: function() {
                alert('Gabim i serverit.');
            },
            complete: function() {
                setTimeout(function() {
                    button.prop('disabled', false).text('Shto në Shportë');
                }, 1500);
            }
        });
    });
});

console.log("Marketplace JavaScript is running fine ✅");
