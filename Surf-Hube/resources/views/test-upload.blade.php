<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test File Upload</title>
</head>
<body>
    <div style="max-width: 600px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
        <h1>Test File Upload</h1>
        
        <form action="/test-upload" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="margin-bottom: 20px;">
                <label for="test_file" style="display: block; margin-bottom: 5px;">Select a file:</label>
                <input type="file" name="test_file" id="test_file">
            </div>
            
            <button type="submit" style="background-color: #4CAF50; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer;">
                Upload File
            </button>
        </form>
    </div>
</body>
</html> 