@extends('layouts.app')

@section('title', 'My Orders - Irfan Store')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">My Orders</h1>
    <p class="mt-1 text-sm text-gray-500">View all your order history and current orders.</p>
    <a href="{{ route('orders.create') }}" class="mt-4 inline-block bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-150 ease-in-out">
        <i class="fas fa-plus mr-1"></i> Create Order
    </a>
</div>

<div class="bg-white shadow rounded-lg overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                <th class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($orders as $order)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900">{{ $order->order_number }}</td>
                    <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-{{ $order->status === 'completed' ? 'green' : ($order->status === 'pending' ? 'yellow' : 'gray') }}-100 text-{{ $order->status === 'completed' ? 'green' : ($order->status === 'pending' ? 'yellow' : 'gray') }}-800">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                        {{ $order->orderItems->count() }} items
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:text-blue-900">
                            <i class="fas fa-eye mr-1"></i> View Details
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        <div class="py-8">
                            <i class="fas fa-shopping-bag text-4xl text-gray-300 mb-4"></i>
                            <p class="text-lg font-medium text-gray-900 mb-2">No orders yet</p>
                            <p class="text-gray-500">You haven't placed any orders yet.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($orders->count() > 0)
<div class="mt-6 bg-gray-50 rounded-lg p-4">
    <h3 class="text-sm font-medium text-gray-900 mb-2">Order Status Guide:</h3>
    <div class="flex flex-wrap gap-4 text-xs">
        <div class="flex items-center">
            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-800 mr-2">Pending</span>
            <span class="text-gray-600">Order is being processed</span>
        </div>
        <div class="flex items-center">
            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800 mr-2">Completed</span>
            <span class="text-gray-600">Order has been fulfilled</span>
        </div>
        <div class="flex items-center">
            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800 mr-2">Cancelled</span>
            <span class="text-gray-600">Order was cancelled</span>
        </div>
    </div>
</div>
@endif
@endsection
