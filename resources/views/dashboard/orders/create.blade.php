@extends('layouts.app')

@section('title', 'Create Order - Irfan Store')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow rounded-lg p-8">
    <h1 class="text-2xl font-bold mb-6">Create New Order</h1>
    <form action="{{ route('orders.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="user_id" class="block text-sm font-medium text-gray-700">User</label>
            <select name="user_id" id="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                <option value="">Select user</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
            @error('user_id')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Order Items</label>
            <div id="order-items">
                <div class="flex space-x-2 mb-2 order-item-row">
                    <select name="products[]" class="block w-1/2 rounded-md border-gray-300">
                        <option value="">Select product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="quantities[]" min="1" value="1" class="block w-1/4 rounded-md border-gray-300" placeholder="Qty">
                    <button type="button" class="remove-item bg-red-500 text-white px-2 rounded">&times;</button>
                </div>
            </div>
            <button type="button" id="add-item" class="mt-2 bg-primary-600 hover:bg-primary-700 text-white px-3 py-1 rounded">Add Product</button>
            @error('products')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300">
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
            @error('status')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-4">
            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
            <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300">{{ old('notes') }}</textarea>
            @error('notes')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-lg font-semibold">Create Order</button>
        </div>
    </form>
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
