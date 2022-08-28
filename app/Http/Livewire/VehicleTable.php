<?php

namespace App\Http\Livewire;

use NumberFormatter;
use App\Models\Vehicle;
use App\Models\Location;
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

final class VehicleTable extends PowerGridComponent
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
     * @return  \Illuminate\Database\Eloquent\Builder<\App\Models\Vehicle>|null
     */
    public function datasource(): ?Builder
    {
        $isAdmin = auth()->user()->isAdmin();
        $isManager = auth()->user()->isManager();
        if ($isAdmin) {
            return Vehicle::query()
                ->leftjoin('expenses', 'expenses.vehicle_id', '=', 'vehicles.id')
                ->select('vehicles.*', \DB::raw('SUM(expenses.amount_in_cents) as total_expenses'))
                ->groupBy('vehicles.id');
        } else if ($isManager) {
            $manager = auth()->user()->manager;
            $location_ids = $manager->locations->pluck('id')->toArray();
            $vehicle_ids = Location::whereIn('id', $location_ids)->with('vehicles')->get()->pluck('vehicles')->flatten()->pluck('id')->toArray();
            return Vehicle::whereIn('id', $vehicle_ids)
                ->leftjoin('expenses', 'expenses.vehicle_id', '=', 'vehicles.id')
                ->select('vehicles.*', \DB::raw('SUM(expenses.amount_in_cents) as total_expenses'))
                ->groupBy('vehicles.id');
        }
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
        $fmt = new NumberFormatter('pt_PT', NumberFormatter::CURRENCY);

        return PowerGrid::eloquent()
            ->addColumn('registration_number')
            ->addColumn('total_expenses')
            ->addColumn('total_expenses_formated', function ($model) use ($fmt) {
                $total =  intval($model->total_expenses) / 100;
                return $fmt->formatCurrency($total, 'INR');
            })
            ->addColumn('is_active', function ($model) {
                return $model->is_active ? 'Active' : 'Inactive';
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
                ->title('REGISTRATION NUMBER')
                ->field('registration_number')
                ->sortable()
                ->searchable(),

            Column::add()
                ->title('IS ACTIVE')
                ->field('is_active'),

            Column::add()
                ->title('Total Expenses')
                ->field('total_expenses_formated', 'total_expenses')
                ->sortable(),

            Column::add()
                ->title('Total Expenses')
                ->field('total_expenses')
                ->hidden()
                ->visibleInExport(True)

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
     * PowerGrid Vehicle Action Buttons.
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
                ->route('admin.vehicles.show', ['vehicle' => 'id']),

            Button::add('edit')
                ->target('')
                ->caption('Edit')
                ->class('text-indigo-600 hover:text-indigo-900 hover:underline')
                ->route('admin.vehicles.edit', ['vehicle' => 'id']),

            //    Button::add('destroy')
            //        ->caption('Delete')
            //        ->class('bg-red-500 cursor-pointer text-white px-3 py-2 m-1 rounded text-sm')
            //        ->route('vehicle.destroy', ['vehicle' => 'id'])
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
     * PowerGrid Vehicle Action Rules.
     *
     * @return array<int, \PowerComponents\LivewirePowerGrid\Rules\RuleActions>
     */

    /*
    public function actionRules(): array
    {
       return [
           
           //Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($vehicle) => $vehicle->id === 1)
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
     * PowerGrid Vehicle Update.
     *
     * @param array<string,string> $data
     */

    /*
    public function update(array $data ): bool
    {
       try {
           $updated = Vehicle::query()->findOrFail($data['id'])
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
