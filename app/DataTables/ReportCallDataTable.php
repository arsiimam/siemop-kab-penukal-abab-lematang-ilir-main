<?php

namespace App\DataTables;

use App\Models\Call;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ReportCallDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('created_at', function ($q) {
                return date('d-m-Y H:i:s', strtotime($q->created_at));
            })
            ->addColumn('duration', function ($q) {
                return $q->duration != null ? gmdate('H:i:s', $q->duration) : '00:00:00';
            })
            ->addColumn('recording', function ($q) {
                $output = '';

                if ($q->recording_file != null) {
                    $output .= '<a href="#" class="modal-btn tooltip-custom" data-title="' . __('Putar Recording') . '" data-url="' . route('report.call.recording', $q->id) . '" data-toggle="modal" data-backdrop="static">
                                <i class="iconsminds-play-music icon-text-size"></i>
                                <span class="tooltiptext">Play</span>
                            </a> &nbsp; &nbsp;';

                    $output .= '<a href="' . asset($q->recording_file) . '" download class="tooltip-custom">
                                <i class="iconsminds-data-download icon-text-size"></i>
                                <span class="tooltiptext">Download</span>
                            </a> &nbsp; &nbsp;';
                }


                return $output;
            })
            ->rawColumns(['recording']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Call $model): QueryBuilder
    {
        return $model->newQuery()
            ->selectRaw('calls.*, customers.name')
            ->join('transactions', 'calls.trx_id', '=', 'transactions.id')
            ->join('customers', 'transactions.customer_id', '=', 'customers.id')
            ->when($this->request->get('customer_id') != '', function ($q) {
                $q->where('customer_id', $this->request->get('customer_id'));
            })
            ->when($this->request->get('date') != '', function ($q) {
                $date = $this->request->date;
                $start_date = date("Y-m-d", strtotime(strtok($date, " ")));
                $dateToArr = explode(' ', $date);
                $end_date = date('Y-m-d', strtotime(array_pop($dateToArr)));

                $q->whereDate('calls.created_at', '>=', $start_date)->whereDate('calls.created_at', '<=', $end_date);
            }, function ($q) {
                $q->whereDate('calls.created_at', date('Y-m-d'));
            })
            ->orderBy('calls.created_at', 'desc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('reportcall-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0)
            ->ordering(false)
            ->dom('lfrtip');
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('created_at')->title(__('Tanggal'))->orderable(false)->searchable(false),
            Column::make('name', 'customers.name')->title(__('Nama Customer')),
            Column::make('src')->title(__('No. Telp')),
            Column::make('start_time')->orderable(false)->searchable(false),
            Column::make('end_time')->orderable(false)->searchable(false),
            Column::make('duration')->orderable(false)->searchable(false),
            Column::make('status')->orderable(false)->searchable(false),
            Column::make('recording')->orderable(false)->searchable(false),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'ReportCall_' . date('YmdHis');
    }
}
