@extends('layouts.app')

@section('title', 'Create Order - Irfan Store')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-3xl mx-auto bg-white border border-gray-200 shadow-lg rounded-xl p-8">
        <h1 class="text-2xl font-bold mb-8 text-primary-700">Create New Order</h1>
        <form action="{{ route('orders.store') }}" method="POST">
            @csrf
            <div class="space-y-6 divide-y divide-gray-200">
                <div class="pb-6">
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">User</label>
                    <input type="text" value="{{ Auth::user()->name }}" class="mt-1 block w-full rounded-md border border-gray-300 bg-gray-100" readonly>
                    <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                    @error('user_id')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <div class="pt-6 pb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Order Items</label>
                    <div id="order-items">
                        <div class="flex space-x-2 mb-2 order-item-row">
                            <select name="products[]" class="block w-1/2 rounded-md border border-gray-300">
                                <option value="">Select product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                            <input type="number" name="quantities[]" min="1" value="1" class="block w-1/4 rounded-md border border-gray-300" placeholder="Qty">
                            <button type="button" class="remove-item bg-red-500 text-white px-2 rounded">&times;</button>
                        </div>
                    </div>
                    <button type="button" id="add-item" class="mt-2 bg-primary-600 hover:bg-primary-700 text-white px-3 py-1 rounded">Add Product</button>
                    @error('products')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <div class="pt-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border border-gray-300">{{ old('notes') }}</textarea>
                    @error('notes')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="flex justify-end space-x-3 mt-8">
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('orders.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium transition duration-150 ease-in-out">
                        Cancel
                    </a>
                @else
                    <a href="{{ route('orders.my-orders') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium transition duration-150 ease-in-out">
                        Cancel
                    </a>
                @endif
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-150 ease-in-out">Create Order</button>
            </div>
        </form>
    </div>
</div>
<script>
    document.getElementById('add-item').addEventListener('click', function() {
        const row = document.querySelector('.order-item-row').cloneNode(true);
        row.querySelector('select').value = '';
        row.querySelector('input').value = 1;
        document.getElementById('order-items').appendChild(row);
    });
    document.getElementById('order-items').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item')) {
            if (document.querySelectorAll('.order-item-row').length > 1) {
                e.target.closest('.order-item-row').remove();
            }
        }
    });
</script>
@endsection
