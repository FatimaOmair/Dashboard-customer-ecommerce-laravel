@extends('layouts.dashboard')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Products</li>
    <li class="breadcrumb-item active">Edit Product</li>
@endsection

@section('content')
    <form action="{{ route('products.update', $product->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="mb-3">
            <label for="name" class="form-label">Name:</label>
            <input type="text" id="name" name="name"
            @class([
                'form-control',
                'is-invalid' => $errors->has('name'),
            ])
            value="{{ old('name', $product->name) }}">

            @error('name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description:</label>
            <textarea id="description" name="description"
            @class([
                'form-control',
                'is-invalid' => $errors->has('description'),
            ])>{{ old('description', $product->description) }}</textarea>

            @error('description')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="regular_price" class="form-label">Regular Price:</label>
            <input type="text" id="regular_price" name="regular_price"
            @class([
                'form-control',
                'is-invalid' => $errors->has('regular_price'),
            ])
            value="{{ old('regular_price', $product->regular_price) }}">

            @error('regular_price')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>


        <div class="mb-3">
            <label for="category_id" class="form-label">Category:</label>
            <select id="category_id" name="category_id"
            @class([
                'form-control',
                'is-invalid' => $errors->has('category_id'),
            ])>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $category->id == old('category_id', $product->category_id) ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>

            @error('category_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Image:</label>
            <input type="file" id="image" name="image"
            @class([
                'form-control',
                'is-invalid' => $errors->has('image'),
            ])>

            @error('image')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror

            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="" height="50px">
            @else
                <p>No image available</p>
            @endif
        </div>






        <div class="mb-3">
            <label for="status" class="form-label">Status:</label>
            <select id="status" name="status"
            @class([
                'form-control',
                'is-invalid' => $errors->has('status'),
            ])>
                <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Active</option>
                <option value="draft" {{ old('status', $product->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="archived" {{ old('status', $product->status) == 'archived' ? 'selected' : '' }}>Archived</option>
            </select>

            @error('status')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Update Product</button>
    </form>
@endsection
