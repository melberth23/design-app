<h1>Email Verification</h1>
  
<p>Please verify your email using this code: {{ $details['code'] }}</p>
<p><a href="{{ route('user.verify', $details['token']) }}">Click here</a> to enter your code.</p>