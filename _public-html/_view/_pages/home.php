<!-- CSS -->

<!-- JS -->
<script type="text/javascript" src="_view/_js/home.js"></script>

<div class="container margin-basic">
	<div class="row">
		<div class="col-md-12">
			<h1>Home</h1>
			<hr>
		</div>
	</div>
	<div class="row">
		<div class="col-md-5 tasks-open"></div>
		<div class="col-md-7 tasks-develop">
			<p draggable="true">
				<br>
			</p>
		</div>
		<div class="col-md-5 tasks-analyse"></div>
	</div>
	<div class="row">
		<div class="col-md-12 tasks-approval-disapproval">
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label for="exampleInputEmail1">My Reminders</label>
				<textarea id="textReminders" rows="3" placeholder="Enter reminders here" style="width: 100%"></textarea>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<label>Next commits</label>
			<table id="myTable" class="table table-bordered table-condensed table-striped">
				<thead>
					<tr>
						<th>#</th>
						<th>Task</th>
						<th>Files</th>
						<th>Date Update</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th colspan="5"></th>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<!-- Insert files modal -->
<div class="modal fade" id="modalFiles" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title">Set Testers</h4>
			</div>
			<div class="modal-body" id="modalTestText">
				<label>Describe modify files</label>
				<input class="form-control" id="describeFiles" placeholder="Enter describe" type="text">
				<input id="modalIdTask" type="hidden" value="">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="buttonSaveFiles">
					Save changes
				</button>
			</div>
		</div>
	</div>
</div>