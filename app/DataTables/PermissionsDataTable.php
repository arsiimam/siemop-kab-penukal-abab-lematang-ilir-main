<?php

namespace App\DataTables;

use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Auth;

class PermissionsDataTable extends DataTable
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

                if (Auth::user()->can('Edit Permission')) {
                    $output .= '<a href="#" class="modal-btn tooltip-custom" data-title="' . __('Edit') . '" data-url="' . route('permissions.edit', $q->id) . '" data-toggle="modal" data-backdrop="static">
                    <i class="iconsminds-folder-edit icon-text-size"></i>
                    <span class="tooltiptext">Edit</span>
                    </a> &nbsp; &nbsp;';
                }

                if (Auth::user()->can('Delete Permission')) {
                    $output .= '
                    <form method="post" action="' . route('permissions.destroy', $q->id) . '" style="display:inline">
                        <input type="hidden" name="_token" value="' . csrf_token() . '" readonly>
                        <input type="hidden" name="_method" value="delete">
                        <button type="button" class="btn-delete tooltip-custom" style="padding: 0; border: none; background: none;">
                        <i class="iconsminds-close icon-text-size"></i>
                        <span class="tooltiptext">Hapus</span>
                        </button>
                    </form>
                    ';
                }
                return $output;
            })
            ->rawColumns(['action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Permission $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('permissions-table')
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
            Column::make('name')->title(__('Name')),
            Column::make('action')->title(__('Aksi'))->searchable(false)->orderable(false)->width(250)->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Permissions_' . date('YmdHis');
    }
}
