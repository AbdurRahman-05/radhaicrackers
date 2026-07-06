@extends('layouts.admin')

@section('page-title', 'Home Page Products')

@section('content')
<div class="p-6 bg-white rounded-lg shadow-md">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Home Page Content Management</h2>
            <p class="text-gray-600">Manage which products appear on the home page</p>
        </div>
        <button wire:click="showCreateModal" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors mt-4 sm:mt-0">
            + Add Home Page Product
        </button>
    </div>

    <!-- Modal -->
    @if($showModal)
    <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-40">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl mx-4 p-6 relative">
            <button wire:click="resetForm" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-2xl">&times;</button>
            <h3 class="text-xl font-semibold mb-4">{{ $isEdit ? 'Edit' : 'Add' }} Home Page Product</h3>
            <form wire:submit.prevent="{{ $isEdit ? 'updateProduct' : 'saveProduct' }}" enctype="multipart/form-data" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Product Name *</label>
                        <input type="text" wire:model.defer="item_name" class="w-full px-3 py-2 border rounded-md" required>
                        @error('item_name') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Category *</label>
                        <input type="number" wire:model.defer="category" class="w-full px-3 py-2 border rounded-md" required>
                        @error('category') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Description</label>
                    <textarea wire:model.defer="description" rows="2" class="w-full px-3 py-2 border rounded-md"></textarea>
                    @error('description') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Original Price (₹)</label>
                        <input type="number" step="0.01" wire:model.defer="original_price" class="w-full px-3 py-2 border rounded-md">
                        @error('original_price') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Main Discount (%)</label>
                        <input type="number" wire:model.defer="discount_percentage" class="w-full px-3 py-2 border rounded-md">
                        @error('discount_percentage') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Special Discount (%)</label>
                        <input type="number" wire:model.defer="special_discount_percentage" class="w-full px-3 py-2 border rounded-md">
                        @error('special_discount_percentage') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Final Price (₹) *</label>
                        <input type="number" step="0.01" wire:model.defer="price" class="w-full px-3 py-2 border rounded-md" required>
                        @error('price') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Initial Quantity *</label>
                        <input type="number" wire:model.defer="quantity" class="w-full px-3 py-2 border rounded-md" required>
                        @error('quantity') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex items-center mt-6">
                        <input type="checkbox" wire:model.defer="is_active" class="h-4 w-4 text-green-600 border-gray-300 rounded">
                        <label class="ml-2 text-sm">Active (Available for purchase)</label>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Product Image</label>
                    <input type="file" wire:model="image" class="w-full px-3 py-2 border rounded-md">
                    @if($image_path)
                        <img src="{{ asset('storage/'.$image_path) }}" class="h-16 mt-2">
                    @endif
                    @if($image)
                        <img src="{{ $image->temporaryUrl() }}" class="h-16 mt-2">
                    @endif
                    @error('image') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                </div>
<div>
                    <label class="block text-sm font-medium mb-1">Youtube URL</label>
                    <input type="text" wire:model.defer="youtube_url" class="w-full px-3 py-2 border rounded-md">
                    @error('youtube_url') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" wire:model.defer="is_popular" class="h-4 w-4 text-yellow-500 border-gray-300 rounded">
                        <span class="text-sm">Popular</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" wire:model.defer="is_latest" class="h-4 w-4 text-blue-500 border-gray-300 rounded">
                        <span class="text-sm">Latest</span>
                    </label>
                </div>
                <div class="flex justify-end gap-4 mt-4">
                    <button type="button" wire:click="resetForm" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">Cancel</button>
                    <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        {{ $isEdit ? 'Update' : 'Add' }} Product
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Product Table -->
    <div class="overflow-x-auto mt-8">
        <table class="min-w-full bg-white border border-gray-200">
            <thead class="bg-gray-50">
                <tr>
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $product->item_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->category }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹{{ number_format($product->price, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $product->quantity }}</td>
                        <td class="px-6 py-4 text-center">
                            <input type="checkbox" disabled {{ $product->is_popular ? 'checked' : '' }} class="h-4 w-4 text-yellow-500 border-gray-300 rounded">
                        </td>
                        <td class="px-6 py-4 text-center">
                            <input type="checkbox" disabled {{ $product->is_latest ? 'checked' : '' }} class="h-4 w-4 text-blue-500 border-gray-300 rounded">
                        </td>
                        <td class="px-6 py-4 text-center flex justify-center gap-2">
                            <button wire:click="showEditModal({{ $product->id }})" class="text-green-500 hover:text-green-700" title="Edit"><i class="fas fa-edit"></i></button>
                            <button wire:click="deleteProduct({{ $product->id }})" class="text-red-500 hover:text-red-700" title="Delete"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
                @if($products->isEmpty())
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No products found</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
