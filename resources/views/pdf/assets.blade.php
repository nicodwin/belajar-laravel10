<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <h1>Data Assets</h1>
    <hr>

    <table class="table table-hover text-nowrap" style="width: 100%" border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>NIK</th>
                <th>Nama</th>
                <th>Asset</th>
                <th>Jumlah Asset</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $d)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $d->ktp->nik ?? '' }}</td>
                    <td>{{ $d->name }}</td>
                    <td>
                        <ul>
                            @foreach ($d->assets as $asset)
                                <li>{{ $asset->nama_asset }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>{{ count($d->assets) }}</td>
            @endforeach
        </tbody>
    </table>
</body>

</html>
