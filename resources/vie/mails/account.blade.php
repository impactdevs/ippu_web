<!DOCTYPE html>
<html>
<head>
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap');

body {
  font-family: 'Poppins', sans-serif;
}
</style>
</head>
<body>

<p>Dear {{ $user->name }},</p>

<p>Your account is now {{ $user->status }}</p>

@if($user->comment)
<p>Comment</p>
{{ $user->comment }}
@endif
<p>Thank You</p>

</body>
</html>
