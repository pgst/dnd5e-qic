<!doctype html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>問題文CSVファイルアップロード</title>
</head>

<body>
    <form action="/import-csv" method="post" enctype="multipart/form-data">
        @csrf
        
        <input type="file" name="file">
        <button type="submit">インポート</button>
    </form>
</body>

</html>