@extends('layouts.admin')

@section('page-title', 'Home Page Products')

@section('content')
<div class="p-6 bg-white rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Home Page Products</h2>
        <a href="{{ route('admin.homepage_products.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">+ Add Product</a>
    </div>
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif
    <div class="overflow-x-auto mt-4">
        <table class="min-w-full bg-white border border-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Popular</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Latest</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($products as $product)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->item_name }}" class="w-12 h-12 object-cover rounded">
                            @else
                                <span class="text-gray-400">No Image</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $product->item_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->category }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹{{ number_format($product->price, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $product->quantity }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($product->is_popular)
                                <span class="text-yellow-500">&#9733;</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($product->is_latest)
                                <span class="text-blue-500">&#9733;</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center flex justify-center gap-2">
                            <a href="{{ route('admin.homepage_products.edit', $product->id) }}" class="text-green-500 hover:text-green-700" title="Edit"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.homepage_products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700" title="Delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                @if($products->isEmpty())
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">No products found</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection 