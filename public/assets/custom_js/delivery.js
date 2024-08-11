$(document).ready(function() {
// add value
$('.plus').on('click', function() {
    var $input = $(this).closest('.input-step').find('.delivery-quantity');
    var originalQtyReste = parseInt($input.closest('tr').find('.qty_reste').data('original-qty-reste'));
    var value = parseInt($input.val());

    if (value < originalQtyReste) {
        $input.val(value + 1).trigger('change');
    } else {
        $input.val(originalQtyReste).trigger('change');
    }
});

// subtract value
$('.minus').on('click', function() {
    var $input = $(this).closest('.input-step').find('.delivery-quantity');
    var value = parseInt($input.val());

    if (value > 1) {
        $input.val(value - 1).trigger('change');
    } else {
        $input.val(1).trigger('change');
    }
});

// handle change in delivery-quantity
$('.delivery-quantity').on('change', function() {
    var $row = $(this).closest('tr');
    var deliveryQuantity = parseInt($(this).val());
    var originalQtyLivred = parseInt($row.find('.qty_livred').data('original-qty-livred'));
    var originalQtyReste = parseInt($row.find('.qty_reste').data('original-qty-reste'));

    if (deliveryQuantity > originalQtyReste) {
        deliveryQuantity = originalQtyReste;
        $(this).val(deliveryQuantity);
    } else if (deliveryQuantity < 1) {
        deliveryQuantity = 1;
        $(this).val(deliveryQuantity);
    }

    var qtyLivred = originalQtyLivred + deliveryQuantity;
    var qtyReste = originalQtyReste - deliveryQuantity;

    $row.find('.qty_livred').text(qtyLivred);
    $row.find('.qty_reste').text(qtyReste);
});


    // Store the original qty_livred and qty_reste values
    $('.qty_livred').each(function() {
        var originalQty = parseInt($(this).text());
        $(this).data('original-qty-livred', originalQty);
    });

    $('.qty_reste').each(function() {
        var originalQty = parseInt($(this).text());
        $(this).data('original-qty-reste', originalQty);
    });
});
// store delivery
$('.storeDelivery').on('click', function (e) {
    e.preventDefault();

    var tableData = [];

    $('#productTableBody tr').each(function() {
        var $row = $(this);
        var rowData = {

            product_id: $row.find('td.productId').text(),
            command_id: $row.find('td.commandId').text(),
            client_id: $row.find('td.clientId').text(),
            // this to update command details
            commandDetailId: $row.find('td').eq(2).text(),
            qty_livred: $row.find('td.qty_livred').text(),
            qty_reste: $row.find('td.qty_reste').text(),
            delivery_quantity: $row.find('.delivery-quantity').val(),


        };
        tableData.push(rowData);
    });
console.log(tableData);
    data = {
        delivery_method: $('select[name="delivery_method"]').val(),
        deliverer: $('select[name="deliverer"]').val(),
        delivery_date: $('input[name="delivery_date"]').val(),
        client_id: $('input[name="client_id"]').val(),
        command_id: $('input[name="command_id"]').val(),
        comment: $('textarea[name="comment"]').val(),
        tableData: tableData
    }
    console.log(data);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: "/deliveries",
        type: "POST",
        data: data,
        dataType: "json",
        success: function (data) {
            if (data.errors) {
                $.each(data.errors, function(field, error) {
                    $('#'+field+'-error').text((error)[0]);
                });
            }
            if (data.success) {
                Swal.fire(
                    'Super!',
                    'Bon de livraison a été créée avec succès',
                    'success'
                )
                location.reload();
            }
        },
        error: function (data) {
            if (data.error) {
                Swal.fire(
                    'Error!',
                    'Bon de livraison n\'a pas été créée avec succès',
                    'error'
                )
            }
        },
    });

});
