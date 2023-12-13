<div id="notifications" class="flex flex-col absolute top-20 right-5 w-full max-w-[400px] z-50 gap-4">
    @foreach(auth()->user()->unreadNotifications as $notification)
    <div class="text-blue-600 bg-blue-50 w-full p-3 rounded-lg px-3 shadow-md flex flex-col gap-5 relative">
        <a href="{{ $notification['data']['url'] }}?n={{ $notification['id'] }}">
            {{ $notification['data']['title'] }}
        </a>
    </div>
    @endforeach
</div>
