    <div>
        @foreach ($followers as $follower)
            <a href="/profile/{{ $follower->userDoingtheFollowing->username }}"
                class="list-group-item list-group-item-action">
                <img class="avatar-tiny" src="{{ $follower->userDoingtheFollowing->avatar }}" />
                <strong>{{ $follower->userDoingtheFollowing->username }}</strong>
            </a>
        @endforeach

    </div>
