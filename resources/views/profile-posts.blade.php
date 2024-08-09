<x-profile :sharedData="$sharedData" pageTitle="{{ $sharedData['username'] }}'s Profile">
    @include('profile-posts-only')
</x-profile>
