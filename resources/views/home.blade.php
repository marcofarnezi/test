@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">User Data</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form id="dataUser">
                        <label for="name">Name</label>
                        <input type="text" id="name">
                        <br>
                        <label for="name">Email</label>
                        <input type="text" id="email">
                        <br>
                        <label for="name">Phone</label>
                        <input type="text" id="phone">
                        <br>
                        <label for="name">Address</label>
                        <input type="text" id="address">
                    </form>
                    <button id="save">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var userId = '{{ empty(Auth::user()) ?: Auth::user()->id }}';
    $.ajax({
        url: "/api/user/"+userId,
        method: 'get',
        success: function( item ) {
            $('#name').val(item.name)
            $('#email').val(item.email)
            $('#phone').val(item.phone)
            $('#address').val(item.address)
        }
    });
    $(document).ready(function() {
        $('#save').click(function () {
            $.ajax({
                url: "/api/user/"+userId,
                method: 'post',
                data: {
                    name: $('#name').val(),
                    email: $('#email').val(),
                    phone: $('#phone').val(),
                    address: $('#address').val(),
                },
                success: function( item ) {
                    alert('Saved')
                },
                error: function (error) {
                    alert(error.responseJSON)
                }
            });
        })
    })
</script>
@endsection
