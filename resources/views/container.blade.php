<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toggle Container</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container-wrapper {
            border: 1px solid #000;
            margin-bottom: 10px;
            background-color: #b3d9ff;
        }
        .container-header {
            display: flex;
            padding: 10px;
            cursor: pointer;
        }
        .text {
            flex: 1;
            font-size: 1.25rem;
        }
        .expandable-content {
            overflow: hidden;
            transition: max-height 0.5s ease-out, padding 0.5s ease-out;
            max-height: 0;
            background-color: #e6f2ff;
            padding: 0 10px;
            font-size: 1rem;
        }
        .expanded {
            padding-top: 10px;
        }
        .dropdown-container {
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        @foreach($items as $name => $group)
            {{-- Main container for each name --}}
            <div class="container-wrapper" onclick="toggleExpand('{{ $name }}')">
                <div class="container-header">
                    <div class="text">{{ $name }}</div>
                </div>
                <div id="expandableContent{{ $name }}" class="expandable-content">
                    {{-- List all items with this name --}}
                    <ul class="list-unstyled">
                        @foreach($group as $item)
                            <li>
                                <div class="text">{{ $item->code }}: {{ $item->name }}</div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach
    </div>

    <script>
        function toggleExpand(name) {
            const content = document.getElementById('expandableContent' + name);
            if (content.classList.contains('expanded')) {
                content.style.maxHeight = '0';
                content.style.paddingTop = '0';
                setTimeout(() => content.classList.remove('expanded'), 500);
            } else {
                content.classList.add('expanded');
                content.style.maxHeight = content.scrollHeight + 'px';
                content.style.paddingTop = '10px';
            }
        }
    </script>
</body>
</html>
