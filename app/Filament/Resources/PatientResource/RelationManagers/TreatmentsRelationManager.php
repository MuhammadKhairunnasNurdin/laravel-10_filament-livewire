<?php

namespace App\Filament\Resources\PatientResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TreatmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'treatments';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255)
                    /**
                     * By default, text fields only span half the width of
                     * the form. Since the description field might contain
                     * a lot of information, add a columnSpan('full')
                     * method to make the field span the entire width of
                     * the modal form:
                     */
                    ->columnSpan('full'),
                Forms\Components\Textarea::make('notes')
                    ->maxLength(65535)
                    ->columnSpan('full'),
                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->prefix('$')
                    /**
                     * We can use a text input with some customizations to
                     * make it suitable for currency input. It should be
                     * numeric(), which adds validation and changes the
                     * keyboard layout on mobile devices. Add your
                     * preferred currency prefix using the prefix()
                     * method; for example, prefix('$') will add a $
                     * before the input without impacting the saved output
                     * value:
                     */
                    ->maxValue(42949672.95)
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->columns([
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('price')
                    ->money('DOL')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
