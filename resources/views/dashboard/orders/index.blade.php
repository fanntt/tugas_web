@extends('layouts.app')

@section('title', 'Orders - Irfan Store')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Orders</h1>
    @if(Auth::user()->role === 'admin')
        <a href="{{ route('orders.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-150 ease-in-out mb-4 inline-block">
            <i class="fas fa-plus mr-1"></i> Create Order
        </a>
    @endif
</div>

<div class="bg-white shadow rounded-lg overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                <th class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($orders as $order)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900">{{ $order->order_number }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->user->name ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($order->total_amount, 2, ',', '.') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-{{ $order->status === 'completed' ? 'green' : ($order->status === 'pending' ? 'yellow' : 'gray') }}-100 text-{{ $order->status === 'completed' ? 'green' : ($order->status === 'pending' ? 'yellow' : 'gray') }}-800">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $order->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:text-blue-900 mr-2"><i class="fas fa-eye"></i></a>
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('orders.edit', $order) }}" class="text-yellow-600 hover:text-yellow-900 mr-2">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('orders.delete', $order) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No orders found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
