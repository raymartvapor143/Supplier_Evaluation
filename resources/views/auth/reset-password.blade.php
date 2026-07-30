<!DOCTYPE html>
<html>

<head>

<title>Create New Password</title>

<script src="{{asset('script/tailwind.js')}}"></script>

</head>


<body class="bg-gray-100 flex items-center justify-center min-h-screen">


<div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md">


<h2 class="text-2xl font-bold mb-5">
Create New Password
</h2>


<form method="POST" action="{{ route('password.update') }}">

@csrf

<input type="hidden"
name="token"
value="{{ $token }}">

<input type="hidden"
name="email"
value="{{ request('email') }}">


<input type="password"
name="password"
placeholder="New Password"
class="w-full border p-3 rounded-lg mb-4"
required>


<input type="password"
name="password_confirmation"
placeholder="Confirm Password"
class="w-full border p-3 rounded-lg mb-4"
required>


<button
class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold">
Change Password
</button>

</form>


</div>


</body>
</html>