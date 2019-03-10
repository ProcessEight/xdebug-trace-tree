<?php
error_reporting(E_ALL);
ini_set('display_errors', true);
?>
<!doctype html>
<html lang="en_gb">
<head>
    <title>XDebug Trace Tree</title>
    <link rel="stylesheet" href="res/style.css">
    <script src="https://code.jquery.com/jquery-2.2.1.min.js"></script>
    <script src="res/script.js"></script>
</head>
<body>
<form method="post" class="load">
    <label for="file">File:</label>
    <select name="file" id="file">
        <?php
        $dir = ini_get('xdebug.trace_output_dir');
        if (!$dir) {
            $dir = '/var/www/html/watson/watson-safari/xdebug-trace-files/';
        }
        $files = glob("$dir/*");
        foreach ($files as $file) {
            $checked = ($file == $_REQUEST['file']) ? 'selected="selected"' : '';
            echo '<option value="' . htmlspecialchars($file) . '" ' . $checked . '>' . htmlspecialchars(basename($file)) . '</option>';
        }
        ?>
    </select>
    <button type="submit">Load</button>
    <br/>
    <p>Reading files from <code><?= htmlspecialchars($dir) ?></code></p>
</form>
<ul class="help">
    <li>Load a trace file from the dropdown</li>
    <li>Click a left margin to collapse a whole sub tree</li>
    <li>Click a function name to collapse all calls to the same function</li>
    <li>Click the parameter list to expand it</li>
    <li>Click the return list to expand it</li>
    <li>Click the time to mark the line important</li>
    <li>Use checkboxes to hide all PHP internal functions or limit to important lines</li>
</ul>
<form class="options">
    <input type="checkbox" value="1" checked="checked" id="internal">
    <label for="internal">Show internal (PHP core) functions</label>

    <input type="checkbox" value="1" id="highlighted">
    <label for="highlighted">Show highlighted only (slow)</label>
</form>
<?php
$before = 0;
$after = 0;
if (!empty($_REQUEST['file'])) :
    $before = memory_get_usage();
    require_once 'res/XdebugParser.php';
    $parser = new XdebugParser($_REQUEST['file']);
    $parser->parse();
    echo $parser->getTraceHTML();
    $after = memory_get_usage();
endif;
?>
<p>BEFORE: <?= $before ?></p>
<p>AFTER: <?= $after ?></p>
<p>DIFF: <?= $after - $before ?></p>
</body>
</html>
