<a href="{{ route($profileLink['url']) }}">
    <li
        class="px-4 py-4 md:py-6 transition-colors {{ $dashboardLocation == $profileLink['location'] ? 'bg-primary-400 text-neutral-100' : '' }}">
        <p class="flex gap-4 items-center font-heading">
            <span class="w-5 aspect-square">
                {{ import_svg('icons/' . $profileLink['icon']) }}
            </span>

            {{ $profileLink['title'] }}
        </p>
    </li>
</a>
