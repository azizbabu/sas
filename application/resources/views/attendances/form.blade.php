<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="name" class="control-label">Name {!! validation_error($errors->first('name'),'name') !!}</label>
            {!! Form::text('name', null, ['class'=>'form-control', 'placeholder' => 'Name']) !!}
        </div>
        <div class="form-group">
            <label for="father_name" class="control-label">Father's Name </label>
            {!! Form::text('father_name', null, ['class'=>'form-control', 'placeholder' => 'Father\'s Name']) !!}
        </div>
        <div class="form-group">
            <label for="mother_name" class="control-label">Mother's Name </label>
            {!! Form::text('mother_name', null, ['class'=>'form-control', 'placeholder' => 'Mother\'s Name']) !!}
        </div>
        <div class="form-group">
            <label for="class" class="control-label">Class {!! validation_error($errors->first('class'),'class') !!}</label>
            {!! Form::select('class', config('constants.classes'), null, ['class'=>'form-control chosen-select', 'placeholder' => 'Select a Class']) !!}
        </div>
        <div class="form-group">
            <label for="class" class="control-label">Section {!! validation_error($errors->first('section'),'section') !!}</label>
            {!! Form::select('section', config('constants.sections'), null, ['class'=>'form-control chosen-select', 'placeholder' => 'Select a section']) !!}
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-group">
            <label for="roll" class="control-label">Roll {!! validation_error($errors->first('roll'),'roll') !!}</label>
            {!! Form::number('roll', null, ['class'=>'form-control', 'placeholder' => 'Roll']) !!}
        </div>
        <div class="form-group">
            <label class="control-label">Gender </label> <br>
            <label class="radio-inline">
                {!! Form::radio('gender', 'male', !empty($student->gender) && $student->gender=='male' ? true : (old('gender') ? old('gender') : true)) !!} Male
            </label>
            <label class="radio-inline">
                {!! Form::radio('gender', 'female', old('gender')) !!} Female
            </label>
        </div>
        <div class="form-group">
            <label for="admission_date" class="control-label">Admission Date </label>
            {!! Form::text('admission_date', null, ['class'=>'form-control datepicker', 'placeholder' => 'yyyy-mm-dd']) !!}
        </div>
        <div class="form-group">
            <label for="photo" class="control-label">Photo {!! validation_error($errors->first('photo'),' photo', true) !!}</label> <br>
            <div class="fileinput {{empty($student->photo) ? 'fileinput-new':'fileinput-exists'}}" data-provides="fileinput">
               <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                  <img alt="Student Photo" src="{{ $assets . '/images/avatar/profile.svg' }}">
               </div>
               <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
                  @if(!empty($student->photo) && @getimagesize(url($student->photo)))
                    <img src="{{url($student->photo)}}" alt="Student photo">
                  @endif
               </div>
               <div>
                  <span class="btn btn-default btn-file">
                  <span class="fileinput-new">Select photo</span>
                  <span class="fileinput-exists">Change</span>
                  <input type="file" name="photo" id='photo'>
                  </span>
                  <a href="#" class="btn btn-default btn-remove fileinput-exists" data-dismiss="fileinput">Remove</a>
               </div>
            </div><!-- end fileinput -->
        </div>
    </div>
</div>

@section('custom-style')
{{-- Bootstrap Datepicker --}}
{!! Html::style($assets.'/plugins/datepicker/datepicker3.css') !!}
{{-- Jasny Bootstrap --}}
{!! Html::style($assets. '/plugins/jasny-bootstrap/jasny-bootstrap.min.css') !!}
@endsection

@section('custom-script')
{{-- Bootstrap Datepicker --}}
{!! Html::script($assets. '/plugins/datepicker/bootstrap-datepicker.js') !!}
{{-- Jasny Bootstrap --}}
{!! Html::script($assets. '/plugins/jasny-bootstrap/jasny-bootstrap.min.js') !!}
<script>
(function() {
    $('.btn-remove').on('click', function() {
        $('input[name=file_remove]').val(true);
    });
})();
</script>
@endsection


