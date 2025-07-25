@extends('layouts.app')

@section('title', 'Edit Order - Irfan Store')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-3xl mx-auto bg-white border border-gray-200 shadow-lg rounded-xl p-8">
        <h1 class="text-2xl font-bold mb-8 text-primary-700">Edit Order</h1>
        <form action="{{ route('orders.update', $order) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-6 divide-y divide-gray-200">
                <div class="pb-6">
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">User</label>
                    <select name="user_id" id="user_id" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-200 focus:ring-opacity-50 transition">
                        <option value="">Select user</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $order->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <div class="pt-6 pb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Order Items</label>
                    <div id="order-items">
                        @foreach($order->orderItems as $item)
                        <div class="flex space-x-2 mb-2 order-item-row">
                            <select name="products[]" class="block w-1/2 rounded-md border border-gray-300">
                                <option value="">Select product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ $item->irfan_product_id == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                @endforeach
                            </select>
                            <input type="number" name="quantities[]" min="1" value="{{ $item->quantity }}" class="block w-1/4 rounded-md border border-gray-300" placeholder="Qty">
                            <button type="button" class="remove-item bg-red-500 text-white px-2 rounded">&times;</button>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" id="add-item" class="mt-2 bg-primary-600 hover:bg-primary-700 text-white px-3 py-1 rounded">Add Product</button>
                    @error('products')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <div class="pt-6 pb-6">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="status" class="mt-1 block w-full rounded-md border border-gray-300">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <div class="pt-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border border-gray-300">{{ old('notes', $order->notes) }}</textarea>
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
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-150 ease-in-out">Update Order</button>
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
