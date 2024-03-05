Dear Admin,
<h1>Hi, {{ $name }} - {{ $email }} - {{ $phone }}</h1>
<h3>My Name Is - {{ $name }}</h3>
<p>Right Now I am Send my Personal Information With Attach File.</p>
<p>Please Checked My Details and Let Me Know Very Soon..</p>

<table border='1'>
    <tr>
        <td>Name:</td>
        <td>{{ $name }}</td>
    </tr>
    <tr>
        <td>Email:</td>
        <td>{{ $email }}</td>
    </tr>
    <tr>
        <td>Phone:</td>
        <td>{{ $phone }}</td>
    </tr>
</table>

<p>Sending Mail from {{ $name }}.</p> 

Best Regards
{{ $name }}
