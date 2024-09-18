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

class ActivityReportDataTable extends DataTable
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
                return 'Rp. ' . number_format($q->pagu_indikatif);
            })
            ->addColumn('month', function ($q) {
                $month = month_list();

                return $month[$q->month];
            })
            ->addColumn('status', function ($q) {
                $output = '';
                if ($q->status == 'done') {
                    $output = '<span class="badge badge-primary">Selesai</span>';
                } else {
                    $output = '<span class="badge badge-info">Dalam Proses</span>';
                }

                return $output;
            })
            ->addColumn('created_at', function ($q) {
                return dateTime_format($q->created_at);
            })
            ->addColumn('action', function ($q) {
                $output = '';

                $output .= '<a href="' . route('description-of-activities.index', $q->id) . '" class="tooltip-custom">
                            <i class="iconsminds-information icon-text-size"></i>
                            <span class="tooltiptext">Detail</span>
                            </a> &nbsp;';

                if (Auth::user()->can('Edit Activity Report')) {
                    $output .= '<a href="#" class="modal-btn-xl tooltip-custom" data-title="' . __('Edit') . '" data-url="' . route('activity-report.edit', $q->id) . '" data-toggle="modal" data-backdrop="static">
                            <i class="iconsminds-folder-edit icon-text-size"></i>
                            <span class="tooltiptext">Edit</span>
                            </a> &nbsp;';
                }

                if (Auth::user()->can('Delete Activity Report')) {
                    $output .= '
                    <form method="post" data-target="' . url('api/activity-report/' . $q->id) . '" data-token="' . session('bearerToken') . '" data-async-delete style="display:inline">
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
    public function query(ActivityReport $model): QueryBuilder
    {
        return $model->newQuery()
            ->selectRaw('activity_reports.*, activity_programs.title as program_title, institutes.name as dinas')
            ->leftJoin('activity_programs', 'activity_reports.activityprogram_id', '=', 'activity_programs.id')
            ->leftJoin('users', 'activity_reports.user_id', '=', 'users.id')
            ->leftJoin('institutes', 'activity_reports.institute_id', '=', 'institutes.id')
            ->where('parent_id', null)
            ->when($this->request->get('institute_id') != '', function ($q) {
                $q->where('institutes.id', $this->request->get('institute_id'));
            })
            ->when(!Auth::user()->can('Manage Any Activity Report'), function ($q) {
                $q->where('institutes.id', Auth::user()->institute_id);
            })
            ->when($this->request->get('month') != '', function ($q) {
                $q->where('month', $this->request->get('month'));
            }, function ($q) {
                $q->where('month', date('m'));
            })
            ->when($this->request->get('year') != '', function ($q) {
                $q->where('year', $this->request->get('year'));
            }, function ($q) {
                $q->where('year', date('Y'));
            })
            ->orderBy('month', 'desc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('activityreport-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->ordering(false)
            ->pageLength(25)
            ->dom('lfrtip');
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('program_title', 'activity_programs.title')->title(__('Uraian kegiatan')),
            Column::make('pagu_indikatif')->searchable(false)->orderable(false),
            Column::make('target_kinerja')->searchable(false)->orderable(false),
            Column::make('dinas', 'institutes.name')->title(__('Perangkat Daerah'))->searchable(false)->orderable(false),
            Column::make('status')->orderable(false)->searchable(false),
            Column::make('month')->title(__('Bulan'))->searchable(false)->orderable(false),
            Column::make('year')->title(__('Tahun'))->searchable(false)->orderable(false),
            // Column::make('created_at')->title(__('Dibuat'))->searchable(false)->orderable(false),
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
        return 'ActivityReport_' . date('YmdHis');
    }
}
