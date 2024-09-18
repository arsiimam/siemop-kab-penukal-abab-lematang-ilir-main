<?php

namespace App\DataTables;

use App\Models\Voucher;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ReportVoucherDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('price', function ($q) {
                return number_format($q->price);
            })
            ->addColumn('status', function ($q) {
                $output = '';
                if ($q->status == 'active') $output .= '<span class="badge badge-primary">Active</span>';
                else  $output .= '<span class="badge badge-danger">Non Active</span>';

                return $output;
            })
            ->addColumn('count_trx', function ($q) {
                return $q->count_trx . ' Terjual';
            })
            ->rawColumns(['status']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Voucher $model): QueryBuilder
    {
        return $model->newQuery()
            ->selectRaw('vouchers.*, count(transactions.id) as count_trx')
            ->leftjoin('transactions', function ($left_join) {
                $left_join->on('transactions.voucher_id', '=', 'vouchers.id')
                    ->where('transactions.deleted_at', null)
                    ->when($this->request->get('date') != '', function ($q) {
                        $date = $this->request->date;
                        $start_date = date("Y-m-d", strtotime(strtok($date, " ")));
                        $dateToArr = explode(' ', $date);
                        $end_date = date('Y-m-d', strtotime(array_pop($dateToArr)));

                        $q->whereDate('date', '>=', $start_date)->whereDate('date', '<=', $end_date);
                    }, function ($q) {
                        $q->whereDate('date', date('Y-m-d'));
                    });
            })
            ->groupBy('vouchers.id');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('reportvoucher-table')
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
            Column::make('title')->title(__('Nama Voucher')),
            Column::make('price')->title(__('Harga / Saldo'))->searchable(false)->orderable(false),
            Column::make('minute_rate')->title(__('Biaya Per-Menit')),
            Column::make('status')->searchable(false)->orderable(false),
            Column::make('count_trx')->title(__('Jumlah Transaksi'))->searchable(false)->orderable(false)->addClass('font-weight-bold')
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'ReportVoucher_' . date('YmdHis');
    }
}
