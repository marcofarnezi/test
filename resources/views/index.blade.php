@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Products</div>

                    <div class="card-body">
                        <table>
                            <tr>
                                <td>Title</td>
                                <td>Price</td>
                            </tr>
                            <tbody id="products">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
    let model = '<tr><td><a href="/product/{id}">{name}</a></td><td>{price}</td></tr>'
    $.ajax({
        url: "/api/products",
        method: 'get',
        success: function( result ) {
            let models = result.map(function (item) {
                let newModel = model;
                newModel = newModel.replace('{name}', item.title)
                newModel = newModel.replace('{price}', item.price/100)
                newModel = newModel.replace('{id}', item.id)
                return newModel
            })
            $("#products").html(models.join())
        }
    });
</script>
@endsection
