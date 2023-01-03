<?php

namespace App\Http\Livewire;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridEloquent;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;

final class PendingInvoiceTable extends PowerGridComponent
{
    use ActionButton;

    public string $sortDirection = 'desc';
    public string $sortField = 'id';

    public string $vehicle_id;
    public string $location_id;

    /*
    |--------------------------------------------------------------------------
    |  Datasource
    |--------------------------------------------------------------------------
    | Provides data to your Table using a Model or Collection
    |
    */
    public function datasource(): ?Collection
    {
        $query = 'select date as id,
            count(case when delivery_state_id = 1 then id end) as Pending,
            count(case when delivery_state_id = 2 then id end) as Delivered,
            count(case when delivery_state_id = 3 then id end) as Cancelled,
            count(id) as Total 
            from invoices';

        if ($this->vehicle_id) {
            $query .= ' where vehicle_id = ' . $this->vehicle_id;

            if ($this->location_id) {
                $query .= ' and location_id = ' . $this->location_id;
            }
        }

        if ($this->location_id && !$this->vehicle_id) {
            $query .= ' where location_id = ' . $this->location_id;
        }


        $query .= ' group by date';


        $data = collect(\DB::select($query));

        return $data;
    }

    /*
    |--------------------------------------------------------------------------
    |  Relationship Search
    |--------------------------------------------------------------------------
    | Configure here relationships to be used by the Search and Table Filters.
    |
    */
    public function setUp(): void
    {
        $this->showCheckBox()
            ->showPerPage()
            ->showExportOption('download', ['excel', 'csv'])
            ->showSearchInput();
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
            ->addColumn('id')
            ->addColumn('Pending')
            ->addColumn('Delivered')
            ->addColumn('Cancelled')
            ->addColumn('Total');
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
                ->title('DATE')
                ->field('id')
                ->makeInputDatePicker()
                ->sortable(),
            Column::add()
                ->title('PENDING')
                ->field('Pending')
                ->sortable(),
            Column::add()
                ->title('DELIVERED')
                ->field('Delivered')
                ->sortable(),
            Column::add()
                ->title('CANCELLED')
                ->field('Cancelled')
                ->sortable(),
            Column::add()
                ->title('TOTAL')
                ->field('Total')
                ->sortable(),
        ];
    }
}
