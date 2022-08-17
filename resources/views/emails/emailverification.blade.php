<h1 style="font-size: 14px;">Hi {{ $details['toname'] }}</h1>
<p>Please verify your email using this code: {{ $details['code'] }}</p>
<p><a href="{{ route('user.verify', $details['token']) }}">Click here</a> to enter your code.</p>
<p>Best regards,<br/>
DesignsOwl</p>