<ul class="nav nav-tabs">
    <li class="{{ (Request::segment(3) == 'stores') ? 'active':''}}"><a href="{{ url('audits/'.$audit->id.'/stores') }}" aria-expanded="true">Store Lists</a></li>
    <li class="{{ (Request::segment(3) == 'templates') ? 'active':''}}"><a href="{{ url('audits/'.$audit->id.'/templates') }}" aria-expanded="false">Audit Templates</a></li>
    <li class="{{ (Request::segment(3) == 'users') ? 'active':''}}"><a href="{{ url('audits/'.$audit->id.'/users') }}" aria-expanded="false">Users</a></li>
    <li class="{{ (Request::segment(3) == 'categories') ? 'active':''}}"><a href="{{ url('audits/'.$audit->id.'/categories') }}" aria-expanded="false">Audit Categories</a></li>
    <li class="{{ (Request::segment(3) == 'groups') ? 'active':''}}"><a href="{{ url('audits/'.$audit->id.'/groups') }}" aria-expanded="false">Audit Groups</a></li>
    <li class="{{ (Request::segment(3) == 'secondarydisplay') ? 'active':''}}"><a href="{{ url('audits/'.$audit->id.'/secondarydisplay') }}" aria-expanded="false">Secondary Display</a></li>
    <li class="{{ (Request::segment(3) == 'osatargets') ? 'active':''}}"><a href="{{ url('audits/'.$audit->id.'/osatargets') }}" aria-expanded="false">OSA Target</a></li>
    <li class="{{ (Request::segment(3) == 'sostargets') ? 'active':''}}"><a href="{{ url('audits/'.$audit->id.'/sostargets') }}" aria-expanded="false">SOS Target</a></li>
    <li class="{{ (Request::segment(3) == 'enrollments') ? 'active':''}}"><a href="{{ url('audits/'.$audit->id.'/enrollments') }}" aria-expanded="false">Enrollment Type</a></li>
</ul>