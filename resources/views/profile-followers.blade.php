<x-profile :sharedData="$sharedData" pageTitle="{{ $sharedData['username'] }}'s Followers">
    <p>{{ $followers }}</p>
    @include('profile-followers-only')
</x-profile>
