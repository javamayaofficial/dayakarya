<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkResource\Pages;
use App\Models\Work;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * Moderasi & manajemen karya (approve/tolak, tandai unggulan).
 */
class WorkResource extends Resource
{
    protected static ?string $model = Work::class;
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Konten';
    protected static ?string $modelLabel = 'Karya';
    protected static ?string $pluralModelLabel = 'Karya';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('title')->label('Judul')->required(),
            Select::make('type')->label('Tipe')->options(config('dayakarya.work_types'))->required(),
            Select::make('category_id')->label('Kategori')->relationship('category', 'name'),
            Textarea::make('synopsis')->label('Sinopsis')->rows(4),
            Select::make('status')->label('Status')->options([
                'draft' => 'Draft', 'review' => 'Review',
                'published' => 'Terbit', 'rejected' => 'Ditolak',
            ])->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Judul')->searchable()->limit(40),
                Tables\Columns\TextColumn::make('creator.name')->label('Kreator')->searchable(),
                Tables\Columns\TextColumn::make('type')->label('Tipe')->badge(),
                Tables\Columns\BadgeColumn::make('status')->colors([
                    'gray' => 'draft', 'warning' => 'review',
                    'success' => 'published', 'danger' => 'rejected',
                ]),
                Tables\Columns\TextColumn::make('views')->label('Dibaca')->sortable(),
                Tables\Columns\IconColumn::make('is_featured')->label('Unggulan')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'draft' => 'Draft', 'review' => 'Review',
                    'published' => 'Terbit', 'rejected' => 'Ditolak',
                ]),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorks::route('/'),
            'edit'  => Pages\EditWork::route('/{record}/edit'),
        ];
    }
}
