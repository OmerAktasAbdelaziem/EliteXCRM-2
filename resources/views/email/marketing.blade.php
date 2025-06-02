<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700;800&display=swap" rel="stylesheet">
    {{-- textEditor --}}
    <link rel="stylesheet" href="{{ url('assets/plugins/external/sample/css/sample.css?v2.944') }}">
    <link rel="stylesheet" href="{{ url('assets/plugins/external/dist/css/suneditor.min.css?v2.944') }}">
    <style>
        * {
            font-family: 'Almarai', sans-serif;
        }
    </style>
</head>
<body>
    <div class="sun-editor sun-editor-editable">
        {!! $data['body'] !!}
    </div>

    <div class="email-footer">
        &copy; 2024 {{$data['company_name']}}. All rights reserved.
    </div>
</body>
</html>
