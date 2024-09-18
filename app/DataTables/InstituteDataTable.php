<?php

namespace App\DataTables;

use App\Models\Institute;
use Auth;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class InstituteDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('paraf_image', function ($q) {

                if ($q->paraf_image == null) {
                    return '-';
                }

                return '<img src="' . asset($q->paraf_image) . '" height="50">';
            })
            ->addColumn('action', function ($q) {
                $output = '';
                if (Auth::user()->can('Edit Institute')) {
                    $output .= '<a href="#" class="modal-btn tooltip-custom" data-title="' . __('Edit') . '" data-url="' . route('institute.edit', $q->id) . '" data-toggle="modal" data-backdrop="static">
                            <i class="iconsminds-folder-edit icon-text-size"></i>
                            <span class="tooltiptext">Edit</span>
                            </a> &nbsp; &nbsp;';
                }

                if (Auth::user()->can('Delete Institute')) {
                    $output .= '
                    <form method="post" data-target="' . url('api/institute/' . $q->id) . '" data-token="' . session('bearerToken') . '" data-async-delete style="display:inline">
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
            ->addColumn('signature_status', function ($q) {
                return $q->signature_status == 1 ? '<span class="badge badge-success">Tampilkan</span>' : '<span class="badge badge-danger">Sembunyikan</span>';
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
            ->rawColumns(['action', 'paraf_image', 'status', 'checkbox']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Institute $model): QueryBuilder
    {
        return $model->newQuery()
            ->when(!Auth::user()->can('Manage Any Institute'), function ($q) {
                $q->where('id', Auth::user()->institute_id);
            });
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('institute-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('lfrtip');
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        $column = [
            Column::make('name')->title(__('Nama Perangkat Daerah / Instansi')),
            Column::make('head_of_institute')->title(__('Kepala Instansi')),
            Column::make('nip'),
            Column::make('paraf_image')->orderable(false)->searchable(false),
            Column::make('action')->title(__('Aksi'))->searchable(false)->orderable(false)->width(100)
        ];

        if (Auth::user()->can('Delete Institute')) {
            $column[] = Column::make('checkbox')->title('
                <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input align-self-center data-table-rows-check" id="checkAll">
                        <label class="custom-control-label" for="checkAll"></label>
                </div>
            ')->orderable(false)->searchable(false)->width(50);
        }

        return $column;
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Institute_' . date('YmdHis');
    }
}
