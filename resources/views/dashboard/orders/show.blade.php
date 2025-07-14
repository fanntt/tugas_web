@extends('layouts.app')

@section('title', 'Order Details - Irfan Store')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow rounded-lg p-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold">Order #{{ $order->order_number }}</h1>
        <a href="{{ route('orders.edit', $order) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-150 ease-in-out">
            <i class="fas fa-edit mr-1"></i> Edit
        </a>
    </div>
    <div class="mb-4">
        <span class="font-semibold">User:</span> {{ $order->user->name ?? '-' }}<br>
        <span class="font-semibold">Status:</span> <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-{{ $order->status === 'completed' ? 'green' : ($order->status === 'pending' ? 'yellow' : 'gray') }}-100 text-{{ $order->status === 'completed' ? 'green' : ($order->status === 'pending' ? 'yellow' : 'gray') }}-800">{{ ucfirst($order->status) }}</span><br>
        <span class="font-semibold">Notes:</span> {{ $order->notes ?? '-' }}<br>
        <span class="font-semibold">Created:</span> {{ $order->created_at->format('d M Y H:i') }}
    </div>
    <div class="mb-4">
        <h2 class="text-lg font-semibold mb-2">Order Items</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($order->orderItems as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->product->name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($item->price, 2, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($item->subtotal, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-6 flex justify-end">
        <a href="{{ route('orders.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium transition duration-150 ease-in-out">
            <i class="fas fa-arrow-left mr-1"></i> Back to Orders
        </a>
    </div>
</div>
@endsection
