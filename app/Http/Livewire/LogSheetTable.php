<?php

namespace App\Http\Livewire;

use App\Models\Location;
use App\Models\LogSheet;
use Illuminate\Support\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridEloquent;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;
use PowerComponents\LivewirePowerGrid\Rules\Rule;

final class LogSheetTable extends PowerGridComponent
{
    use ActionButton;

    public string $sortField = 'id';

    public string $sortDirection = 'asc';

    //Messages informing success/error data is updated.
    public bool $showUpdateMessages = true;

    /*
    |--------------------------------------------------------------------------
    |  Features Setup
    |--------------------------------------------------------------------------
    | Setup Table's general features
    |
    */
    public function setUp(): void
    {
        $this->showCheckBox()
            ->showPerPage()
            ->showSearchInput()
            ->showToggleColumns()
            ->showExportOption('download', ['excel', 'csv']);
    }

    /*
    |--------------------------------------------------------------------------
    |  Datasource
    |--------------------------------------------------------------------------
    | Provides data to your Table using a Model or Collection
    |
    */

    /**
     * PowerGrid datasource.
     *
     * @return  \Illuminate\Database\Eloquent\Builder<\App\Models\LogSheet>|null
     */
    public function datasource(): ?Builder
    {
        $isAdmin = auth()->user()->isAdmin();
        $isManager = auth()->user()->isManager();

        $query = null;

        if ($isAdmin) {
            $query = LogSheet::query()->withCount('invoices')->with('invoices', 'location');
        } else if ($isManager) {
            $manager = auth()->user()->manager;
            $location_ids = $manager->locations->pluck('id')->toArray();
            $query =  LogSheet::whereIn('location_id', $location_ids)->withCount('invoices')->with('invoices', 'location');
        }

        // If not super user, limit data
        $is_super_user = auth()->user()->email === config('services.dropship.super_user');
        if (!$is_super_user) {
            $query->where('date', '>=', Carbon::now()->subDays(config('services.dropship.data_limit'))->toDateString());
        }

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    |  Relationship Search
    |--------------------------------------------------------------------------
    | Configure here relationships to be used by the Search and Table Filters.
    |
    */

    /**
     * Relationship search.
     *
     * @return array<string, array<int, string>>
     */
    public function relationSearch(): array
    {
        return [];
    }

    /*
    |--------------------------------------------------------------------------
    |  Add Column
    |--------------------------------------------------------------------------
    | Make Datasource fields available to be used as columns.
    | You can pass a closure to transform/modify the data.
    |
    */
    public function addColumns(): ?PowerGridEloquent
    {
        return PowerGrid::eloquent()
            ->addColumn('log_sheet_no')
            ->addColumn('date_formatted', function (LogSheet $model) {
                return Carbon::parse($model->date)->format('d/m/Y');
            })
            ->addColumn('location_name', function (LogSheet $model) {
                return $model->location->name;
            })
            ->addColumn('invoices_count');
    }

    /*
    |--------------------------------------------------------------------------
    |  Include Columns
    |--------------------------------------------------------------------------
    | Include the columns added columns, making them visible on the Table.
    | Each column can be configured with properties, filters, actions...
    |
    */

    /**
     * PowerGrid Columns.
     *
     * @return array<int, Column>
     */
    public function columns(): array
    {
        return [

            Column::add()
                ->title('LOG SHEET NO')
                ->field('log_sheet_no')
                ->sortable()
                ->searchable()
                ->makeInputText(),

            Column::add()
                ->title('DATE')
                ->field('date_formatted', 'date')
                ->sortable()
                ->makeInputDatePicker('date'),

            Column::add()
                ->title('LOCATION')
                ->field('location_name')
                ->sortable()
                ->makeInputSelect(Location::all(), 'name', 'location_id'),

            Column::add()
                ->title('Invoices')
                ->field('invoices_count'),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Actions Method
    |--------------------------------------------------------------------------
    | Enable the method below only if the Routes below are defined in your app.
    |
    */

    /**
     * PowerGrid LogSheet Action Buttons.
     *
     * @return array<int, \PowerComponents\LivewirePowerGrid\Button>
     */


    public function actions(): array
    {
        return [
            Button::add('show')
                ->target('')
                ->caption('Show')
                ->class('text-indigo-600 hover:text-indigo-900 hover:underline')
                ->route('admin.log-sheets.show', ['log_sheet' => 'id']),

            //    Button::add('destroy')
            //        ->caption('Delete')
            //        ->class('bg-red-500 cursor-pointer text-white px-3 py-2 m-1 rounded text-sm')
            //        ->route('log-sheet.destroy', ['log-sheet' => 'id'])
            //        ->method('delete')
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Actions Rules
    |--------------------------------------------------------------------------
    | Enable the method below to configure Rules for your Table and Action Buttons.
    |
    */

    /**
     * PowerGrid LogSheet Action Rules.
     *
     * @return array<int, \PowerComponents\LivewirePowerGrid\Rules\RuleActions>
     */

    /*
    public function actionRules(): array
    {
       return [
           
           //Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($log-sheet) => $log-sheet->id === 1)
                ->hide(),
        ];
    }
    */

    /*
    |--------------------------------------------------------------------------
    | Edit Method
    |--------------------------------------------------------------------------
    | Enable the method below to use editOnClick() or toggleable() methods.
    | Data must be validated and treated (see "Update Data" in PowerGrid doc).
    |
    */

    /**
     * PowerGrid LogSheet Update.
     *
     * @param array<string,string> $data
     */

    /*
    public function update(array $data ): bool
    {
       try {
           $updated = LogSheet::query()->findOrFail($data['id'])
                ->update([
                    $data['field'] => $data['value'],
                ]);
       } catch (QueryException $exception) {
           $updated = false;
       }
       return $updated;
    }

    public function updateMessages(string $status = 'error', string $field = '_default_message'): string
    {
        $updateMessages = [
            'success'   => [
                '_default_message' => __('Data has been updated successfully!'),
                //'custom_field'   => __('Custom Field updated successfully!'),
            ],
            'error' => [
                '_default_message' => __('Error updating the data.'),
                //'custom_field'   => __('Error updating custom field.'),
            ]
        ];

        $message = ($updateMessages[$status][$field] ?? $updateMessages[$status]['_default_message']);

        return (is_string($message)) ? $message : 'Error!';
    }
    */
}
