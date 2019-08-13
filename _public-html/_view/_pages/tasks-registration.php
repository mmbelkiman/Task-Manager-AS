<!-- CSS -->
<link href="_libs/_css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<!-- JS -->
<script type="text/javascript" src="_libs/_js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="_view/_js/tasks-registration.js"></script>

<div class="container margin-basic">

	<div class="container">
		
		<div class="row">
			<div class="col-md-12">
				<h1>Task Registration</h1>
				<hr>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-12">

				<div class="form-group">
					<label>Name</label>
					<input class="form-control validate-field" id="taskName" placeholder="Task Name" type="text">
				</div>

				<div class="form-group">
					<label>Description</label>
					<textarea id="taskDescription" rows="3" placeholder="Enter a short description" style="width: 100%"></textarea>										
				</div>

				<div class="form-group">
					<label>Main Job</label>
					<br>
					<div>
						<select id="selectJobs" class="selectpicker align-left">
						</select>
					</div>
				</div>

				<div class="form-group">
					<label>Time expected</label>

					<div id="datetimepicker3" class="input-append">
						<input data-format="hh:mm" type="text" maxlength="5" class="validate-field" id="taskDatetime">
						</input>
						<span class="add-on"> <i data-time-icon="glyphicon glyphicon-pencil"> </i> </span>
					</div>
				</div>

				<button type="submit" class="btn btn-default" id="btnSubmit">
					Submit
				</button>
				
			</div>
		</div>
	</div>
</div>