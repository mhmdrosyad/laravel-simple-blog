<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Home') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-10 sm:px-6 lg:px-8">
            @guest
                {{-- for gueset users --}}
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <p>Please <a href="{{ route('login') }}" class="text-blue-500">login</a> or
                        <a href="{{ route('register') }}" class="text-blue-500">register</a>.</p>
                    </div>
                </div>
            @endguest
            @auth
                {{-- for authenticated users --}}

                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="mx-auto">
                        @foreach(['success', 'error', 'warning', 'info'] as $msg)
                            <x-flash-message type="{{ $msg }}" />
                        @endforeach
                    </div>
                    <div class="mt-4 space-y-6 p-6">
                        <h2 class="text-lg font-semibold">Your Posts</h2>
                        @if(isset($posts))
                        @php
                            $statusColors = [
                                'active' => 'bg-green-100 text-green-800',
                                'draft' => 'bg-gray-100 text-gray-800',
                                'schedule' => 'bg-yellow-100 text-yellow-800',
                            ];
                        @endphp
                        @foreach($posts as $post)

                        <div class="rounded-md border p-5 shadow">
                            <div class="flex items-center gap-2">
                                <span class="flex-none rounded px-2 py-1 {{ $statusColors[$post->display_status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $post->display_status }}
                                </span>
                                <h3><a href="{{ route('posts.edit', $post->id) }}" class="text-blue-500">{{ $post->title }}</a></h3>
                            </div>
                            <div class="mt-4 flex items-end justify-between">
                                <div>
                                    <div>Published: {{ $post->published_at }}</div>
                                    <div>Updated: {{ $post->updated_at }}</div>
                                </div>
                                <div>
                                    <a href="{{ route('posts.show', $post->id) }}" class="text-blue-500">Detail</a> /
                                    <a href="{{ route('posts.edit', $post->id) }}" class="text-blue-500">Edit</a> /
                                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-500">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif
                        <div>Pagination Here</div>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</x-app-layout>
