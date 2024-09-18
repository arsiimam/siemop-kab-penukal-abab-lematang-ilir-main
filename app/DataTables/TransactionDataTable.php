<?php

namespace App\DataTables;

use App\Models\Transaction;
use Auth;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TransactionDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('status', function ($q) {
                $output = '';
                if ($q->status == 'active') $output .= '<span class="badge badge-primary">Active</span>';
                else  $output .= '<span class="badge badge-danger">Non Active</span>';

                return $output;
            })
            ->addColumn('action', function ($q) {
                $output = '';

                $output .= '<a href="#" class="modal-btn-xl tooltip-custom" data-title="' . __('Detail Penggunaan') . '" data-url="' . route('transaction.show', $q->id) . '" data-toggle="modal" data-backdrop="static">
                                <i class="iconsminds-information icon-text-size"></i>
                                <span class="tooltiptext">Detail</span>
                            </a> &nbsp; &nbsp;';

                if (Auth::user()->can('Delete Transaction')) {
                    $output .= '
                    <form method="post" data-target="' . url('api/transaction/' . $q->id) . '" data-token="' . session('bearerToken') . '" data-async-delete style="display:inline">
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
            ->rawColumns(['status', 'action', 'checkbox']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Transaction $model): QueryBuilder
    {
        return $model->newQuery()
            ->selectRaw('transactions.*, customers.name as customer_name, vouchers.title as voucher_name, vouchers.price, vouchers.status as voucher_status')
            ->join('customers', 'transactions.customer_id', '=', 'customers.id')
            ->join('vouchers', 'transactions.voucher_id', '=', 'vouchers.id')
            ->when($this->request->get('voucher_id') != '', function ($q) {
                $q->where('voucher_id', $this->request->get('voucher_id'));
            })
            ->when($this->request->get('customer_id') != '', function ($q) {
                $q->where('customer_id', $this->request->get('customer_id'));
            })
            ->when($this->request->get('status') != '', function ($q) {
                $q->where('transactions.status', $this->request->get('status'));
            });
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('transaction-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(3)
            ->dom('lfrtip');
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('uniqueid')->title(__('Kode Voucher')),
            Column::make('customer_name', 'customers.name')->title(__('Customer')),
            Column::make('voucher_name', 'vouchers.title')->title(__('Nama Voucher')),
            Column::make('date')->title(__('Tanggal')),
            Column::make('saldo')->title(__('Sisa Saldo')),
            Column::make('status')->title(__('Status')),
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
        return 'Transaction_' . date('YmdHis');
    }
}
