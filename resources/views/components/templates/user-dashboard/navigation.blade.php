@php
    //navigation menu
    $profileLinks = [['title' => 'Account', 'url' => 'user-dashboard', 'location' => 'recap', 'icon' => 'user'], ['title' => 'I miei corsi', 'url' => 'user-dashboard.courses', 'location' => 'courses', 'icon' => 'courses'], ['title' => 'I miei dati', 'url' => 'user-dashboard.personal-data', 'location' => 'data', 'icon' => 'settings'], ['title' => 'Logout', 'url' => 'logout', 'location' => 'logout', 'icon' => 'logout']];

@endphp

@push('styles')
    <style>
        .user-dashboard__navigation {
            position: sticky;
            top: 2rem;
        }

        .user-dashboard__navigation ul {
            overflow: hidden;
        }

        @media (max-width: 768px) {
            .user-dashboard__navigation {
                grid-template-rows: max-content 0fr;
                transition: grid-template-rows 0.3s;

                &[data-open="true"] {
                    grid-template-rows: max-content 1fr;
                }
            }
        }
    </style>
@endpush

<nav class="user-dashboard__navigation grid bg-surface-400 rounded-2xl overflow-clip" x-data="{ open: false }"
    :data-open="open ? 'true' : 'false'">
    <!-- Title -->
    <label class="md:hidden" @click="open = !open">
        <h3 class="flex items-center gap-4 px-4 py-4 text-xl">
            <span class="w-5">
                {{ import_svg('icons/list') }}
            </span>
            Profilo
        </h3>
    </label>

    <!-- Navigation menu -->
    <ul class="">

        @foreach ($profileLinks as $profileLink)
            <x-templates.user-dashboard.link :profileLink="$profileLink" :dashboardLocation="$dashboardLocationTag" />
        @endforeach

    </ul>
</nav>
