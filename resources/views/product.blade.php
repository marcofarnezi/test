@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Product</div>

                    <div class="card-body">
                        <table id="product">

                        </table>
                    </div>
                    <button id="addCart">Add cart</button>
                </div>
            </div>
            <input type="hidden" id="productId">
        </div>
    </div>
<script>
    let model = '<tr><td>{name}</td></tr><tr><td>{price}</td></tr><tr><td>{description}</td></tr>'
    $.ajax({
        url: "/api/product/{{ $productId }}",
        method: 'get',
        success: function( item ) {
            model = model.replace('{name}', item.title)
            model = model.replace('{price}', item.price/100)
            model = model.replace('{description}', item.description)
            $('#productId').val(item.id)
            $("#product").html(model)
        }
    });
    $(document).ready(function() {

        $('#addCart').click(function () {
            var orderId = getCookie('orderId');
            $.ajax({
                url: "/api/card/"+orderId,
                method: 'post',
                data: {
                    productId: $('#productId').val(),
                    amount: 1
                },
                success: function( item ) {
                    console.log(item)
                    alert('Product add with success')
                    document.cookie = 'orderId='+item.order.id
                    $("#cartLink").attr('href', '/order/'+orderId);
                },
                error: function (error) {
                    alert(error.responseJSON)
                    removeCookie(orderId)
                }
            });
        })
    });
</script>
@endsection
