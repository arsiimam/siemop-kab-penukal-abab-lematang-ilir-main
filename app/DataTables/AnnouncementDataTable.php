<?php

namespace App\DataTables;

use App\Models\Announcement;
use Auth;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class AnnouncementDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('dinas', function ($q) {
                if ($q->dinas == null) return 'SEMUA DINAS';
                else return $q->dinas;
            })
            ->addColumn('start_date', function ($q) {
                return date('d F Y', strtotime($q->start_date));
            })
            ->addColumn('end_date', function ($q) {
                return date('d F Y', strtotime($q->end_date));
            })
            ->addColumn('action', function ($q) {
                $output = '';
                if (Auth::user()->can('Edit Announcement')) {
                    $output .= '<a href="#" class="modal-btn tooltip-custom" data-title="' . __('Edit') . '" data-url="' . route('announcement.edit', $q->id) . '" data-toggle="modal" data-backdrop="static">
                            <i class="iconsminds-folder-edit icon-text-size"></i>
                            <span class="tooltiptext">Edit</span>
                            </a> &nbsp; &nbsp;';
                }

                if (Auth::user()->can('Delete Announcement')) {
                    $output .= '
                    <form method="post" data-target="' . url('api/announcement/' . $q->id) . '" data-token="' . session('bearerToken') . '" data-async-delete style="display:inline">
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
    public function query(Announcement $model): QueryBuilder
    {
        return $model->newQuery()
            ->selectRaw('announcements.*, institutes.name as dinas')
            ->leftJoin('institutes', 'announcements.institute_id', '=', 'institutes.id')
            ->when(!Auth::user()->can('Manage Any Announcement'), function ($q) {
                $q->where('institute_id', null)->orWhere('institute_id', Auth::user()->institute_id);
            })
            ->orderBy('start_date', 'desc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('announcement-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->ordering(false)
            ->dom('lfrtip');
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        $column = [
            Column::make('title')->title(__('Judul')),
            Column::make('dinas', 'institutes.name')->title(__('Perangkat Daerah'))->orderable(false)->searchable(false),
            Column::make('start_date')->title(__('Tanggal Mulai'))->orderable(false)->searchable(false),
            Column::make('end_date')->title(__('Tanggal Selesai'))->orderable(false)->searchable(false),
            Column::make('description')->title(__('Deskripsi'))->orderable(false)->searchable(false)->width(200),
        ];

        if (Auth::user()->can('Edit Announcement') || Auth::user()->can('Delete Announcement')) {
            array_push(
                $column,
                Column::make('action')->title(__('Aksi'))->searchable(false)->orderable(false)->width(100)
            );
        }

        if (Auth::user()->can('Delete Announcement')) {
            array_push(
                $column,
                Column::make('checkbox')->title('
                <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input align-self-center data-table-rows-check" id="checkAll">
                        <label class="custom-control-label" for="checkAll"></label>
                </div>
            ')->orderable(false)->searchable(false)->width(50)
            );
        }


        return $column;
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Announcement_' . date('YmdHis');
    }
}
