
<div class=" bg-white px-2 py-2">
  <div class="max-w-6xl mx-auto relative">
    <!-- Sticky Header -->
    <div class="sticky top-0 z-30 bg-white backdrop-blur-md rounded-2xl flex flex-col md:flex-row items-center justify-between px-8 py-5 mb-8 ">
      <h1 class="text-3xl font-extrabold text-black tracking-tight drop-shadow-sm flex items-center gap-2">
        <svg class="w-8 h-8 text-black" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7M16 3v4M8 3v4m-5 4h18"/></svg>
        Stock Image Gallery
      </h1>
      <a href="{{ route('admin.stocks') }}" class="ml-0 md:ml-4 mt-4 md:mt-0 bg-gray-600 text-white px-6 py-2 rounded-xl font-semibold shadow hover:bg-gray-500 transition-colors flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        Back to Stocks
      </a>
    </div>

    <!-- Floating Upload Form -->
    <div class="fixed bottom-8 right-8 z-40">
      <form wire:submit.prevent="uploadImages" enctype="multipart/form-data" class="flex flex-col md:flex-row items-center gap-4 bg-white/90 p-6 rounded-2xl shadow-2xl border border-blue-200">
        <input type="file" wire:model="uploadedImages" multiple accept="image/*" class="block w-full md:w-auto text-base text-blue-900 file:mr-4 file:py-2 file:px-6 file:rounded-lg file:border-0 file:text-base file:font-semibold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 transition" />
        @error('uploadedImages') <span class="text-red-500 block mb-2">{{ $message }}</span> @enderror
        @error('uploadedImages.*') <span class="text-red-500 block mb-2">{{ $message }}</span> @enderror
        <button type="submit" class="bg-gradient-to-r from-green-500 to-blue-500 text-white px-8 py-3 rounded-xl shadow-lg hover:from-green-600 hover:to-blue-600 transition font-bold text-lg tracking-wide flex items-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
          Upload Images
        </button>
      </form>
    </div>

    <!-- Session Messages -->
    <div class="max-w-2xl mx-auto mt-4">
      @if (session()->has('success'))
        <div class="bg-green-50 border border-green-300 text-green-800 px-6 py-3 rounded-xl mb-4 text-center text-base font-semibold shadow">{{ session('success') }}</div>
      @endif
      @if (session()->has('error'))
        <div class="bg-red-50 border border-red-300 text-red-800 px-6 py-3 rounded-xl mb-4 text-center text-base font-semibold shadow">{{ session('error') }}</div>
      @endif
    </div>

    <!-- Masonry Gallery Grid -->
    <div class="columns-1 sm:columns-2 md:columns-3 gap-6 space-y-6 mt-8">
      @forelse ($images as $img)
        <div class="break-inside-avoid relative group bg-white/95 rounded-3xl shadow-xl p-5 flex flex-col items-center w-full mb-6 border border-gray-200 hover:shadow-2xl transition-all duration-200">
          <div class="w-full h-56 flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl overflow-hidden mb-4 border border-blue-100">
            <img src="/{{ $img->image_path }}" alt="Stock Image" class="object-contain w-full h-full transition-transform duration-200 group-hover:scale-105" />
          </div>
          @if ($showDelete)
            <button wire:click="deleteImage({{ $img->id }})" class="absolute top-4 right-4 bg-red-500 text-white rounded-full px-4 py-1 text-xs font-bold opacity-0 group-hover:opacity-100 transition-all shadow-lg hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400">Delete</button>
          @endif
        </div>
      @empty
        <div class="col-span-full text-center text-gray-400 text-xl py-16 font-medium">No images uploaded yet.</div>
      @endforelse
    </div>
  </div>
</div>

 
