@if( Auth::user()->level == 5 ) 
<div id="" class="navbar navbar-default" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">eSports Management System</a>
            </div>
            <div id="navbar-main" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li><a href="{{ URL::route('adminDashboard') }}">Admin Dashboard</a></li>  
                    <li class="active"><a href="{{ URL::route('index') }}">Home</a></li>
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <li><a href="{{ URL::route('player-profile', Auth::user()->userID) }}">{{Auth::user()->username }}</a></li>
                    <li><a href="{{ URL::route('playerSettingsView') }}">Settings</a></li>
                    <li><a href="{{URL::route('signout')}}">Sign out</a></li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>
@endif