<?php

namespace App\DataTables;

use App\Models\ActivityReport;
use Auth;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class DetailsActivityDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('pagu_indikatif', function ($q) {
                if ($q->pagu_indikatif == null) return '';
                return 'Rp. ' . number_format($q->pagu_indikatif);
            })
            ->addColumn('action', function ($q) {
                $output = '';

                if (Auth::user()->can('Create Activity Report')) {
                    $output .= '<a href="#" class="modal-btn-xl tooltip-custom" data-title="' . __('Tambah Kegiatan') . '" data-url="' . route('description-of-activities.create.details', $q->id) . '" data-toggle="modal" data-backdrop="static">
                            <i class="iconsminds-add icon-text-size"></i>
                            <span class="tooltiptext">Tambah Kegiatan</span>
                            </a> &nbsp;';
                }

                if (Auth::user()->can('Edit Activity Report')) {
                    $output .= '<a href="#" class="modal-btn-xl tooltip-custom" data-title="' . __('Edit') . '" data-url="' . route('description-of-activities.edit', $q->id) . '" data-toggle="modal" data-backdrop="static">
                            <i class="iconsminds-folder-edit icon-text-size"></i>
                            <span class="tooltiptext">Edit</span>
                            </a> &nbsp;';
                }

                if (Auth::user()->can('Delete Activity Report')) {
                    $output .= '
                    <form method="post" data-target="' . url('api/child-activity-report/' . $q->id) . '" data-token="' . session('bearerToken') . '" data-async-delete style="display:inline">
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
    public function query(ActivityReport $model): QueryBuilder
    {
        return $model->newQuery()
            ->where('parent_id', $this->attributes['parent_id']);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('detailsactivity-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->ordering(false)
            ->pageLength(100)
            ->dom('lfrtip');
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('title')->title(__('Uraian Urusan, Program dan Kegiatan')),
            Column::make('pagu_indikatif')->title(__('Pagu Indikatif <small>(Rp)</small>'))->searchable(false)->orderable(false),
            Column::make('target_kinerja')->title(__('Target Kinerja <small>(%)</small>'))->searchable(false)->orderable(false),
            Column::make('fisik')->title(__('Fisik <small>(Rp)</small>'))->searchable(false)->orderable(false),
            Column::make('non_fisik')->title(__('Non Fisik <small>(Rp)</small>'))->searchable(false)->orderable(false),
            Column::make('realization')->title(__('Keuangan <small>(Rp)</small>'))->searchable(false)->orderable(false),
            Column::make('percentage')->title(__('Prosentase <small>(%)</small>'))->searchable(false)->orderable(false),
            Column::make('executor')->title(__('Pelaksana / Kontruktor')),
            Column::make('contract_price')->title(__('Harga Kontrak <small>(Rp)</small>'))->searchable(false)->orderable(false),
            Column::make('location')->title(__('Lokasi Kegiatan')),
            Column::make('action')->title(__('Aksi'))->searchable(false)->orderable(false)->width(110),
            Column::make('checkbox')->title('
                <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input align-self-center data-table-rows-check" id="checkAll">
                        <label class="custom-control-label" for="checkAll"></label>
                </div>
            ')->orderable(false)->searchable(false)
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'DetailsActivity_' . date('YmdHis');
    }
}
