<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\DiscontResource\Pages;
use App\Filament\Admin\Resources\DiscontResource\RelationManagers;
use App\Models\Discount;
use App\Models\States\Status\Active;
use App\Models\States\Status\Inactive;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Collection;

class DiscountResource extends Resource
{
    protected static ?string $model = Discount::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationGroup = 'Shop';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required(),
                Forms\Components\TextInput::make('percentage')
                    ->label('Percentage')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->searchable()
                    ->preload()
                    ->placeholder('Select a status')
                    ->options([
                        Active::$name => Active::$name,
                        Inactive::$name => Inactive::$name
                    ])
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('percentage')
                    ->label('Percentage')
                    ->searchable()
                ->suffix('%'),

                Tables\Columns\IconColumn::make('status')
                    ->label('Status')
                    ->icon(fn (string $state): string => match ($state) {
                        'active' => 'heroicon-o-check-circle',
                        'inactive' => 'heroicon-o-x-circle',
                        'scheduled' => 'heroicon-o-clock',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->color( fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        'scheduled' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->groupedBulkActions([
                Tables\Actions\BulkAction::make('Active')
                    ->requiresConfirmation()
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(fn (Collection $records) => $records->each->update(['status' => Active::$name])),
                Tables\Actions\BulkAction::make('Inactive')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->action(fn (Collection $records) => $records->each->update(['status' => Inactive::$name])),
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
            'index' => Pages\ListDiscounts::route('/'),
//            'create' => Pages\CreateDiscont::route('/create'),
//            'edit' => Pages\EditDiscont::route('/{record}/edit'),
        ];
    }
}
