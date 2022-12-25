<?php

namespace App\Http\Livewire;

use App\Models\Invoice;
use App\Models\DeliveryState;
use App\Models\DeliveryRemark;
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

final class ClientInvoiceTable extends PowerGridComponent
{
    use ActionButton;

    public $client_id;

    public function __construct($client_id)
    {
        $this->client_id = $client_id;
    }

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
     * @return  \Illuminate\Database\Eloquent\Builder<\App\Models\Invoice>|null
     */
    public function datasource(): ?Builder
    {
        return Invoice::query()->where('client_id', $this->client_id)->with('clientUser', 'client', 'logSheet', 'deliveryRemark');
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
            ->addColumn('clientUser.name')
            ->addColumn('gross_weight', function (Invoice $model) {
                return $model->gross_weight . ' Kg';
            })
            ->addColumn('no_of_packs')
            ->addColumn('logSheet.log_sheet_no')
            ->addColumn('delivery_status', function (Invoice $model) {
                return ucfirst($model->delivery_status);
            })
            ->addColumn('delivery_remark_id')
            ->addColumn('delivery_remark', function (Invoice $model) {
                return $model->deliveryRemark->remark;
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
                ->title('CLIENT')
                ->field('clientUser.name')
                ->searchable(),

            Column::add()
                ->title('GROSS WEIGHT')
                ->field('gross_weight')
                ->sortable(),

            Column::add()
                ->title('PACKS')
                ->field('no_of_packs')
                ->sortable(),

            Column::add()
                ->title('LOG SHEET NO')
                ->field('logSheet.log_sheet_no'),
                
            Column::add()
                ->title('STATUS')
                ->field('delivery_status')
                ->makeInputSelect(DeliveryState::all(), 'name', 'delivery_state_id')
                ->sortable(),

            Column::add()
                ->title('REMARK')
                ->field('delivery_remark')
                ->makeInputSelect(DeliveryRemark::all(), 'remark', 'delivery_remark_id')
                ->sortable(),

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
