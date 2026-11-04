<?php

namespace App\Filament\Admin\Resources\CustomerPhotos\Schemas;

use App\Models\Ecommerce\Design\CustomerPhoto;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CustomerPhotoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('first_name')
                    ->label('Nome')
                    ->maxLength(255),
                TextInput::make('last_name')
                    ->label('Cognome')
                    ->maxLength(255),
                Select::make('design_id')
                    ->label('Design')
                    ->relationship('design', 'name')
                    ->searchable()
                    ->required(),
                Select::make('type_id')
                    ->label('Categoria')
                    ->preload()
                    ->relationship('type', 'name')
                    ->searchable(),
                FileUpload::make('image')
                    ->label('Immagine')
                    ->image()
                    ->imageEditor()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->directory('users/testimonials')
                    ->imageResizeTargetWidth('1440')
                    ->moveFiles()
                    ->disk('public')
                    ->columnSpanFull()
                    ->getUploadedFileNameForStorageUsing(
                        fn (?CustomerPhoto $record): string => $record ? $record->id.'.webp' : 'temp.webp',
                    )
                    ->required(fn (?CustomerPhoto $record) => ! $record),
            ]);
    }
}
