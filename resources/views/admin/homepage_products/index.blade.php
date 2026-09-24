@extends('layouts.admin')

@section('page-title', 'Home Page Products')

@section('content')
<div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Home Page Featured Products</h2>
            <p class="text-sm text-gray-500 mt-1">Manage the products displayed in the "Popular Products" and "Latest Products" sections on your homepage.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            <form action="{{ route('admin.homepage_products.sync_from_stocks') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-amber-100 hover:bg-amber-200 text-amber-900 border border-amber-300 px-3.5 py-2 rounded-lg transition text-xs font-bold flex items-center gap-1.5 shadow-sm" title="Import products marked as popular/latest in inventory">
                    <i class="fas fa-sync-alt"></i> Import from Inventory ({{ $popularStocksCount + $latestStocksCount }})
                </button>
            </form>
            <a href="{{ route('admin.homepage_products.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition text-sm font-bold shadow flex items-center gap-1.5">
                <i class="fas fa-plus"></i> + Add Product
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-5 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg text-green-800 text-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle text-green-500 text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800 text-lg font-bold">&times;</button>
        </div>
    @endif

    <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-5 py-3.5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Image</th>
                    <th scope="col" class="px-5 py-3.5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Product Name</th>
                    <th scope="col" class="px-5 py-3.5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Category</th>
                    <th scope="col" class="px-5 py-3.5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Price</th>
                    <th scope="col" class="px-5 py-3.5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Qty</th>
                    <th scope="col" class="px-5 py-3.5 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Popular</th>
                    <th scope="col" class="px-5 py-3.5 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Latest</th>
                    <th scope="col" class="px-5 py-3.5 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-5 py-3.5 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($products as $product)
                    <tr class="hover:bg-amber-50/30 transition">
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <div class="w-14 h-14 rounded-lg overflow-hidden bg-gray-50 border border-gray-200 flex items-center justify-center p-1">
                                <img src="{{ $product->image_url }}" alt="{{ $product->item_name }}" class="w-full h-full object-contain">
                            </div>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="text-sm font-bold text-gray-900">{{ $product->item_name }}</div>
                            @if($product->description)
                                <div class="text-xs text-gray-500 max-w-xs truncate">{{ $product->description }}</div>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap text-sm text-gray-600">
                            {{ $product->categoryRelation->name ?? $product->category }}
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap text-sm font-semibold text-gray-900">
                            @if($product->original_price && $product->original_price > $product->price)
                                <span class="text-xs line-through text-gray-400 mr-1">₹{{ number_format($product->original_price, 0) }}</span>
                            @endif
                            <span class="text-amber-700 font-bold">₹{{ number_format($product->price, 2) }}</span>
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap text-sm text-gray-600">
                            {{ $product->quantity }}
                        </td>
                        <!-- Quick Toggle Popular -->
                        <td class="px-5 py-3.5 whitespace-nowrap text-center">
                            <form action="{{ route('admin.homepage_products.toggle_popular', $product->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-2.5 py-1 rounded-full text-xs font-bold transition {{ $product->is_popular ? 'bg-amber-100 text-amber-800 hover:bg-amber-200' : 'bg-gray-100 text-gray-400 hover:bg-gray-200' }}" title="Click to toggle Popular status">
                                    {{ $product->is_popular ? '⭐ Popular' : '☆ Not Popular' }}
                                </button>
                            </form>
                        </td>
                        <!-- Quick Toggle Latest -->
                        <td class="px-5 py-3.5 whitespace-nowrap text-center">
                            <form action="{{ route('admin.homepage_products.toggle_latest', $product->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-2.5 py-1 rounded-full text-xs font-bold transition {{ $product->is_latest ? 'bg-blue-100 text-blue-800 hover:bg-blue-200' : 'bg-gray-100 text-gray-400 hover:bg-gray-200' }}" title="Click to toggle Latest status">
                                    {{ $product->is_latest ? '🆕 Latest' : '— Not Latest' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap text-center">
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.homepage_products.edit', $product->id) }}" class="p-2 text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition" title="Edit Product">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <form action="{{ route('admin.homepage_products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this product from the homepage?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 rounded-lg transition" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-gray-400">
                            <i class="fas fa-box-open text-4xl mb-3 text-gray-300"></i>
                            <p class="text-base font-medium text-gray-600">No Home Page Products Added Yet</p>
                            <p class="text-xs text-gray-400 mt-1 max-w-md mx-auto">
                                You can click <strong>"Import from Inventory"</strong> to automatically pull products already marked as popular/latest in your stock catalog, or click <strong>"+ Add Product"</strong> to choose any product.
                            </p>
                            <div class="mt-4 flex justify-center gap-3">
                                <form action="{{ route('admin.homepage_products.sync_from_stocks') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg text-xs font-bold transition">
                                        <i class="fas fa-sync-alt mr-1"></i> Import Featured from Inventory
                                    </button>
                                </form>
                                <a href="{{ route('admin.homepage_products.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-xs font-bold transition">
                                    <i class="fas fa-plus mr-1"></i> + Add New Product
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection