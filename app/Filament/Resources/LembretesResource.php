<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LembretesResource\Pages;
use App\Filament\Resources\LembretesResource\RelationManagers;
use App\Models\Lembretes;
use App\Models\Nota;
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

class LembretesResource extends Resource
{
    protected static ?string $model = Lembretes::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        $destinatarios = User::all()->pluck('name', 'id');

        if (Auth::user()->tipo === 'T' ) {
            $relacao = TitularesSecundarios::select('id_secundario')
                ->where('id_titular', Auth::user()->id)
                ->get()
                ->pluck('id_secundario')
                ->toArray();

            $relacao[] = Auth::user()->id;

            $destinatarios = User::select('name', 'id')
                ->whereIn('usuarios.id', $relacao)
                ->orderBy('usuarios.id', 'desc')
                ->get()
                ->pluck('name', 'id');
        }

        return $form
            ->schema([
                Forms\Components\TextInput::make('nome')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('descricao')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('dia_semana')
                    ->options([
                        'DOMINGO' => 'Domingo',
                        'SEGUNDA' => 'Segunda-feira',
                        'TERCA'   => 'Terça-feira',
                        'QUARTA'  => 'Quarta-feira',
                        'QUINTA'  => 'Quinta-feira',
                        'SEXTA'   => 'Sexta-feira',
                        'SABADO'  => 'Sábado',
                    ])
                    ->required(),
                Forms\Components\TimePicker::make('hora')
                    ->required(),
                Forms\Components\Select::make('destinatarios')
                    ->relationship('destinatarios', 'name')
                    ->options($destinatarios)
                    ->multiple()
                    ->label('Destinatários')
                    ->required()
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = Lembretes::select('lembretes.*')
            ->join('lembrete_usuario', 'lembrete_usuario.id_lembrete', 'lembretes.id')
            ->where('lembrete_usuario.id_destinatario', Auth::user()->id)
            ->orderBy('lembretes.id', 'desc');

        return $query;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')->label('Nome'),
                Tables\Columns\TextColumn::make('dia_semana'),
                Tables\Columns\TextColumn::make('hora')->dateTime('H:i')

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
            'index' => Pages\ListLembretes::route('/'),
            'create' => Pages\CreateLembretes::route('/create'),
            'edit' => Pages\EditLembretes::route('/{record}/edit'),
        ];
    }
}
