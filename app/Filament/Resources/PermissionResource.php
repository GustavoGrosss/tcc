<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionResource\Pages;
use App\Models\Permission;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static ?string $modelLabel = 'Permissões';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Nome')
                    ->maxLength(255),
                Forms\Components\Select::make('roles')
                    ->label('Função')
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->preload()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i:s')
                    ->label('Data Criação'),
            ])
            ->filters([])
            ->actions([
                //

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePermissions::route('/'),
        ];
    }
}
