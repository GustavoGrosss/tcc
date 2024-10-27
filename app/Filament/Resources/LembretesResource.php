<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LembretesResource\Pages;
use App\Models\DiaSemana;
use App\Models\Lembretes;
use App\Models\TitularesSecundarios;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class LembretesResource extends Resource
{
    protected static ?string $model = Lembretes::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell';

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
                ->whereIn('id', $relacao)
                ->orderBy('id', 'desc')
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
                    ->relationship('dia_semana', 'nome')
                    ->options(DiaSemana::all()->pluck('nome', 'id'))
                    ->multiple()
                    ->preload()
                    ->label('Dia da semana')
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
        $query = Lembretes::select('lembretes.*', 'dia_semana.nome as dia_semana')
            ->join('lembrete_dia_semana', 'lembrete_dia_semana.id_lembrete', 'lembretes.id')
            ->join('dia_semana', 'dia_semana.id', 'lembrete_dia_semana.id_dia_semana')
            ->orderBy('lembretes.id', 'desc');

        if (Auth::user()->tipo === 'T' ) {
            $relacao = TitularesSecundarios::select('id_secundario')
                ->where('id_titular', Auth::user()->id)
                ->get()
                ->pluck('id_secundario')
                ->toArray();

            $relacao[] = Auth::user()->id;

            $query = Lembretes::select('lembretes.*', 'dia_semana.nome as dia_semana')
                ->join('lembrete_usuario', 'lembrete_usuario.id_lembrete', 'lembretes.id')
                ->join('lembrete_dia_semana', 'lembrete_dia_semana.id_lembrete', 'lembretes.id')
                ->join('dia_semana', 'dia_semana.id', 'lembrete_dia_semana.id_dia_semana')
                ->whereIn('lembrete_usuario.id_destinatario', $relacao)
                ->groupBy('lembretes.id', 'dia_semana.nome')
                ->orderBy('lembretes.id', 'desc');
        }

        if (Auth::user()->tipo == 'S') {
            $query = Lembretes::select('lembretes.*', 'dia_semana.nome as dia_semana')
                ->join('lembrete_usuario', 'lembrete_usuario.id_lembrete', 'lembretes.id')
                ->join('lembrete_dia_semana', 'lembrete_dia_semana.id_lembrete', 'lembretes.id')
                ->join('dia_semana', 'dia_semana.id', 'lembrete_dia_semana.id_dia_semana')
                ->where('lembrete_usuario.id_destinatario', Auth::user()->id)
                ->groupBy('lembretes.id', 'dia_semana.nome')
                ->orderBy('lembretes.id', 'desc');
        }

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
