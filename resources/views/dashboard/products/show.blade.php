@extends('layouts.app')

@section('title', 'Product Detail - Irfan Store')

@section('content')
<div class="max-w-xl mx-auto bg-white shadow rounded-lg p-8">
    <div class="flex flex-col items-center mb-6">
        @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="mb-4 rounded shadow" style="max-width:200px;max-height:200px;">
        @else
            <div class="w-40 h-40 flex items-center justify-center bg-gray-100 text-gray-400 rounded mb-4">No Image</div>
        @endif
        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mb-2">
            {{ $product->category->name ?? 'No Category' }}
        </span>
        <div class="text-lg font-semibold text-primary-700 mb-2">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
        <div class="text-sm text-gray-600 mb-2">Stock: {{ $product->stock }} units</div>
    </div>
    <div class="mb-4">
        <h2 class="text-lg font-semibold mb-1">Description</h2>
        <p class="text-gray-700">{{ $product->description }}</p>
    </div>
    <div class="flex justify-end">
        <a href="{{ route('admin.products.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium transition duration-150 ease-in-out">Back to Products</a>
    </div>
</div>
@endsection
