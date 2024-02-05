@php
    use Carbon\Carbon;
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <div class="container">
        ###<br>
        Great news, a new backup of Afriregister (production) was successfully created on the disk named local<br>

        Backup name: Paradise-Backup<br>

        Disk: local<br> 

        Newest backup date: {{ Carbon::now()->format('Y-d-m-h') }}

    </div>

</body>

</html>
