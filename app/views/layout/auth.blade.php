<!DOCTYPE html>

<html class="full" lang="en"><!-- The full page image background will only work if the html has the custom class set to it! Don't delete it! -->

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dixie Philamerah Atay">

    <title>Remuneration</title>

    <!-- Custom CSS for the 'Full' Template -->
    {{ Asset::container('header')->styles() }}
  </head>

  <body>

    <div class="container">
      <div class="row">
		<div class="col-lg-7 col-sm-7 blob">
			<h1>The Next Big Thing in the Cloud</h1>
		</div>

		<div class="col-lg-5 pull-right">
			<div class="main well">
				@yield('content')
			</div>
		</div>
      </div>
    </div>

    <!-- JavaScript -->
    {{ Asset::container('footer')->scripts() }}

  </body>

</html>
