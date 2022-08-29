<?php

namespace App\Http\Livewire;

use NumberFormatter;
use App\Models\Expense;
use Illuminate\Support\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\Rules\Rule;
use PowerComponents\LivewirePowerGrid\PowerGridEloquent;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;

final class ExpenseTable extends PowerGridComponent
{
    use ActionButton;

    public $vehicle_id = null;


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
     * @return  \Illuminate\Database\Eloquent\Builder<\App\Models\Expense>|null
     */
    public function datasource(): ?Builder
    {
        // Check if vehicle_id is set.

        if ($this->vehicle_id != null) {
            return Expense::query()
                ->where('vehicle_id', $this->vehicle_id)
                ->join('vehicles', 'vehicles.id', '=', 'expenses.vehicle_id')
                ->select([
                    'expenses.*',
                    'vehicles.registration_number',
                ]);
        }
        return Expense::query()
            ->join('vehicles', 'vehicles.id', '=', 'expenses.vehicle_id')
            ->select([
                'expenses.*',
                'vehicles.registration_number',
            ]);
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
        return [
            'vehicle' => ['registration_number'],
        ];
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
        $fmt = new NumberFormatter('pt_PT', NumberFormatter::CURRENCY);

        return PowerGrid::eloquent()
            ->addColumn('id')
            ->addColumn('registration_number')
            ->addColumn('vehicle_link', function ($row) {
                return '<a href="' . route('admin.vehicles.show', $row->vehicle_id) . '" class="text-indigo-600 hover:text-indigo-900 hover:underline">' . $row->registration_number . '</a>';
            })
            ->addColumn('amount')
            ->addColumn('amount_formated', function (Expense $model) use ($fmt) {
                return $fmt->formatCurrency($model->amount, "INR");
            })
            ->addColumn('remark')
            ->addColumn('created_at')
            ->addColumn('created_at_formatted', function (Expense $model) {
                return Carbon::parse($model->created_at)->format('d/m/Y H:i:s');
            });
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
                ->title('Vehicle')
                ->field('vehicle_link')
                ->sortable()
                ->visibleInExport(False),
            
            Column::add()
                ->title('Vehicle')
                ->field('registration_number')
                ->hidden()
                ->visibleInExport(True),

            Column::add()
                ->title('Amount')
                ->field('amount_formated')
                ->sortable()
                ->visibleInExport(False),

            Column::add()
                ->title('Amount')
                ->field('amount')
                ->hidden()
                ->visibleInExport(True),

            Column::add()
                ->title('Remark')
                ->field('remark'),

            Column::add()
                ->title('Added')
                ->field('created_at')
                ->hidden()
                ->visibleInExport(True),

            Column::add()
                ->title('Added')
                ->field('created_at_formatted', 'expenses.created_at')
                ->makeInputDatePicker()
                ->searchable()
                ->visibleInExport(False)
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
     * PowerGrid Expense Action Buttons.
     *
     * @return array<int, \PowerComponents\LivewirePowerGrid\Button>
     */

    public function actions(): array
    {
        return [
            Button::add('edit')
                ->caption('Edit')
                ->class('text-indigo-600 hover:text-indigo-900 hover:underline')
                ->route('admin.expenses.edit', ['expense' => 'id'])
                ->target('_self'),

            /*
           Button::add('destroy')
               ->caption('Delete')
               ->class('bg-red-500 cursor-pointer text-white px-3 py-2 m-1 rounded text-sm')
               ->route('expense.destroy', ['expense' => 'id'])
               ->method('delete')
               */
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
     * PowerGrid Expense Action Rules.
     *
     * @return array<int, \PowerComponents\LivewirePowerGrid\Rules\RuleActions>
     */

    /*
    public function actionRules(): array
    {
       return [
           
           //Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($expense) => $expense->id === 1)
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
     * PowerGrid Expense Update.
     *
     * @param array<string,string> $data
     */

    /*
    public function update(array $data ): bool
    {
       try {
           $updated = Expense::query()
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
