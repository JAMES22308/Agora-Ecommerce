<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple PHP Example</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f4f4f9; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); max-width: 400px; }
        h1 { color: #333; }
        ul { padding-left: 20px; }
    </style>
</head>
<body>

    <div class="card">
        <?php
        // 1. Define variables
        $project_name = "Agora E-Commerce";
        $current_hour = (int)date('G'); // Gets 24-hour format of current time
        
        // 2. Conditional Statement (Greeting based on time)
        if ($current_hour < 12) {
            $greeting = "Good morning";
        } elseif ($current_hour < 18) {
            $greeting = "Good afternoon";
        } else {
            $greeting = "Good evening";
        }
        
        // 3. Output HTML using echo
        echo "<h1>{$greeting}!</h1>";
        echo "<p>Welcome back to the <strong>{$project_name}</strong> development server.</p>";
        
        // 4. Array and Loop
        $todo_list = ["Fix Git remote tracking", "Create database connection", "Build login page"];
        
        echo "<h3>Today's Tasks:</h3>";
        echo "<ul>";
        foreach ($todo_list as $task) {
            echo "<li>" . htmlspecialchars($task) . "</li>";
        }
        echo "</ul>";
        ?>
        
        <p style="font-size: 0.8em; color: #666;">
            Server time: <?php echo date('Y-m-d H:i:s'); ?>
        </p>
    </div>

</body>
</html>
