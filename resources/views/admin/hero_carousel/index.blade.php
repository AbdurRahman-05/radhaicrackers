@extends('layouts.admin')

@section('page-title', 'Hero Carousel Banners')

@section('content')
<div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Hero Carousel Banners</h2>
            <p class="text-sm text-gray-500 mt-1">Manage the top banners on your homepage. Easily change images, headings, and order button links.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ url('/') }}" target="_blank" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition text-sm font-medium flex items-center gap-1.5">
                <i class="fas fa-external-link-alt"></i> View Home Page
            </a>
            <a href="{{ route('admin.hero_carousel.create') }}" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg transition text-sm font-semibold shadow flex items-center gap-1.5">
                <i class="fas fa-plus"></i> Add New Slide
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
                    <th scope="col" class="px-5 py-3.5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Order</th>
                    <th scope="col" class="px-5 py-3.5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Banner Image</th>
                    <th scope="col" class="px-5 py-3.5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Title / Subtitle</th>
                    <th scope="col" class="px-5 py-3.5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Button & Link</th>
                    <th scope="col" class="px-5 py-3.5 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-5 py-3.5 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($slides as $slide)
                    <tr class="hover:bg-amber-50/30 transition">
                        <td class="px-5 py-4 whitespace-nowrap text-sm font-semibold text-gray-700">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-gray-100 text-gray-800 text-xs font-bold">
                                {{ $slide->sort_order }}
                            </span>
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap">
                            <div class="relative w-40 h-20 rounded-lg overflow-hidden border border-gray-200 shadow-sm bg-gray-100">
                                <img src="{{ $slide->image_url }}" alt="{{ $slide->title }}" class="w-full h-full object-cover">
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <div class="text-sm font-bold text-gray-900">{{ $slide->title ?: 'No Title' }}</div>
                            @if($slide->subtitle)
                                <div class="text-xs text-gray-500 mt-0.5 line-clamp-2">{{ $slide->subtitle }}</div>
                            @endif
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap">
                            <div class="text-xs font-semibold text-gray-800">
                                <span class="bg-gray-100 px-2 py-0.5 rounded text-gray-700">{{ $slide->button_text ?: 'Order Now' }}</span>
                            </div>
                            <div class="text-xs text-blue-600 truncate max-w-xs mt-1">
                                <a href="{{ $slide->link_url }}" target="_blank" class="hover:underline">{{ $slide->link_url ?: '/' }}</a>
                            </div>
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap text-center">
                            <form action="{{ route('admin.hero_carousel.toggle', $slide->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1 text-xs font-bold rounded-full transition {{ $slide->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-red-100 text-red-700 hover:bg-red-200' }}" title="Click to toggle status">
                                    {{ $slide->is_active ? '● Active' : '○ Inactive' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.hero_carousel.edit', $slide->id) }}" class="p-2 text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition" title="Edit Slide">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <form action="{{ route('admin.hero_carousel.destroy', $slide->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this carousel slide?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 rounded-lg transition" title="Delete Slide">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            <i class="fas fa-images text-4xl mb-3 text-gray-300"></i>
                            <p class="text-base font-medium text-gray-600">No Carousel Banners Found</p>
                            <p class="text-xs text-gray-400 mt-1">Click "+ Add New Slide" to add your first banner.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
