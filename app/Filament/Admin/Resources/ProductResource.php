<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use AymanAlhattami\FilamentDateScopesFilter\DateScopeFilter;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Shop';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                ->label('Name')
                ->required(),

                Forms\Components\TextInput::make('price')
                ->label('Price')
                ->required(),
                Forms\Components\TextInput::make('stock')
                ->label('Stock')
                ->required(),
                Forms\Components\Select::make('category_id')
                    ->label('Category')
                    ->searchable()
                    ->preload()
                    ->relationship('category', 'name')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->required(),
                    ])
                    ->required(),
                Forms\Components\Textarea::make('description')
                ->label('Description')
                    ->columnSpanFull(),
                Forms\Components\SpatieMediaLibraryFileUpload::make('image')
                ->label('Image')
                    ->collection('product_image')
                ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                ->label('ID')
                ->searchable(),
                Tables\Columns\TextColumn::make('name')
                ->label('Name')
                ->searchable(),
                Tables\Columns\ToggleColumn::make('is_enabled')
                    ->label('Status')
                    ->onIcon('heroicon-s-check')
                    ->onColor('success')
                    ->offColor('danger')
                    ->offIcon('heroicon-s-x-mark'),
                Tables\Columns\TextColumn::make('price')
                ->label('Price')
                ->searchable(),
                Tables\Columns\TextColumn::make('stock')
                ->label('Stock')
                ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                ->label('Category')
                ->searchable(),
                Tables\Columns\TextColumn::make('description')
                ->label('Description')
                ->searchable(),
                SpatieMediaLibraryImageColumn::make('image')
                ->collection('product_image')
                ->circular(),
            ])
            ->filters([
                DateScopeFilter::make('created_at')
                ->label('Created At'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->groupedBulkActions([
                Tables\Actions\BulkAction::make('Enabled')
                    ->requiresConfirmation()
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(fn (Collection $records) => $records->each->update(['is_enabled' => 1])),
                Tables\Actions\BulkAction::make('Disabled')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->action(fn (Collection $records) => $records->each->update(['is_enabled' => 0])),
                Tables\Actions\DeleteBulkAction::make(),
            ])->defaultSort('created_at', 'desc');
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
            'index' => \App\Filament\Admin\Resources\ProductResource\Pages\ListProducts::route('/'),
            'create' => \App\Filament\Admin\Resources\ProductResource\Pages\CreateProduct::route('/create'),
            'edit' => \App\Filament\Admin\Resources\ProductResource\Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
