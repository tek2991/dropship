<?php

namespace App\Http\Livewire;

use App\Models\Invoice;
use App\Models\Location;
use Illuminate\Support\Str;
use App\Models\DeliveryState;
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

final class InvoiceTablePending extends PowerGridComponent
{
    use ActionButton;

    public string $sortField = 'id';

    public string $sortDirection = 'asc';

    public string $days;
    public string $days2;

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
     * @return  \Illuminate\Database\Eloquent\Builder<\App\Models\Invoice>|null
     */
    public function datasource(): ?Builder
    {
        $isAdmin = auth()->user()->isAdmin();
        $isManager = auth()->user()->isManager();
        // parse days as integer
        $days = (int) $this->days;
        $days2 = (int) $this->days2;

        $query = Invoice::query();

        if ($days == 14) {
            $query->where('invoices.date', '<=', Carbon::now()->subDays($days)->toDateString());
        } else if ($days2 > 0) {
            $query->whereBetween('invoices.date', [Carbon::now()->subDays($days2)->toDateString(), Carbon::now()->subDays($days)->toDateString()]);
        } else {
            $query->where('invoices.date', Carbon::now()->subDays($days)->toDateString());
        }


        if ($isAdmin) {
            return $query->where('delivery_state_id', DeliveryState::STATE_PENDING)
                ->join('transporters', 'transporters.id', '=', 'invoices.transporter_id')
                ->join('users', 'users.id', '=', 'transporters.user_id')
                ->join('vehicles', 'vehicles.id', '=', 'invoices.vehicle_id')
                ->select('invoices.*', 'vehicles.registration_number as vehicle_registration_number', 'users.name as transporter_name')
                ->with('clientUser', 'client', 'logSheet', 'location', 'transporterUser', 'driverUser');
        } else if ($isManager) {
            $manager = auth()->user()->manager;
            $location_ids = $manager->locations->pluck('id')->toArray();
            return $query->whereIn('location_id', $location_ids)
                ->where('delivery_state_id', DeliveryState::STATE_PENDING)
                ->join('transporters', 'transporters.id', '=', 'invoices.transporter_id')
                ->join('users', 'users.id', '=', 'transporters.user_id')
                ->join('vehicles', 'vehicles.id', '=', 'invoices.vehicle_id')
                ->select('invoices.*', 'vehicles.registration_number as vehicle_registration_number', 'users.name as transporter_name')
                ->with('clientUser', 'client', 'logSheet', 'location', 'transporterUser', 'driverUser');
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
        return [
            'clientUser' => [
                'name',
                'email',
                'phone',
            ],
            'logSheet' => [
                'log_sheet_no',
            ],
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
        return PowerGrid::eloquent()
            ->addColumn('invoice_no')
            ->addColumn('date_formatted', function (Invoice $model) {
                return Carbon::parse($model->date)->format('d/m/Y');
            })
            ->addColumn('transporter_name', function (Invoice $model) {
                return Str::limit($model->transporterUser->name, 12);
            })
            ->addColumn('transporter_name_full', function (Invoice $model) {
                return $model->transporterUser->name;
            })
            ->addColumn('transporterUser.phone')
            ->addColumn('vehicle_registration_number')
            ->addColumn('location_name', function (Invoice $model) {
                return $model->location->name;
            })
            ->addColumn('clientUser.name', function (Invoice $model) {
                return Str::limit($model->clientUser->name, 9);
            })
            ->addColumn('clientUser.name_full', function (Invoice $model) {
                return $model->clientUser->name;
            })
            ->addColumn('clientUser.phone')
            ->addColumn('gross_weight')
            ->addColumn('no_of_packs')
            ->addColumn('logSheet.log_sheet_no')
            ->addColumn('delivery_status', function (Invoice $model) {
                return ucfirst($model->deliveryState->name);
            })
            ->addColumn('driver_name', function (Invoice $model) {
                return $model->driverUser->name;
            })
            ->addColumn('driver_phone', function (Invoice $model) {
                return $model->driverUser->phone;
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
                ->title('INVOICE NO')
                ->field('invoice_no')
                ->sortable()
                ->searchable()
                ->makeInputText(),

            Column::add()
                ->title('DATE')
                ->field('date_formatted', 'date')
                ->sortable()
                ->makeInputDatePicker('date'),

            Column::add()
                ->title('TRANSPORTER')
                ->field('transporter_name')
                ->sortable()
                ->searchable()
                ->makeInputText()
                ->visibleInExport(false),

            Column::add()
                ->title('TRANSPORTER')
                ->field('transporter_name_full')
                ->hidden()
                ->visibleInExport(true),

            Column::add()
                ->title('TRANSPORTER PHONE')
                ->field('transporterUser.phone')
                ->hidden()
                ->visibleInExport(true),

            Column::add()
                ->title('VEHICLE')
                ->field('vehicle_registration_number')
                ->sortable()
                ->searchable()
                ->makeInputText(),

            Column::add()
                ->title('LOCATION')
                ->field('location_name')
                ->sortable()
                ->makeInputSelect(Location::all(), 'name', 'location_id'),

            Column::add()
                ->title('CLIENT')
                ->field('clientUser.name')
                ->searchable()
                ->visibleInExport(false),

            Column::add()
                ->title('CLIENT')
                ->field('clientUser.name_full')
                ->hidden()
                ->visibleInExport(true),

            Column::add()
                ->title('CLIENT PHONE')
                ->field('clientUser.phone')
                ->hidden()
                ->visibleInExport(true),

            Column::add()
                ->title('Wt(Kg)')
                ->field('gross_weight')
                ->sortable(),

            Column::add()
                ->title('PKS')
                ->field('no_of_packs')
                ->sortable(),

            Column::add()
                ->title('LOG SHEET NO')
                ->field('logSheet.log_sheet_no'),

            Column::add()
                ->title('STATUS')
                ->field('delivery_status'),

            Column::add()
                ->title('DRIVER')
                ->field('driver_name')
                ->hidden()
                ->visibleInExport(true),

            Column::add()
                ->title('DRIVER PHONE')
                ->field('driver_phone')
                ->hidden()
                ->visibleInExport(true),
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
     * PowerGrid Invoice Action Buttons.
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
                ->route('admin.invoices.show', ['invoice' => 'id']),

            //    Button::add('destroy')
            //        ->caption('Delete')
            //        ->class('bg-red-500 cursor-pointer text-white px-3 py-2 m-1 rounded text-sm')
            //        ->route('invoice.destroy', ['invoice' => 'id'])
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
     * PowerGrid Invoice Action Rules.
     *
     * @return array<int, \PowerComponents\LivewirePowerGrid\Rules\RuleActions>
     */

    /*
    public function actionRules(): array
    {
       return [
           
           //Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($invoice) => $invoice->id === 1)
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
     * PowerGrid Invoice Update.
     *
     * @param array<string,string> $data
     */

    /*
    public function update(array $data ): bool
    {
       try {
           $updated = Invoice::query()->findOrFail($data['id'])
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
