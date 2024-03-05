Dear {{ $name }},
<h3>You'r Registration is SuccessFully</h3>
<p>Here Details Bellow:</p>
<table border='1'>
    <tr>
        <td>ID:</td>
        <td>{{ $employee_id }}</td>
    </tr>
    <tr>
        <td>Name:</td>
        <td>{{ $name }}</td>
    </tr>
    <tr>
        <td>Email:</td>
        <td>{{ $email }}</td>
    </tr>
</table>

<h1>Account Activation Url:</h1>
<p>Please Clicked Bellow Link &  Set Your Password For Activated Account.Other Wise You are not access your account.</p>
<p>{{ $url }}</p> 

<p><b style="color:red">Note:</b>If You Have Face Any Problem Activated Your Account.Please Don't Be Hesitant And Contact Your Admin.</p>

Best Regards
Admin
