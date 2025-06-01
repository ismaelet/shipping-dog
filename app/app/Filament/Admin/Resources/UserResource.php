<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages\CreateUser;
use App\Filament\Admin\Resources\UserResource\Pages\EditUser;
use App\Filament\Admin\Resources\UserResource\Pages\ListUsers;
use App\Models\User;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource {

	/**
	 * The model the resource corresponds to.
	 * 
	 * @var string
	 */
	protected static ?string $model = User::class;

	/**
	 * The icon of the resource.
	 * 
	 * @var string
	 */
	protected static ?string $navigationIcon = 'icon-users';

	/**
	 * The label of a singular resource.
	 * 
	 * @var string
	 */
	protected static ?string $label = 'Utente';

	/**
	 * The label of a plural resource.
	 * 
	 * @var string
	 */
	protected static ?string $pluralLabel = 'Utenti';

	/**
	 * The group in which the resource's navigation item will be displayed.
	 * 
	 * @var string
	 */
	protected static ?string $navigationGroup = 'Utenti e Negozi';

	/**
	 * The form for creating or updating records.
	 * 
	 * @param \Filament\Forms\Form $form
	 * @return \Filament\Forms\Form
	 */
	public static function form(Form $form): Form {
		return $form
			->schema([

				// Split the form into two columns
				Split::make([

					// First column
					Grid::make()
						->schema([

							// Personal data
							Fieldset::make('Dati personali')
								->schema([
									TextInput::make('first_name')
										->label('Nome')
										->placeholder('Nome')
										->maxLength(255),
									TextInput::make('last_name')
										->label('Cognome')
										->placeholder('Cognome')
										->maxLength(255),
									TextInput::make('email')
										->label('E-mail')
										->placeholder('E-mail')
										->email()
										->required()
										->maxLength(255)
										->columnSpanFull(),
								])->grow(true),

							// Password management
							Fieldset::make('Password')
								->schema([
									TextInput::make('password')
										->label('Password')
										->placeholder('Password')
										->password()
										->required(fn(Page $livewire): bool => $livewire instanceof CreateRecord)
										->same('password_confirmation')
										->minLength(8)
										->maxLength(255)
										->dehydrateStateUsing(fn($state) => Hash::make($state)),
									TextInput::make('password_confirmation')
										->label('Conferma password')
										->password()
										->placeholder('Conferma password')
										->required(fn(Page $livewire): bool => $livewire instanceof CreateRecord)
										->minLength(8),
								])->grow(true),
						]),

					// Second column
					Fieldset::make('Impostazioni')
						->schema([
							Checkbox::make('admin_level')
								->label('È amministratore'),
						])
						->grow(false),
				])->from('lg')
			])->columns(1);
	}

	/**
	 * The table for the resource.
	 * 
	 * @param \Filament\Tables\Table $table
	 * @return \Filament\Tables\Table
	 */
	public static function table(Table $table): Table {
		return $table
			// The columns that should be displayed in the table
			->columns([

				// ID
				TextColumn::make('id')
					->searchable()
					->label('ID')
					->numeric()
					->sortable(),

				// First name
				TextColumn::make('first_name')
					->placeholder('-')
					->label('Nome')
					->searchable()
					->toggleable()
					->sortable(),

				// Last name
				TextColumn::make('last_name')
					->placeholder('-')
					->label('Cognome')
					->searchable()
					->toggleable()
					->sortable(),

				// E-mail
				TextColumn::make('email')
					->label('E-mail')
					->searchable()
					->sortable(),

				// Admin
				IconColumn::make('admin_level')
					->boolean()
					->label('Amministratore')
					->sortable(),

				// Created at
				TextColumn::make('created_at')
					->dateTime('d-m-Y')
					->label('Creato il')
					->toggleable()
					->toggledHiddenByDefault()
					->sortable(),

				// Updated at
				TextColumn::make('updated_at')
					->dateTime('d-m-Y')
					->label('Aggiornato il')
					->toggleable()
					->toggledHiddenByDefault()
					->sortable(),

			])

			// The default sort column and direction
			->defaultSort('id', 'desc')

			// The actions that can be performed on the records
			->actions([
				ViewAction::make()->icon('icon-visibility'),
				EditAction::make()->icon('icon-edit'),
				DeleteAction::make()->icon('icon-delete'),
				Action::make('login')
					->icon('icon-login')
					->color('info')
					->action(function (User $record) {
						auth()->login($record);

						return to_route('user-dashboard');
					}),
			]);
	}

	/**
	 * Get the pages available to the resource.
	 * 
	 * @return array
	 */
	public static function getPages(): array {
		return [
			'index' => ListUsers::route('/'),
			'create' => CreateUser::route('/create'),
			'edit' => EditUser::route('/{record}/edit'),
		];
	}
}
