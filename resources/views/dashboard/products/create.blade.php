@extends('layouts.dashboard')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Products</li>
@endsection

@section('content')
    <form action="{{ route('products.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        @if ($errors->any())
            <div class="alert alert-danger">
                <h3>Error Occurred!</h3>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-group">
            <label for="productName">Product Name</label>
            <input type="text" class="form-control" id="productName" name="name" value="{{ old('name', $product->name) }}" required>
        </div>

        <div class="form-group">
            <label for="regular_price">Regular Price</label>
            <input type="number" class="form-control" id="regular_price" name="regular_price" value="{{ old('regular_price', $product->regular_price) }}" required>
        </div>

        <div>
            <label for="store_id">Store:</label>
            <select id="store_id" name="store_id" class="form-control" required>
                @foreach($stores as $store)
                    <option value="{{ $store->id }}" @selected(old('store_id', $product->store_id) == $store->id)>{{ $store->name }}</option>
                @endforeach
            </select>
        </div>


        <div class="form-group">
            <label for="category">Category</label>
            <select name="category_id" class="form-control form-select" required>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>


        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description">{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="form-group">
            <label for="image">Image</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="" height="50px">
            @else
                <p>No image available</p>
            @endif
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <x-form.radio name="status" :checked="$product->status" :options="['active'=>'Active' ,'inactive'=>'Archived']"/>
        </div>

        <button type="submit" class="btn btn-primary">{{ $button_label ?? 'Save' }}</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary ml-2">Cancel</a>
    </form>
@endsection
