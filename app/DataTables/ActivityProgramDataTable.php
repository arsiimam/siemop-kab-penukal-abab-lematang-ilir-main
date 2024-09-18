<?php

namespace App\DataTables;

use App\Models\ActivityProgram;
use Auth;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ActivityProgramDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($q) {
                $output = '';
                if (Auth::user()->can('Edit Activity Program')) {
                    $output .= '<a href="#" class="modal-btn tooltip-custom" data-title="' . __('Edit') . '" data-url="' . route('activity-program.edit', $q->id) . '" data-toggle="modal" data-backdrop="static">
                            <i class="iconsminds-folder-edit icon-text-size"></i>
                            <span class="tooltiptext">Edit</span>
                            </a> &nbsp; &nbsp;';
                }

                if (Auth::user()->can('Delete Activity Program')) {
                    $output .= '
                    <form method="post" data-target="' . url('api/activity-program/' . $q->id) . '" data-token="' . session('bearerToken') . '" data-async-delete style="display:inline">
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
            ->rawColumns(['action', 'status', 'checkbox']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(ActivityProgram $model): QueryBuilder
    {
        return $model->newQuery()
            ->selectRaw('activity_programs.*, institutes.name as dinas')
            ->join('institutes', 'activity_programs.institute_id', '=', 'institutes.id')
            ->when(!Auth::user()->can('Manage Any Activity Program'), function ($q) {
                $q->where('institute_id', Auth::user()->institute_id);
            });
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('activity-program-table')
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
            Column::make('title')->title(__('Nama Program Kerja')),
            Column::make('dinas', 'institutes.name')->title(__('Perangkat Daerah')),
            Column::make('action')->title(__('Aksi'))->searchable(false)->orderable(false)->width(100),
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
        return 'ActivityProgram_' . date('YmdHis');
    }
}
