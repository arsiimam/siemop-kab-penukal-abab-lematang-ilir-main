{!! Form::model($user, [
    'method' => 'PUT',
    'route' => ['users.update', $user->id],
]) !!}
<div class="modal-body">

    <div class="container-fluid">
        <div class="row">
            <div class="form-group col-md-6">
                <label>{{ __('Nama Pengguna') }}</label>
                {{ Form::text('name', old('name'), ['class' => 'form-control', 'required']) }}
            </div>
            <div class="form-group col-md-6">
                <label>{{ __('Email') }}</label>
                {{ Form::text('email', old('email'), ['class' => 'form-control', 'required']) }}
            </div>
            <div class="form-group col-md-6">
                <label>{{ __('Username') }}</label>
                {{ Form::text('username', old('username'), ['class' => 'form-control', 'required']) }}
            </div>
            <div class="form-group col-md-6">
                <label>{{ __('No. Telp') }}</label>
                {{ Form::text('phone_number', old('username'), ['class' => 'form-control', 'required']) }}
            </div>
            <div class="form-group col-md-6">
                <label>{{ __('Role') }}</label>
                {{ Form::select('role_id', $roles, old('role_id'), ['class' => 'form-control  select2-modal', 'placeholder' => __('Select')]) }}
            </div>
            <div class="form-group col-md-6">
                <label>{{ __('Instansi') }}</label>
                {{ Form::select('institute_id', $institute, old('institute'), ['class' => 'form-control  select2-modal', 'placeholder' => __('Select')]) }}
            </div>
            <div class="form-group col-md-6">
                <label>{{ __('Password') }}</label>
                {{ Form::password('password', ['class' => 'form-control']) }}
            </div>
            <div class="form-group col-md-6">
                <label>{{ __('Konfirmasi Password') }}</label>
                {{ Form::password('confirm-password', ['class' => 'form-control']) }}
            </div>
            <div class="form-group col-md-6">
                <label>{{ __('Status') }}</label>
                <select class="form-control select2-modal" name="is_active" data-width="100%">
                    <option selected></option>
                    <option value="1" {{ $user->is_active == 1 ? 'selected' : '' }}>{{ __('Active') }}</option>
                    <option value="0" {{ $user->is_active == 0 ? 'selected' : '' }}>{{ __('InActive') }}</option>
                </select>
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
