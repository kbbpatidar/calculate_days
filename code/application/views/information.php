<div class="container">
	<!-- Main jumbotron for a primary information for user on site/page -->
	<div class="jumbotron">
		<div class="container">&nbsp;</div>
	</div>
	
	<!-- Example row of columns -->
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 custom-container margin-t-b-10 padding-top-20">
			<p>In this example of calculating days without using PHP existing Date/Time functions or classes between two given dates from user, 
			I have tried to give user an easy way(UI) to select dates(datepicker) and give result without refereshing the page using AJAX call.
			</p>
			
			<p>To demonstrate logic, I tried with mulitple method/technique in code. See Datediff.php (application/controllers/Datediff.php) for more details on the code</p>
			<hr/>
			<h4>Tools and technology used: </h4>
			<pre><strong>Codeigniter 3</strong> has been used to demonstrate an MVC website.</pre>
			<pre><strong>jQuery and jQuery UI</strong> has been used for giving option for user to select date and restrict them by making mistake.</pre>
			<pre><strong>Bootstrap 3</strong> has been used for better UI and support responsive design along with some custom css.</pre>
			<pre><strong>AJAX</strong> has been used to maintain user experiance and do not reload whole page.</pre>
			
			<pre><strong>Option to Include Day and Invert</strong> - Give user ability to include end date in calculation. 
Also when user by mistake/intesionally select start date <i>greater then</i> end date, give user proper message with appropriate result.</pre>
			
			<p>Also, to resolve this issue we can store dates in database and use mysql DATEDIFF function.</p>
			<code><strong>Query:</strong> SELECT DATEDIFF('2016-05-30','2016-05-27') AS DiffDate</code>	
			<code><strong>Ans: 3</strong></code>
			<p><br>This way we can also get desired result without using PHP by default Date/Time functions.</p>
			<hr/>	
			<p>
				Contact me @
				<code>kbbpatidar@gmail.com</code>
				- or -
				<code>+1-780-716-2040</code>
			</p>
			
		</div>
	</div>
</div>
