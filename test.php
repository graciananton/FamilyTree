<form method="get">
  <label for="name">Name:</label>
  <input type="text" name="name" id="name" required>
  <input type="submit" value="Submit">
</form>
<?php
$name = isset($_GET['name']) ? escapeshellarg($_GET['name']) : '';
$output = shell_exec("python3 /kunden/homepages/3/d1017242952/htdocs/projects/python/test.py $name 2>&1");
echo "<pre>$output</pre>";
?>