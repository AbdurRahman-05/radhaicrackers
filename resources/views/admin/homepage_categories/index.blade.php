@extends('layouts.admin')

@section('page-title', 'Category Cards (2026)')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-bold text-gray-900">Home Category Cards (2026)</h1>
                <span class="bg-amber-100 text-amber-800 text-xs px-2.5 py-0.5 rounded-full font-bold">2026 Current Images</span>
            </div>
            <p class="text-sm text-gray-500 mt-1">Manage the "Best For Your Categories" showcase cards on the homepage. Change images to 2026 current year photos, reorder, or edit manually.</p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <form action="{{ route('admin.homepage_categories.reset_defaults') }}" method="POST" onsubmit="return confirm('Reset all category cards to current 2026 high-resolution images?');">
                @csrf
                <button type="submit" class="bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-300 px-4 py-2 rounded-lg text-sm font-semibold transition flex items-center gap-1.5 shadow-sm">
                    <i class="fas fa-sync-alt"></i> Reset to 2026 Defaults
                </button>
            </form>
            <a href="{{ route('admin.homepage_categories.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-bold shadow transition flex items-center gap-1.5">
                <i class="fas fa-plus"></i> Add Category Card
            </a>
            <a href="{{ url('/') }}#categories" target="_blank" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-1.5">
                <i class="fas fa-external-link-alt"></i> Preview Live
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg text-green-800 text-sm flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle text-green-600"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">&times;</button>
        </div>
    @endif

    <!-- Cards Grid Preview -->
    <div class="mb-8">
        <h2 class="text-base font-bold text-gray-800 mb-3 flex items-center gap-2">
            <span>Live Display Preview</span>
            <span class="text-xs text-gray-500 font-normal">(As shown on homepage with 2026 current year images)</span>
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-4">
            @foreach($categories as $cat)
                <div class="bg-white rounded-xl shadow border {{ $cat->is_active ? 'border-gray-200 hover:border-amber-400' : 'border-red-200 opacity-60' }} transition flex flex-col justify-between overflow-hidden">
                    <div class="relative bg-gray-50 h-32 flex items-center justify-center p-2">
                        <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}" class="max-h-full max-w-full object-contain">
                        @if(!$cat->is_active)
                            <span class="absolute top-1 left-1 bg-red-600 text-white text-[10px] px-1.5 py-0.5 rounded font-bold">Hidden</span>
                        @else
                            <span class="absolute top-1 left-1 bg-green-600 text-white text-[10px] px-1.5 py-0.5 rounded font-bold">2026 Current</span>
                        @endif
                        <span class="absolute top-1 right-1 bg-gray-800/70 text-white text-[10px] px-1.5 py-0.5 rounded">#{{ $cat->sort_order }}</span>
                    </div>
                    <div class="p-3 text-center bg-white border-t border-gray-100 flex flex-col justify-between flex-1">
                        <div>
                            <h3 class="font-bold text-gray-900 text-xs truncate" title="{{ $cat->name }}">{{ $cat->name }}</h3>
                            <p class="text-[11px] text-gray-500 mt-0.5">{{ $cat->display_count }} Products</p>
                        </div>
                        <div class="mt-2 pt-2 border-t border-gray-100 flex items-center justify-center gap-2">
                            <a href="{{ route('admin.homepage_categories.edit', $cat->id) }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold flex items-center gap-1">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <span class="text-gray-300">|</span>
                            <form action="{{ route('admin.homepage_categories.toggle', $cat->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-xs {{ $cat->is_active ? 'text-amber-600 hover:text-amber-800' : 'text-green-600 hover:text-green-800' }} font-semibold">
                                    {{ $cat->is_active ? 'Hide' : 'Show' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Full Table View -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="font-bold text-gray-900">All Category Cards ({{ $categories->count() }})</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-700 uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-6 py-3">Order</th>
                        <th class="px-6 py-3">Image Preview</th>
                        <th class="px-6 py-3">Category Name</th>
                        <th class="px-6 py-3">Search Tag</th>
                        <th class="px-6 py-3">Product Count</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($categories as $cat)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-bold text-gray-900">#{{ $cat->sort_order }}</td>
                            <td class="px-6 py-4">
                                <div class="w-14 h-14 bg-gray-100 rounded-lg p-1 border border-gray-200 flex items-center justify-center">
                                    <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}" class="max-h-full max-w-full object-contain">
                                </div>
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-900">
                                {{ $cat->name }}
                            </td>
                            <td class="px-6 py-4 text-xs font-mono text-gray-500">
                                {{ $cat->search_term ?: '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-gray-800">{{ $cat->display_count }} Products</span>
                                @if($cat->auto_count)
                                    <span class="ml-1 text-[10px] bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded">Auto</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{ route('admin.homepage_categories.toggle', $cat->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 rounded-full text-xs font-bold {{ $cat->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $cat->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.homepage_categories.edit', $cat->id) }}" class="p-2 text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition inline-block" title="Edit Card & Select Image">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                @if($categories->count() > 7)
                                    <form action="{{ route('admin.homepage_categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category card?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 rounded-lg transition" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-400">
                                No categories found. Click "Reset to 2026 Defaults" above to load standard categories.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
