<div>
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Shop By Categories</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
        @foreach($categories as $category => $data)
            <a href="{{ $data['url'] }}" class="text-center group">
                <div class="bg-gray-50 rounded-lg p-3 hover:bg-gray-50 transition-colors">
                    <div class="text-2xl mb-2">{{ $data['icon'] }}</div>
                    <div class="text-xs font-medium text-gray-900 group-hover:text-gray-600">{{ $category }}</div>
                    @if($data['count'] > 0)
                        <div class="text-xs text-gray-500">({{ $data['count'] }})</div>
                    @endif
                </div>
            </a>
        @endforeach
    </div>
</div> 