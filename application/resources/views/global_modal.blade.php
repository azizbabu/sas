<!-- GLOBAL ALERT MODAL -->
<div id="global-alert-modal" class="modal fade">
	<div class="modal-dialog modal-md">
		{!! Form::open(['id'=>'globalAlertFrm']) !!}
		<div class="modal-content">
			<!-- header modal -->
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Are You Sure?</h4>
			</div>
			<!-- body modal -->
			<div class="modal-body"></div>
			<!-- footer modal -->
			<div class="modal-footer">
				{!! Form::hidden('hdnResource') !!}
				<button type="button" class="btn btn-sm btn-default" data-dismiss="modal">No</button>
				<button type="submit" class="btn btn-sm btn-danger">Yes</button>
			</div>
		</div>
		{!! Form::close() !!}
	</div>
</div>
<!-- GLOBAL ALERT MODAL END -->