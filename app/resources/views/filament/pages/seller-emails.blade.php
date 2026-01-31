@php

	use App\Models\Ecommerce\Seller;

	$ignoredEmails = [
	    'andrea_trentini94@ya.it',
	    'valeriosiciliano@email.it',
	    'temp@gmail.com',
	    'inseriscilatuaemail@gmail.it',
	    'noncancellareisma@hotmail.it',
	    'oscarufficiale@mail.com',
	    'weareawesome@panicpanik.com',
	    'yourmail@gmail.com',
	    'ssss@gmail.com',
	    'quellidelcalciomercato@aol.com',
	    'ciriii@hotmail.kt',
	    'mettile@ditanelnaso.com',
	    'giorgiamiranda@yahoo.it',
	    'danieletomoiaga@yahoo.it',
	    'greco4581@outlook.it',
	    'attilioguerra@gmail.com',
	    'giorgiodefrancesco71.1@gmail.com',
	    'fugo.pannacotta@tiscali.it',
	    'admin@mondoduster.it',
	    'aaa@aaa.com',
	    'ernestotommasocampagnuolo2002@gmail.com',
	    'tizianoconta@tiscali.it',
	    'giorgiodefrancesco71.1@gmail.com',
	    'admin@mondoduster.it',
	    'fiel89@hotmail.it',
	    'squillacegiovanni98@gmail.com',
	    'sanseverotrash@gmx.com',
	];

	$sellers = Seller::join('users', 'users.id', '=', 'sellers.user_id')->where('sellers.status', true)->get();

	$emails = $sellers->pluck('email')->toArray();
	$emails = array_chunk($emails, 50);

@endphp

@pushOnce('styles')
	<style>
		.email-group svg {
			fill: currentColor;
			transition: color 0.2s;

			&:hover {
				color: rgba(var(--primary-400), var(--tw-text-opacity, 1));
			}
		}
	</style>
@endpushOnce

@pushOnce('scripts')
	<script defer>
		document.addEventListener('DOMContentLoaded', function() {
			const copyButtons = document.querySelectorAll('.js-copy-email-list');

			copyButtons.forEach(button => {
				button.addEventListener('click', () => {
					const block = button.closest('.js-block');
					const emails = Array.from(block.querySelectorAll('.js-email'))
						.map(email => email.textContent.trim())
						.join('\n');

					navigator.clipboard.writeText(emails).then(() => {}).catch(err => {
						console.error('Failed to copy emails: ', err);
					});
				});
			});
		});
	</script>
@endpushOnce

<x-filament-panels::page>

	@foreach ($emails as $key => $chunk)
		<div class="email-group js-block rounded-lg p-4 dark:bg-gray-900">
			<div class="mb-4 flex flex-wrap items-center justify-between">
				<h3 class="text-lg font-bold">Gruppo {{ $key + 1 }}</h3>
				<x-icon-copy class="js-copy-email-list w-4 cursor-pointer" />
			</div>

			<ul>
				@foreach ($chunk as $email)
					@if (!in_array($email, $ignoredEmails))
						<li class="email js-email text-gray-400">{{ $email }}</li>
					@endif
				@endforeach
			</ul>
		</div>
	@endforeach

</x-filament-panels::page>
