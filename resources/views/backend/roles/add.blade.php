{{ Form::open(['url' => 'roles', 'method' => 'post', 'id' => 'myForm']) }}
<div class="modal-body">

    <div class="container-fluid">
        <div class="row">
            <div class="form-group col-md-12">
                <label>{{ __('Name') }}</label>
                {{ Form::text('name', old('name'), ['class' => 'form-control', 'required']) }}
            </div>
            <div class="form-group col-md-12">
                @if (!empty($permissions))
                    <label>{{ __(' Permission to Roles') }}</label>

                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>
                                    <input type="checkbox" name="checkall" id="checkall">
                                </th>
                                <th>{{ __('Module') }} </th>
                                <th class="text-center">{{ __('Permissions') }} </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $modules = [
                                    'User',
                                    'Role',
                                    'Permission',
                                    'Setting',
                                    'Institute',
                                    'Activity Program',
                                    'Activity Report',
                                    'Report',
                                    'Statistics',
                                    'As Super Admin',
                                    'Announcement',
                                    'Development',
                                ];

                                sort($modules);
                            @endphp

                            @foreach ($modules as $module)
                                <tr>
                                    <td><input type="checkbox" class="ischeck" name="checkall"
                                            data-id="{{ str_replace(' ', '', $module) }}"></td>
                                    <td><label class="ischeck form-label"
                                            data-id="{{ str_replace(' ', '', $module) }}">{{ ucfirst($module) }}</label>
                                    </td>
                                    <td>
                                        <div class="row ">
                                            @if (in_array('Manage ' . $module, (array) $permissions))
                                                @if ($key = array_search('Manage ' . $module, $permissions))
                                                    <div class="col-md-3 custom-control custom-checkbox">
                                                        {{ Form::checkbox('permissions[]', $key, false, ['class' => 'isscheck isscheck_' . str_replace(' ', '', $module), 'id' => 'permission' . $key]) }}
                                                        {{ Form::label('permission' . $key, 'Manage', ['class' => 'form-label font-weight-500']) }}<br>
                                                    </div>
                                                @endif
                                            @endif
                                            @if (in_array('Manage Any ' . $module, (array) $permissions))
                                                @if ($key = array_search('Manage Any ' . $module, $permissions))
                                                    <div class="col-md-3 custom-control custom-checkbox">
                                                        {{ Form::checkbox('permissions[]', $key, false, ['class' => 'isscheck isscheck_' . str_replace(' ', '', $module), 'id' => 'permission' . $key]) }}
                                                        {{ Form::label('permission' . $key, 'Manage Any', ['class' => 'form-label font-weight-500']) }}<br>
                                                    </div>
                                                @endif
                                            @endif
                                            @if (in_array('Create ' . $module, (array) $permissions))
                                                @if ($key = array_search('Create ' . $module, $permissions))
                                                    <div class="col-md-3 custom-control custom-checkbox">
                                                        {{ Form::checkbox('permissions[]', $key, false, ['class' => 'isscheck isscheck_' . str_replace(' ', '', $module), 'id' => 'permission' . $key]) }}
                                                        {{ Form::label('permission' . $key, 'Create', ['class' => 'form-label font-weight-500']) }}<br>
                                                    </div>
                                                @endif
                                            @endif
                                            @if (in_array('Edit ' . $module, (array) $permissions))
                                                @if ($key = array_search('Edit ' . $module, $permissions))
                                                    <div class="col-md-3 custom-control custom-checkbox">
                                                        {{ Form::checkbox('permissions[]', $key, false, ['class' => 'isscheck isscheck_' . str_replace(' ', '', $module), 'id' => 'permission' . $key]) }}
                                                        {{ Form::label('permission' . $key, 'Edit', ['class' => 'form-label font-weight-500']) }}<br>
                                                    </div>
                                                @endif
                                            @endif
                                            @if (in_array('Delete ' . $module, (array) $permissions))
                                                @if ($key = array_search('Delete ' . $module, $permissions))
                                                    <div class="col-md-3 custom-control custom-checkbox">
                                                        {{ Form::checkbox('permissions[]', $key, false, ['class' => 'isscheck isscheck_' . str_replace(' ', '', $module), 'id' => 'permission' . $key]) }}
                                                        {{ Form::label('permission' . $key, 'Delete', ['class' => 'form-label font-weight-500']) }}<br>
                                                    </div>
                                                @endif
                                            @endif
                                            @if (in_array('Show ' . $module, (array) $permissions))
                                                @if ($key = array_search('Show ' . $module, $permissions))
                                                    <div class="col-md-3 custom-control custom-checkbox">
                                                        {{ Form::checkbox('permissions[]', $key, false, ['class' => 'isscheck isscheck_' . str_replace(' ', '', $module), 'id' => 'permission' . $key]) }}
                                                        {{ Form::label('permission' . $key, 'Show', ['class' => 'form-label font-weight-500']) }}<br>
                                                    </div>
                                                @endif
                                            @endif

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>

</div>
<div class="modal-footer pt-3 pb-3">
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-info mr-1" data-dismiss="modal"
            style="width: 80px;">{{ __('Batal') }}</button>
        <button type="submit" class="btn btn-primary" style="width: 80px;">{{ __('Simpan') }}</button>
    </div>
</div>
{{ Form::close() }}

<script>
    $(document).ready(function() {
        $("#checkall").click(function() {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
        $(".ischeck").click(function() {
            var ischeck = $(this).data('id');
            $('.isscheck_' + ischeck).prop('checked', this.checked);
        });
    });

    function submitForm(id) {
        document.getElementById(id).submit();
    }
</script>
