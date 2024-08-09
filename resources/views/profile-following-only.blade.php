    <div>
        @foreach ($following as $follow)
            <a href="/profile/{{ $follow->userBeingFollowed->username }}" class="list-group-item list-group-item-action">
                <img class="avatar-tiny" src="{{ $follow->userBeingFollowed->avatar }}" />
                <strong>{{ $follow->userBeingFollowed->username }}</strong>
            </a>
        @endforeach
    </div>
