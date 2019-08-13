<!-- CSS -->

<!-- JS -->
<script type="text/javascript" src="_view/_js/tasks-show.js"></script>

<div class="container margin-basic">
	<div class="row">
		<div class="col-md-12">
			<h1>Tasks</h1>
			<hr>

			<select id="selectTaskStatus" class="selectpicker align-right"></select>

			<label class="align-right">&nbsp; Only tasks I Develop &nbsp; || &nbsp;</label>
			<input class="align-right" type="checkbox" name="checkTasks" id="checkTasks" value="myTasks" >
			<label class="align-right">&nbsp; Only tasks I Request &nbsp; || &nbsp;</label>
			<input class="align-right" type="checkbox" name="checkTasksRequest" id="checkTasksRequest" value="myTasksRequest" >

			<br/>
			<br/>
			<table id="myTable" class="table table-bordered table-condensed table-striped">
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						<th>Description</th>
						<th>Job Type</th>
						<th>Date Creation</th>
						<th>Date Last-Update</th>
						<th>User Request</th>
						<th>User Developing</th>
						<th>Time Expected/Used</th>
						<th>Status</th>
						<th>Functions</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th colspan="11"></th>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

<!-- Modals control -->
<!-- Transfer to another user -->
<div class="modal fade" id="modalTransfer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title" id="myModalLabel">Transfer task to another user</h4>
			</div>
			<div class="modal-body" id="modalTransferText">
				...
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">
					Cancel
				</button>
				<button type="button" class="btn btn-primary" id="buttonSaveChanges">
					Save changes
				</button>
			</div>
		</div>
	</div>
</div>
<!-- Edit Testers -->
<div class="modal fade" id="modalTester" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title">Set Testers</h4>
			</div>
			<div class="modal-body" id="modalTestText">
				...
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">
					Cancel
				</button>
				<button type="button" class="btn btn-primary" id="buttonSaveChangesTester">
					Save changes
				</button>
			</div>
		</div>
	</div>
</div>
<!-- END Modals control -->
