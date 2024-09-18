<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Auth;

class UsersDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->setRowId('id')
            ->addColumn('is_active', function ($q) {
                if ($q->is_active == 1) {
                    return '<span class="badge badge-pill badge-success mb-1">' . __('Active') . '</span>';
                } else {
                    return '<span class="badge badge-pill badge-danger mb-1">' . __('In Active') . '</span>';
                }
            })
            ->addColumn('role_id', function ($q) {
                // return $q->role->name ?? '';
                return $q->role_name;
            })
            ->addColumn('created_at', function ($q) {
                return date('d F Y', strtotime($q->created_at));
            })
            ->addColumn('action', function ($q) {
                $output = '';
                if (Auth::user()->can('Edit User')) {
                    $output .= '<a href="#" class="modal-btn-xl tooltip-custom" data-title="' . __('Edit') . '" data-url="' . route('users.edit', $q->id) . '" data-toggle="modal" data-backdrop="static">
                            <i class="iconsminds-folder-edit icon-text-size"></i>
                            <span class="tooltiptext">Edit</span>
                            </a> &nbsp; &nbsp;';
                }

                if (Auth::user()->can('Delete User')) {
                    $output .= '
                    <form method="post" action="' . route('users.destroy', $q->id) . '" style="display:inline">
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
            ->rawColumns(['is_active', 'action']);
    }

    public function query(User $model): QueryBuilder
    {
        return $model->newQuery()
            ->select('users.*', 'roles.name as role_name', 'institutes.name as institute_name')
            ->leftjoin('roles', 'users.role_id', '=', 'roles.id')
            ->leftjoin('institutes', 'users.institute_id', '=', 'institutes.id');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('users-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            // ->orderBy(1)
            ->dom('lfrtip')
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
            ]);
    }

    public function getColumns(): array
    {
        return [
            // Column::make('id'),
            Column::make('name')->title('Nama Pengguna'),
            Column::make('institute_name', 'institutes.name')->title('Instansi'),
            Column::make('username')->title('Username')->addClass('font_italic'),
            Column::make('email')->title(__('Email'))->addClass('font_mail'),
            Column::make('phone_number')->title('No. Telp'),
            Column::make('role_id', 'roles.name')->title('Akses'),
            Column::make('is_active')->title(__('Status'))->searchable(false),
            Column::make('created_at')->title(__('Dibuat')),
            Column::make('action')->title(__('Aksi'))->searchable(false)->orderable(false)->addClass('text-center')
        ];
    }

    protected function filename(): string
    {
        return 'Users_' . date('YmdHis');
    }
}
