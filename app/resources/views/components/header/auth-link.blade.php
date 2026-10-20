<a
	href="{{ auth()->check() ? route('user-dashboard') : route('login') }}"
	class="surface leading-12 hover:border-primary-400 hover:bg-primary-400 hidden rounded-full border px-[1.7rem] font-semibold transition-colors xl:block"
>
	{{ auth()->check() ? 'Profilo' : 'Login' }}
</a>
