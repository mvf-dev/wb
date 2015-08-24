<!DOCTYPE html>
<html lang="en" ng-app="myApp">
<head>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	
<link rel="stylesheet" type="text/css" href="DataTables-1.10.7/media/css/jquery.dataTables.css">
  
<!-- jQuery -->
<script src="DataTables-1.10.7/media/js/jquery.js"></script>
  
<!-- DataTables -->
<script src="DataTables-1.10.7/media/js/jquery.dataTables.js"></script>
<script src="https://code.angularjs.org/1.4.0-rc.2/angular.min.js"></script>
<script src="app.js"></script>

<script>
	$(document).ready(function() {
    	$('#example').DataTable( {
    			"scrollX": true
    	});
    		
	} );
</script>
</head>
<body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Project name</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <form class="navbar-form navbar-right">
            <div class="form-group">
              <input type="text" placeholder="Email" class="form-control">
            </div>
            <div class="form-group">
              <input type="password" placeholder="Password" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Sign in</button>
          </form>
        </div><!--/.navbar-collapse -->
      </div>
    </nav>
<br>
<br>
<br>
<br>
<div>
				
	<table id="example" class="display" cellspacing="0" width="70%">

					<thead>
						<tr>
							<th>Encode House</th>
							<th>Provider Name</th>
							<th>Provider Short Name</th>
							<th>User Name</th>
							<th>Password</th>
							<th>Transporter Proto Script</th>
							<th>Source Path</th>
							<th>Transport Path</th>
							<th>Archive Path</th>
							<th>Failure Path</th>
							<th>Log Path</th>
							<th>Summary Path</th>
							<th>Edit</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th>Encode House</th>
							<th>Provider Name</th>
							<th>Provider Short Name</th>
							<th>User Name</th>
							<th>Password</th>
							<th>Transporter Proto Script</th>
							<th>Source Path</th>
							<th>Transport Path</th>
							<th>Archive Path</th>
							<th>Failure Path</th>
							<th>Log Path</th>
							<th>Summary Path</th>
						</tr>
					</tfoot>
 
			        <tbody>
			         <tr ng-repeat="provider in providers">
			         	<td>{{provider.encode_house}}</td>
			         </tr>
			        </tbody>
			
				</table>
			
				<div controller="mainController">
					<input type="text" ng-model="handle" />
					<br>
					<h1>twitter.com/{{handle}}</h1>

				</div>
</body>
</html>
