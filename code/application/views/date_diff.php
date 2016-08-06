<div class="container">
	<!-- Main jumbotron for a primary information for user on site/page -->
	<div class="jumbotron">
		<div class="container padding-top-20">
			<h2>Calculate days between two dates</h2>
			<p>This calculator calculates the number of days between two dates.</p>
		</div>
	</div>

	<div class="container">
		<!-- Example row of columns -->
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 custom-container">
				<div class="jsError alert alert-danger margin-t-b-10 hide fade in" role="alert" ></div>
				<?php echo form_open('datediff/calculate', array('class'=>'jsform')); ?>
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
						<h3>Start Date</h3>
						<div>
							<input type="text" id="start_date" class="datepicker" name="start_date" required="required"  readonly="readonly" placeholder="YYYY-MM-DD">
								<span class="calender-icon"><i class="glyphicon glyphicon-calendar"></i></span>
							<input type="text" id="start_alternate" size="30" disabled="disabled" class="alternate-field">
						</div>
					</div>
	
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
						<h3>End Date</h3>
						<div>
							<input type="text" id="end_date" class="datepicker" name="end_date" required="required"  readonly="readonly" placeholder="YYYY-MM-DD">
								<span class="calender-icon"><i class="glyphicon glyphicon-calendar"></i></span>
							<input type="text" id="end_alternate" size="30" disabled="disabled" class="alternate-field">
						</div>
					</div>
					
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-3 alert alert-warning hide margin-t-b-10" role="alert" 
						 id="invert-warning">
						<strong>Warning! </strong>
						<p>The Start date was after the End date, so the Start and End fields were swapped for calculation.</p>
					</div>
	
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 margin-t-b-10">
						<div>
							<input type="checkbox" name="include_day" id="include_day" value="1">
							<label for="include_day">Include end date in calculation</label>
						</div>
					</div>
					
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 margin-t-b-10">
						<input type="submit" class="btn btn-success custom-btn-effect" id="calculate" value="Calculate" name="Calculate duration"/>
					</div>
				<?php echo form_close(); ?>	
				
			</div>

		</div>
	</div>
	
	<div class="container padding-top-20">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-success hide font-size-20" role="alert" id="result">	
				<!-- Content will be set from custom.js // LN: #67 -->
			</div>
		</div>
	</div>
</div>
