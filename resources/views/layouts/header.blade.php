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
					@if(Entrust::hasRole('admin'))
					<li class="dropdown">
				  		<a href="#" class="dropdown-toggle" data-toggle="dropdown">File Maintenane <span class="caret"></span></a>
				  		<ul class="dropdown-menu" role="menu">
				  			<li>{!! link_to_route('sostypes.index','SOS Type') !!}</li>
				  			<li>{!! link_to_route('enrollmenttypes.index','Enrollment Type') !!}</li>
				  			<li>{!! link_to_route('users.index','Users') !!}</li>
				  			<li>{!! link_to_route('templatemaintenance.index','Templates') !!}</li>
				  		</ul>
				  		
					</li>
					@endif
					@if(Entrust::hasRole('admin'))
					<li class="dropdown">
				  		<a href="#" class="dropdown-toggle" data-toggle="dropdown">Audits <span class="caret"></span></a>
				  		<ul class="dropdown-menu" role="menu">
				  			<li>{!! link_to_route('audits.index','Audit Month') !!}</li>
				  		</ul>

					</li>
					@endif
					<li class="dropdown">
				  		<a href="#" class="dropdown-toggle" data-toggle="dropdown">Reports <span class="caret"></span></a>
				  		<ul class="dropdown-menu" role="menu">
				  			<li>{!! link_to_route('auditreport.index','Posted Audit Report') !!}</li>
				  			<li>{!! link_to_route('usersummaryreport.index','User Summary Report') !!}</li>
				  			<li>{!! link_to_route('customerreport.index','Customer Summary Report') !!}</li>
				  			<li>{!! link_to_route('customerregionalreport.index','Customer Regional Summary Report') !!}</li>
				  			<li>{!! link_to_route('osareport.index','Per SKU OSA Report') !!}</li>
				  			<li>{!! link_to_route('npireport.index','Per SKU NPI Report') !!}</li>
				  			<li>{!! link_to_route('sosreport.index','SOS Report') !!}</li>
				  			<li>{!! link_to_route('customizedplanoreport.index','Customized Planogram Report') !!}</li>
				  			<li>{!! link_to_route('pjpreport.index','PJP Frequency Report') !!}</li>
				  			@if(Entrust::hasRole('admin'))
				  			<li>{!! link_to_route('deviceerror.index','Device Error Report') !!}</li>
				  			@endif
				  		</ul>
				  		
					</li>
					


					
			  	</ul>

			  	

			</div><!-- /.navbar-collapse -->
			<div class="navbar-custom-menu">
          <ul class="nav navbar-nav">

            <!-- User Account Menu -->
            <li class="dropdown user user-menu">
              <!-- Menu Toggle Button -->
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span class="hidden-xs">{{ ucwords(strtolower(Auth::user()->name)) }}</span>
              </a>
              <ul class="dropdown-menu">
                
                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-right">
                  	<a class="btn btn-default btn-flat" href="{{ url('/auth/logout') }}">Logout</a>
                  </div>
                </li>
              </ul>
            </li>
          </ul>
        </div>
	  	</div><!-- /.container-fluid -->
	</nav>
</header>