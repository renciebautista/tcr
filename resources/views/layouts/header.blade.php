<header class="main-header">
	<nav class="navbar navbar-static-top">
	  	<div class="container">
			<div class="navbar-header">
				{!! link_to_route('dashboard.index','Trade Check Report', array(), ['class' => 'navbar-brand']) !!}
			  	<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
					<i class="fa fa-bars"></i>
			  	</button>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse pull-left" id="navbar-collapse">
			  	<ul class="nav navbar-nav">
					<!-- <li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li> -->
					<!-- <li><a href="#">Link</a></li> -->

					<li class="dropdown">
				  		<a href="#" class="dropdown-toggle" data-toggle="dropdown">File Maintenane <span class="caret"></span></a>
				  		<ul class="dropdown-menu" role="menu">
				  			<li>{!! link_to_route('sostypes.index','SOS Type') !!}</li>
				  			<li>{!! link_to_route('enrollmenttypes.index','Enrollment Type') !!}</li>
				  		</ul>
				  		
					</li>

					<li class="dropdown">
				  		<a href="#" class="dropdown-toggle" data-toggle="dropdown">Audits <span class="caret"></span></a>
				  		<ul class="dropdown-menu" role="menu">
				  			<li>{!! link_to_route('audits.index','Audit List') !!}</li>
				  		</ul>

					</li>

					<li class="dropdown">
				  		<a href="#" class="dropdown-toggle" data-toggle="dropdown">Reports <span class="caret"></span></a>
				  		<ul class="dropdown-menu" role="menu">
				  			<li>{!! link_to_route('auditreport.index','Posted Audit Report') !!}</li>
				  			<li>{!! link_to_route('usersummaryreport.index','User Summary Report') !!}</li>
				  		</ul>
				  		
					</li>
					

					
			  	</ul>
			</div><!-- /.navbar-collapse -->
	  	</div><!-- /.container-fluid -->
	</nav>
</header>