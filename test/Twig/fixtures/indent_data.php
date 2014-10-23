// line 1
ob_start();
echo (isset($context["data"]) ? $context["data"] : null);
$lines = explode("\n", ob_get_clean());
foreach ($lines as $key => $line) {
    if (trim($line)) {
        echo $key > 0 ? '    ' : '' ;
        echo "$line";
    }
    echo "\n";
}
