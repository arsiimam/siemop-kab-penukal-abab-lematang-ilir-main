{!! Form::model($permission, [
    'method' => 'PUT',
    'route' => ['permissions.update', $permission->id],
    'class' => 'needs-validation tooltip-label-right was-validated',
]) !!}
<div class="modal-body">

    <div class="form-group position-relative error-l-50">
        <label>{{ __('Name') }}</label>
        {{-- <input type="text" name="name" class="form-control form-edit-name" placeholder="" required> --}}
        {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Permission Name'), 'required']) }}
        <div class="invalid-tooltip">
            {{ __('Name is required!') }}
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
