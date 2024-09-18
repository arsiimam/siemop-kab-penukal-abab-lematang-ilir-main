<?php

namespace App\DataTables;

use App\Models\Customer;
use Auth;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CustomerDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('voucher', function ($q) {
                return count($q->transaction()) . ' Voucher';
            })
            ->addColumn('panggilan', function ($q) {
                return count($q->calls($q->id)) . ' Panggilan';
            })
            ->addColumn('saldo', function ($q) {
                return 'Rp. ' . number_format($q->total_price($q->id));
            })
            ->addColumn('remaining_call_time', function ($q) {
                $transaction = $q->transaction();

                $remaining_time = 0;

                foreach ($transaction as $val) {
                    $remaining_time += $val->saldo / $val->voucher->minute_rate * 60;
                }

                return gmdate("H:i:s", $remaining_time);
            })
            ->addColumn('action', function ($q) {
                $output = '<a href="#" class="modal-btn-xl tooltip-custom" data-title="' . __('Edit') . '" data-url="' . route('customer.show', $q->id) . '" data-toggle="modal" data-backdrop="static">
                            <i class="iconsminds-information icon-text-size"></i>
                            <span class="tooltiptext">Detail</span>
                            </a> &nbsp; &nbsp;';

                if (Auth::user()->can('Edit Customer')) {
                    $output .= '<a href="#" class="modal-btn tooltip-custom" data-title="' . __('Edit') . '" data-url="' . route('customer.edit', $q->id) . '" data-toggle="modal" data-backdrop="static">
                            <i class="iconsminds-folder-edit icon-text-size"></i>
                            <span class="tooltiptext">Edit</span>
                            </a> &nbsp; &nbsp;';
                }

                if (Auth::user()->can('Delete Customer')) {
                    $output .= '
                    <form method="post" data-target="' . url('api/customer/' . $q->id) . '" data-token="' . session('bearerToken') . '" data-async-delete style="display:inline">
                        <input type="hidden" name="_method" value="delete">
                        <button type="submit" class="tooltip-custom" style="padding: 0; border: none; background: none;">
                            <i class="iconsminds-close icon-text-size"></i>
                            <span class="tooltiptext">Hapus</span>
                        </button>
                    </form>
                    ';
                }

                return $output;
            })
            ->addColumn('checkbox', function ($q) {
                $output = '
                            <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input delete_check" data-delete id="customCheck' . $q->id . '" value="' . $q->id . '">
                                    <label class="custom-control-label" for="customCheck' . $q->id . '"></label>
                            </div>
                    ';

                return $output;
            })
            ->rawColumns(['action', 'checkbox']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Customer $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('customer-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('lfrtip');
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('name'),
            Column::make('voucher')->searchable(false)->orderable(false),
            Column::make('panggilan')->searchable(false)->orderable(false),
            Column::make('saldo')->searchable(false)->orderable(false),
            Column::make('remaining_call_time')->title(__('Sisa Waktu'))->searchable(false)->orderable(false),
            Column::make('action')->searchable(false)->orderable(false)->width(150),
            Column::make('checkbox')->title('
                <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input align-self-center data-table-rows-check" id="checkAll">
                        <label class="custom-control-label" for="checkAll"></label>
                </div>
            ')->orderable(false)->searchable(false)->width(50)
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Customer_' . date('YmdHis');
    }
}
