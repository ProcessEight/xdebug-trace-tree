<?php
error_reporting(E_ALL);
ini_set('display_errors', true);
?>
<?php
$dir = ini_get('xdebug.trace_output_dir') ?: '/var/www/html/process-eight-xdebug-trace-tree/xdebug-trace-files/';
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
        <p>Reading files from <code><?= htmlspecialchars($dir) ?></code></p>
        <form method="post" class="load">
            <p>
                <label for="file">File:</label>
                <select name="file" id="file">
                <?php
                $files = glob("$dir/*");
                foreach ($files as $file) :
                    $checked = ($file == $_REQUEST['file']) ? 'selected="selected"' : '';
                    echo '<option value="' . htmlspecialchars($file) . '" ' . $checked . '>' . htmlspecialchars(basename($file)) . '</option>';
                endforeach;
                ?>
                </select>
                <button type="submit">Load</button>
            </p>
        </form>
        <?php
        //$before = 0;
        //$after = 0;
        if (!empty($_REQUEST['file'])) :
        //    $before = memory_get_usage();
            require_once 'res/XdebugParser.php';
            $parser = new XdebugParser($_REQUEST['file']);
            $parser->parse();
            echo $parser->getTraceHTML();
        //    $after = memory_get_usage();
        endif;
        ?>
        <!--<p>BEFORE: --><? //= $before ?><!--</p>-->
        <!--<p>AFTER: --><? //= $after ?><!--</p>-->
        <!--<p>DIFF: --><? //= $after - $before ?><!--</p>-->
    </body>
</html>
