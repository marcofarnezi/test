@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Product</div>

                    <div class="card-body">
                        <table>
                            <tr>
                                <td>Amount</td>
                                <td>Discount</td>
                                <td>Total</td>
                            </tr>
                            <tbody id="order">

                            </tbody>
                        </table>
                        <table>
                            <tbody id="items">
                            <tr>
                                <td>Amount</td>
                                <td>Product</td>
                                <td>Price</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <label for="coupon">Coupon</label>
                    <input type="text" id="coupon">
                    <button id="addCoupon">Add coupon</button>
                    <button id="pay">Process payment</button>
                </div>
            </div>
            <input type="hidden" id="productId">
        </div>
    </div>
<script>
    let modelOrderMain = '<tr><td>${total}</td><td>${discount}</td></tr>'
    let modelItem = '<tr><td>{count}</td><td>{title}</td><td>${price}</td><td><button class="removeItem" data-id="{productId}">remove an item</button></td></tr>'
    function update() {
        $.ajax({
            url: "/api/order/{{ $orderId }}",
            method: 'get',
            success: function (item) {
                let modelOrder = modelOrderMain
                modelOrder = modelOrder.replace('{total}', item.order.total / 100)
                modelOrder = modelOrder.replace('{discount}', item.order.discount === 0 ? 0 : item.order.discount / 100)
                $('#order').html(modelOrder)

                let models = item.items.map(function (item) {
                    let newModel = modelItem;
                    newModel = newModel.replace('{count}', item.count)
                    newModel = newModel.replace('{title}', item.product.title)
                    newModel = newModel.replace('{price}', item.count * item.product.price / 100)
                    newModel = newModel.replace('{productId}', item.product.id)
                    return newModel
                })
                $("#items").html(models.join())
                $('.removeItem').click(function () {
                    remove($(this).data('id'))
                })
            }
        });
    }
    function remove(id) {
        $.ajax({
            url: "/api/card/{{ $orderId }}/product/"+id,
            method: 'delete',
            success: function (item) {
                let modelOrder = modelOrderMain
                modelOrder = modelOrder.replace('{total}', item.order.total / 100)
                modelOrder = modelOrder.replace('{discount}', item.order.discount === 0 ? 0 : item.order.discount / 100)
                $('#order').html(modelOrder)

                let models = item.items.map(function (item) {
                    let newModel = modelItem;
                    newModel = newModel.replace('{count}', item.count)
                    newModel = newModel.replace('{title}', item.product.title)
                    newModel = newModel.replace('{price}', item.count * item.product.price / 100)
                    newModel = newModel.replace('{productId}', item.product.id)
                    return newModel
                })
                $("#items").html(models.join())
                $('.removeItem').click(function () {
                    remove(id)
                })
            }
        });
    }
    $(document).ready(function() {
        update();
        $('#pay').click(function () {
            var userId = '{{ empty(Auth::user()) ?: Auth::user()->id }}';
            $.ajax({
                url: "/api/payment/{{ $orderId }}/user/"+userId,
                method: 'put',
                success: function (item) {
                    alert('Payed');
                    removeCookie({{ $orderId }})
                },
                error: function (error) {
                    alert(error.responseJSON)
                }
            });
        })

        $('#addCoupon').click(function () {
            let code = $('#coupon').val()
            if (code === '') {
                alert('Coupon should not be empty')
                return
            }
            $.ajax({
                url: "/api/coupon/"+$('#coupon').val()+"/order/{{ $orderId }}",
                method: 'put',
                success: function (item) {
                    let modelOrder = modelOrderMain
                    modelOrder = modelOrder.replace('{total}', item.order.total / 100)
                    modelOrder = modelOrder.replace('{discount}', item.order.discount === 0 ? 0 : item.order.discount / 100)
                    $('#order').html(modelOrder)
                    let models = item.items.map(function (item) {
                        let newModel = modelItem;
                        newModel = newModel.replace('{count}', item.count)
                        newModel = newModel.replace('{title}', item.product.title)
                        newModel = newModel.replace('{price}', item.count * item.product.price / 100)
                        newModel = newModel.replace('{productId}', item.product.id)
                        return newModel
                    })
                    $("#items").html(models.join())
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
