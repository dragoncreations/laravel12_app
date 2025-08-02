<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Notification</title>
</head>

<body>
    <div class="container">
        <p><strong>Task #:</strong> {{ $task->id }}</p>
        <p><strong>Task name:</strong> {{ $task->name }}</p>
        <p><strong>Task description:</strong> {{ $task->description }}</p>
        <div class="footer">
            &copy; {{ date('Y') }} Your Company. All rights reserved.
        </div>
    </div>
</body>

</html>