<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>{{ $websiteName }}</title>
	<style>
	
	
	::selection{
		background-color:#EE3C3A;
		color:white;
	}
	*{
		box-sizing:border-box;
		margin:0;
		padding:0;
		  font-family: Arial, Helvetica, sans-serif;
		line-height:1.4;
		
	}
	.container{
		padding:30px;
		max-width:600px;
		margin:0 auto;
	}
	.spacer{
				margin-top:20px;
		margin-bottom:20px;
	}
		.lg-spacer{
		margin-bottom:40px;
		margin-top:40px;
	}
	.main-header {

		font-weight:light;
		color:black;
	}
	.description{
		color:#6F6F6F !important;
	}
	.main-color,
	.ii a[href]
	{
		color:#EE3C3A !important;
	}
	.bg-main{
		background-color:#EE3C3A;
	}
	a.link{
		text-decoration:none;
		display:inline-block;
		color:white;
		padding:1rem 3rem;
		color:white !important;
		transition:0.5s all ;
			
	}
	.rounded{
		border-radius:40px;
	}
	.link:hover{
		background-color:#ec0503;
		
	}
	.text-center{
		text-align:center;
	}
	</style>
</head>
<body class="">
	<div class="container">
	    <div class="text-center">
		<img src="{{ $logo }}" title="{{ $websiteName }}" >
		</div>
		<h1 class="spacer main-header">Welcome To {{ $websiteName }}</h1>
		<p class=" description">We're excited to have you get started. First , you need to confirm your account. just use the follwing code </p>
		<p class="main-color"> <u>{{ $verificationCode }}</u> </p>
		{{-- <a class="link bg-main rounded lg-spacer" href="{{ $verificationUrl }}">Confirm Account</a> --}}
		{{-- <p class=" description">if the does not work , copy and paste the following link in your browser</p> --}}
		{{-- <a href="{{ $verificationUrl }}" class="main-color" >{{ $verificationUrl }}</a> --}}
		<p class="spacer description">if you have any question, just email us at <a href="mailto:{{ $email }}" style="color:#5680BD !important;">{{ $email }}</a> - we're alawys happy to help out  </p>
		<p class="description">Regards, <br> {{ $website }} Team </p>
	</div>
</body>
</html>
