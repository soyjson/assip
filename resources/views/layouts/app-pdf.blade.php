<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta http-equiv="Content-Language" content="en">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>{{ env('APP_NAME') }}</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
	<meta name="description" content="{{ env('APP_DESCRIPTION') }}">
	<meta name="msapplication-tap-highlight" content="no">
	<style>
		body{font-size:85% !important}
	</style>
<body>
	<div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
		<div class="app-header header-shadow">
			<div class="app-main__outer">
				<div class="app-main__inner">
					@yield('content')
				</div>
			</div>
		</div>
	</div>
</body>

</html>