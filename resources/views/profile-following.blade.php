<x-profile :sharedData="$sharedData" pageTitle="{{ $sharedData['username'] }}'s Followings">
    <p>{{ $following }}</p>
    @include('profile-following-only')
</x-profile>
