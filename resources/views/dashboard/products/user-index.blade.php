@extends('layouts.app')

@section('title', 'Products - Irfan Store')

@section('content')
<div class="max-w-7xl mx-auto py-8">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($products as $product)
            <div class="bg-white shadow rounded-lg p-4 flex flex-col items-center">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="mb-3 rounded" style="max-width:120px;max-height:120px;">
                @else
                    <div class="w-28 h-28 flex items-center justify-center bg-gray-100 text-gray-400 rounded mb-3">No Image</div>
                @endif
                <div class="font-bold text-lg text-gray-900 mb-1">{{ $product->name }}</div>
                <div class="text-primary-700 font-semibold mb-1">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                <div class="text-gray-600 text-sm mb-2">{{ Str::limit($product->description, 50) }}</div>
            </div>
        @empty
            <div class="col-span-4 text-center text-gray-500">No products found.</div>
        @endforelse
    </div>
</div>
@endsection
