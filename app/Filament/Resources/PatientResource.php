<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientResource\Pages;
use App\Filament\Resources\PatientResource\RelationManagers;
use App\Models\Patient;
use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        /**
         * If you open the PatientResource.php file, there's a form()
         * method with an empty schema([...]) array. Adding form fields to
         * this schema will build a form that can be used to create and
         * edit new patients.
         */
        return $form
            ->schema([
                /**
                 * TextInput for inputted text in form
                 */
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                /**
                 * TextInput for selection input in form
                 */
                Forms\Components\Select::make('type')
                    /**
                     * The options() method of the Select field accepts an
                     * array of options for the user to choose from. The
                     * array keys should match the database, and the
                     * values are used as the form labels. Feel free to
                     * add as many animals to this array as you wish.
                     */
                    ->options([
                        'cat' => 'Cat',
                        'dog' =>  'Dog',
                        'rabbit' => 'Rabbit',
                    ])
                    /**
                     * Since this field is also required in the database,
                     * let's add the required() validation rule:
                     */
                    ->required(),
                /**
                 * Let's add a date picker field for the date_of_birth
                 * column along with the validation (the date of birth is
                 * required and the date should be no later than the
                 * current day).
                 */
                Forms\Components\DatePicker::make('date_of_birth')
                    ->required()
                    ->maxDate(now()),
                /**
                 * We should also add an owner when creating a new
                 * patient. Since we added a BelongsTo relationship in
                 * the Patient model (associating it to the related Owner
                 * model), we can use the relationship() method from the
                 * select field to load a list of owners to choose from:
                 * Currently, there are no owners in our database.
                 * Instead of creating a separate Filament owner
                 * resource, let's give users an easier way to add owners
                 * via a modal form (accessible as a + button next to the
                 * select). Use the createOptionForm() method to embed a
                 * modal form with TextInput fields for the owner's name,
                 * email address, and phone number:
                 */
                Forms\Components\Select::make('owner_id')
                    /**
                     * The first argument of the relationship() method is
                     * the name of the function that defines the
                     * relationship in the model (used by Filament to
                     * load the select options) — in this case, owner.
                     * The second argument is the column name to use from
                     * the related table — in this case, name.
                     */
                    ->relationship('owner', 'name')
                    ->required()
                    /**
                     * Let's also make the owner field required,
                     * searchable(), and preload() the first 50 owners
                     * into the searchable list (in case the list is
                     * long):
                     */
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            /**
                             * label() overrides the auto-generated label for
                             * each field. In this case, we want the Email label
                             * to be Email address, and the Phone label to be
                             * Phone number.
                             */
                            ->label('Email address')
                            /**
                             * email() ensures that only valid email
                             * addresses can be input into the field. It
                             * also changes the keyboard layout on mobile
                             * devices.
                             */
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label('Phone Number')
                            /**tel() ensures that only valid phone
                             * numbers can be input into the field. It
                             * also changes the keyboard layout on mobile
                             * devices.
                             */
                            ->tel()
                            ->required()
                    ])
                    ->required(),
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            /**
             * without filled column() function with array data, in index
             * we can't see data, just empty with edit button
             *
             * remember, like other param make() function, u must fill
             * with string that match column name in database
             */
            ->columns([
                /**
                 * The ability to search for patients directly in the
                 * table would be helpful as a veterinary practice grows.
                 * You can make columns searchable by chaining the
                 * searchable() method to the column. Let's make the
                 * patient's name and owner's name searchable.
                 *
                 * Reload the page and observe a new search input field
                 * on the table that filters the table entries using the
                 * search criteria.
                 */
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type'),
                /**
                 * To make the patients table sortable by age, add the
                 * sortable() method to the date_of_birth column:
                 *
                 * This will add a sort icon button to the column header.
                 * Clicking it will sort the table by date of birth.
                 */
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->sortable(),
                Tables\Columns\TextColumn::make('owner.name')
                    ->searchable(),
            ])
            /**
             * Although you can make the type field searchable, making it
             * filterable is a much better user experience.
             *
             * Filament tables can have filters, which are components
             * that reduce the number of records in a table by adding a
             * scope to the Eloquent query. Filters can even contain
             * custom form components, making them a potent tool for
             * building interfaces.
             *
             * Reload the page, and you should see a new filter icon in
             * the top right corner (next to the search form). The filter
             * opens a select menu with a list of patient types. Try
             * filtering your patients by type.
             */
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'cat' => 'Cat',
                        'dog' => 'Dog',
                        'rabbit' => 'Rabbit',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\TreatmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPatients::route('/'),
            'create' => Pages\CreatePatient::route('/create'),
            'edit' => Pages\EditPatient::route('/{record}/edit'),
        ];
    }
}
