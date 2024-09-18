{{ Form::open(['url' => 'permissions', 'method' => 'post', 'id' => 'myForm', 'class' => 'needs-validation tooltip-label-right', 'novalidate']) }}
<div class="modal-body">

    <div class="form-group position-relative error-l-50">
        <label>{{ __('Name') }}</label>
        <input type="text" name="name" class="form-control" placeholder="" required>
        <div class="invalid-tooltip">
            {{ __('Name is required!') }}
        </div>
    </div>

    <div class="form-group">
        <label>{{ __('Assaign Permission to Roles') }}</label>

        @foreach ($roles as $role)
            <div class="custom-control custom-checkbox">
                {{ Form::checkbox('roles[]', $role->id, false, ['class' => 'custom-control-input', 'id' => 'role' . $role->id]) }}
                {{ Form::label('role' . $role->id, __(ucfirst($role->name)), ['class' => 'custom-control-label ']) }}
            </div>
        @endforeach

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
