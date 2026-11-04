<?php

namespace App\Filament\Admin\Resources\Designs;

use App\Filament\Admin\Resources\Designs\Pages\CreateDesign;
use App\Filament\Admin\Resources\Designs\Pages\EditDesign;
use App\Filament\Admin\Resources\Designs\Pages\ListDesigns;
use App\Helpers\FileHelper;
use App\Models\Ecommerce\Design;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DesignResource extends Resource
{
    protected static ?string $model = Design::class;

    /**
     * The icon of the resource.
     *
     * @var string
     */
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-paint-brush';

    /**
     * The label of a singular resource.
     */
    protected static ?string $label = 'Design';

    /**
     * The label of a plural resource.
     */
    protected static ?string $pluralLabel = 'Design';

    /**
     * The group in which the resource's navigation item will be displayed.
     *
     * @var string
     */
    protected static string|\UnitEnum|null $navigationGroup = 'Prodotti';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->numeric()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('seller.name')
                    ->label('Shop')
                    ->searchable()
                    ->sortable(),

            ])

            // The default sort column and direction
            ->defaultSort('id', 'desc')

            ->filters([
                //
            ])
            ->recordActions([
                Action::make('download')
                    ->label('Design')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(
                        fn (Design $record): BinaryFileResponse => FileHelper::download('designs_originals/'.$record->id.'.png')
                    ),
                Action::make('download-dark')
                    ->label('Scuro')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('info')
                    ->visible(fn (Design $record) => FileHelper::exists('designs_originals/'.$record->id.'_dark.png'))
                    ->action(
                        fn (Design $record): BinaryFileResponse => FileHelper::download('designs_originals/'.$record->id.'_dark.png')
                    ),
                Action::make('download-vector')
                    ->label('Vettoriale')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('warning')
                    ->visible(fn (Design $record) => FileHelper::exists('designs_vector/'.$record->id.'.pdf') ||
                        FileHelper::exists('designs_vector/'.$record->id.'.ai'))
                    ->action(
                        function (Design $record): BinaryFileResponse {
                            $filePath = FileHelper::exists('designs_vector/'.$record->id.'.ai') ?
                                'designs_vector/'.$record->id.'.ai' :
                                'designs_vector/'.$record->id.'.pdf';

                            return FileHelper::download($filePath);
                        }
                    ),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDesigns::route('/'),
            'create' => CreateDesign::route('/create'),
            'edit' => EditDesign::route('/{record}/edit'),
        ];
    }
}
