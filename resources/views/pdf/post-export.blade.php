<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .meta {
            color: #7f8c8d;
            font-size: 14px;
            margin-bottom: 30px;
        }
        .content {
            font-size: 14px;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ecf0f1;
            color: #95a5a6;
            font-size: 12px;
            text-align: center;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin: 20px 0;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>{{ $post->title }}</h1>

    <div class="meta">
        @if ($post->user)
            <p><strong>Author:</strong> {{ $post->user->name }}</p>
        @endif

        @if ($post->published_at)
            <p><strong>Published:</strong> {{ $post->published_at->format('F j, Y') }}</p>
        @endif

        @if ($post->sourceFile)
            <p><strong>Source File:</strong> {{ $post->sourceFile->filename }}</p>
        @endif
    </div>

    <div class="content">
        @if ($post->excerpt)
            <p><em>{{ $post->excerpt }}</em></p>
        @endif

        {!! nl2br(e($post->content)) !!}
    </div>

    <div class="footer">
        <p>Generated on {{ now()->format('F j, Y \a\t g:i A') }}</p>
    </div>
</body>
</html>
