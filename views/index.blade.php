<!DOCTYPE html>
<html>
<head>
</head>

<body>
<div>
    {!! $true ? 'Bloop' : 'Hello World!' !!}
</div>
<div>
    {!! $true ? 'Bloop' : 'Hello World!' !!}
</div>
<div>
    {!! $true ? 'Bloop' : 'Hello World!' !!}
</div>
<div>
    {!! $true ? 'Bloop' : 'Hello World!' !!}
</div>
<div>
    {!! view('text', ['true' => $true]) !!}
</div>
<?php
//    $connection = queryHelperCustom();
//    $results    = $connection->query('SELECT * FROM information_schema.tables');
//    echo array_to_table($results);
?>
</body>
</html>