<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Lembretes;
use App\Models\Role;
use App\Models\TitularesSecundarios;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $modelLabel = 'Usuários';

    public static function form(Form $form): Form
    {
        $tipos = [
            'A' => 'Admin',
            'T' => 'Titular',
        ];

        $role = [
            'Admin',
            'Titular',
        ];

        if (Auth::user()->tipo === 'T' ) {
            $tipos = [
                'T' => 'Titular',
                'S' => 'Secundario'
            ];

            $role = [
                'Titular',
                'Secundario'
            ];
        }

        if (Auth::user()->tipo === 'S' ) {
            $tipos = [
                'S' => 'Secundario'
            ];

            $role = [
                'Secundario'
            ];
        }

        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Nome'),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->label('E-mail'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create')
                    ->label('Senha'),
                Forms\Components\TextInput::make('documento')
                    ->required()
                    ->label('Documento'),
                Forms\Components\DatePicker::make('data_nascimento')
                    ->required(),
                Forms\Components\Select::make('tipo')
                    ->options($tipos)
                    ->required(),
                Forms\Components\Select::make('roles')
                    ->label('Função')
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->options(Role::all()->whereIn('name', $role)->pluck('name', 'id'))
                    ->preload()
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = User::select('usuarios.*')
            ->orderBy('usuarios.id', 'desc');

        if (Auth::user()->tipo == 'T') {
            $relacao = TitularesSecundarios::select('id_secundario')
                ->where('id_titular', Auth::user()->id)
                ->get()
                ->pluck('id_secundario')
                ->toArray();

            $relacao[] = Auth::user()->id;

            $query = User::select('usuarios.*')
                ->whereIn('usuarios.id', $relacao)
                ->orderBy('usuarios.id', 'desc');
        }

        if (Auth::user()->tipo == 'S') {
            $titular = TitularesSecundarios::select('id_titular')
                ->where('id_secundario', Auth::user()->id)
                ->first();

            $relacao = TitularesSecundarios::select('id_secundario')
                ->where('id_titular', $titular->id_titular)
                ->get()
                ->pluck('id_secundario')
                ->toArray();

            $relacao[] = $titular->id_titular;

            $query = User::select('usuarios.*')
                ->whereIn('usuarios.id', $relacao)
                ->orderBy('usuarios.id', 'desc');
        }

        return $query;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nome'),
                Tables\Columns\TextColumn::make('documento'),
                Tables\Columns\TextColumn::make('data_nascimento')->dateTime('d/m/y')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
