<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Split;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource {

	protected static ?string $model = User::class;

	protected static ?string $navigationIcon = 'icon-users';

	protected static ?string $label = 'Utente';

	protected static ?string $pluralLabel = 'Utenti';

	protected static ?string $navigationGroup = 'Gestione Utenti';

	public static function form(Form $form): Form {
		return $form
			->schema([
				Split::make([
					Grid::make()
						->schema([
							Fieldset::make('Dati personali')
								->schema([
									Forms\Components\TextInput::make('first_name')
										->label('Nome')
										->placeholder('Nome')
										->maxLength(255),
									Forms\Components\TextInput::make('last_name')
										->label('Cognome')
										->placeholder('Cognome')
										->maxLength(255),
									Forms\Components\TextInput::make('email')
										->label('E-mail')
										->placeholder('E-mail')
										->email()
										->required()
										->maxLength(255)
										->columnSpanFull(),
								])->grow(true),

							Fieldset::make('Password')
								->schema([
									Forms\Components\TextInput::make('password')
										->label('Password')
										->placeholder('Password')
										->password()
										->required(fn (Page $livewire): bool => $livewire instanceof CreateRecord)
										->same('password_confirmation')
										->minLength(8)
										->maxLength(255)
										->dehydrateStateUsing(fn ($state) => Hash::make($state)),
									Forms\Components\TextInput::make('password_confirmation')
										->label('Conferma password')
										->password()
										->placeholder('Conferma password')
										->required(fn (Page $livewire): bool => $livewire instanceof CreateRecord)
										->minLength(8),
								])->grow(true),
						]),
					Fieldset::make('Impostazioni')
						->schema([
							Forms\Components\Checkbox::make('admin')
								->label('È amministratore'),
						])
						->grow(false),
				])->from('lg')
			])->columns(1);
	}

	public static function table(Table $table): Table {
		return $table
			->columns([
				Tables\Columns\TextColumn::make('id')
					->numeric()
					->searchable()
					->label('ID')
					->sortable(),
				Tables\Columns\TextColumn::make('first_name')
					->placeholder('-')
					->label('Nome')
					->searchable()
					->toggleable()
					->sortable(),
				Tables\Columns\TextColumn::make('last_name')
					->placeholder('-')
					->label('Cognome')
					->searchable()
					->toggleable()
					->sortable(),
				Tables\Columns\TextColumn::make('seller.name')
					->placeholder('-')
					->label('Negozio')
					->searchable()
					->sortable(),
				Tables\Columns\TextColumn::make('email')
					->label('E-mail')
					->searchable()
					->sortable(),
				Tables\Columns\IconColumn::make('admin')
					->boolean()
					->label('Amministratore')
					->sortable()
					->hidden(fn () => !auth()->user()->isSupervisor()),
				Tables\Columns\TextColumn::make('created_at')
					->dateTime('d-m-Y')
					->label('Creato il')
					->toggleable()
					->toggledHiddenByDefault()
					->sortable(),
				Tables\Columns\TextColumn::make('updated_at')
					->dateTime('d-m-Y')
					->label('Aggiornato il')
					->toggleable()
					->toggledHiddenByDefault()
					->sortable(),

			])->defaultSort('id', 'desc')
			->filters([
				//
			])
			->actions([
				Tables\Actions\Action::make('copyToSelected')
					->icon('icon-login')
					->color('success')
					->action(function (User $record) {
						auth()->login($record);

						return to_route('user-dashboard');
					}),
				Tables\Actions\EditAction::make()->icon('icon-edit'),
				Tables\Actions\DeleteAction::make()->icon('icon-delete'),
			])
			->bulkActions([]);
	}

	public static function getRelations(): array {
		return [
			//
		];
	}

	public static function getPages(): array {
		return [
			'index' => Pages\ListUsers::route('/'),
			'create' => Pages\CreateUser::route('/create'),
			'edit' => Pages\EditUser::route('/{record}/edit'),
		];
	}
}
