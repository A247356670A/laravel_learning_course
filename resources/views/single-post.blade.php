<x-layout :pageTitle="$page->title">
    <div class="container py-md-5 container--narrow">
        <div class="d-flex justify-content-between">
            <h2>{{ $page->title }}</h2>
            @can('update', $page)
                <span class="pt-2">
                    <a href="/post/{{ $page->id }}/edit" class="text-primary mr-2" data-toggle="tooltip"
                        data-placement="top" title="Edit"><i class="fas fa-edit"></i></a>
                    <form class="delete-post-form d-inline" action="/post/{{ $page->id }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="delete-post-button text-danger" data-toggle="tooltip" data-placement="top"
                            title="Delete"><i class="fas fa-trash"></i></button>
                    </form>
                </span>
            @endcan
        </div>

        <p class="text-muted small mb-4">
            <a href="/profile/{{ $page->user->username }}"><img class="avatar-tiny"
                    src="{{ $page->user->avatar }}" /></a>
            Posted by <a href="/profile/{{ $page->user->username }}">{{ $page->user->username }}</a> on
            {{ $page->created_at->format('n/m/y') }}
        </p>

        <div class="body-content">
            {!! $page->body !!}
        </div>
    </div>

</x-layout>
