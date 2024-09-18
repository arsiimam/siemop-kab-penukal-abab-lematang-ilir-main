<?php

namespace App\DataTables;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ReportTransactionDataTable extends DataTable
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
                return 'Rp. ' . number_format($q->price);
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Transaction $model): QueryBuilder
    {
        return $model->newQuery()
            ->selectRaw('transactions.*, customers.name as customer, vouchers.title as voucher, vouchers.price')
            ->join('customers', 'transactions.customer_id', '=', 'customers.id')
            ->join('vouchers', 'transactions.voucher_id', '=', 'vouchers.id')
            ->when($this->request->get('customer_id') != '', function ($q) {
                $q->where('customer_id', $this->request->get('customer_id'));
            })
            ->when($this->request->get('voucher_id') != '', function ($q) {
                $q->where('voucher_id', $this->request->get('voucher_id'));
            })
            ->when($this->request->get('date') != '', function ($q) {
                $date = $this->request->date;
                $start_date = date("Y-m-d", strtotime(strtok($date, " ")));
                $dateToArr = explode(' ', $date);
                $end_date = date('Y-m-d', strtotime(array_pop($dateToArr)));

                $q->whereDate('date', '>=', $start_date)->whereDate('date', '<=', $end_date);
            }, function ($q) {
                $q->whereDate('date', date('Y-m-d'));
            });
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('reporttransaction-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1, 'DESC')
            ->dom('lfrtip');
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('uniqueid')->title(__('Kode Voucher')),
            Column::make('date')->title(__('Tanggal Pembelian')),
            Column::make('customer', 'customers.name'),
            Column::make('voucher', 'vouchers.title'),
            Column::make('price', 'vouchers.price')->title(__('Harga'))->orderable(false)->searchable(false),
            Column::make('used_time')->title(__('Waktu Digunakan'))->orderable(false)->searchable(false),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'ReportTransaction_' . date('YmdHis');
    }
}
