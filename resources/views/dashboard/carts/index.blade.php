@extends('layouts.dashboard')

@section('content')
<section class="breadcrumb-section section-b-space" style="padding-top:20px;padding-bottom:20px;">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3>All Carts</h3>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-home"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Carts</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="cart-section section-b-space">
    <div class="container">
        @if($carts->isEmpty())
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2>No carts found.</h2>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-md-12">
                    <table class="table cart-table">
                        <thead>
                            <tr>
                                <th scope="col">User</th>
                                <th scope="col">Product</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Total Price</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($carts as $cart)
                                <tr>
                                    <td>{{ $cart->user->name }}</td>
                                    <td>{{ $cart->product->name }}</td>
                                    <td>{{ $cart->quantity }}</td>
                                    <td>${{ $cart->product->regular_price * $cart->quantity }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('dashboard.carts.destroy', $cart->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var successMessage = "{{ session('success') }}";
        var errorMessage = "{{ session('error') }}";

        if (successMessage) {
            Swal.fire({
                title: 'Success!',
                text: successMessage,
                icon: 'success',
                confirmButtonText: 'OK'
            });
        }

        if (errorMessage) {
            Swal.fire({
                title: 'Error!',
                text: errorMessage,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
</script>
@endsection
