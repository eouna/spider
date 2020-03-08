<?php
set_time_limit(0);
ob_end_clean();
require_once "fetchSpider.class.php";
$str = "http://www.cartier.cn/";
$url = isset($_GET['url']) ? $_GET['url'] : $str;
$startPage = isset($_GET['start']) ? (int)$_GET['start'] : 1;
$ch = new lib\spider\spider();
$urls = isset($_GET['urls']) ? $_GET['urls'] : '';
$total = (int)(isset($_GET['total']) ? $_GET['total'] : 0) + $startPage;
$rate = (int)(isset($_GET['total']) ? $_GET['total'] : 0) / 200;
$current = 0;
$imgSize = isset($_GET['imgSize']) ? (int)$_GET['imgSize'] : 30;
$suffix = strlen(isset($_GET['suffix']) ? $_GET['suffix'] : '') != 0 ? "." . $_GET['suffix'] : "";
flush();
//$ch->saveHtml($url);

?>
<!DOCTYPE html>
<html>
<head>
    <title>图片抓取</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body, div input {
            font-family: Tahoma;
            font-size: 9pt
        }
    </style>

    <script language="JavaScript">
        function updateProgress(iWidth) {
            document.getElementById("schedule").style.width = iWidth + "px";
            document.getElementById("percent").innerHTML = parseInt(iWidth / 200 * 100) + "%";
        }

        function currentProgress(str) {
            document.getElementById("pername").innerHTML = str;
        }
    </script>
</head>
<form method="GET" action="./test.php" style="text-align: center;">
    <input type="text" name="url" style="outline: none;">
    <input type="submit" value="获取图片">
    <br>
    <input type="text" name="urls" style="outline: none;">
    <input type="text" name="start" style="outline: none;" placeholder="起始页">
    <input type="text" name="total" style="outline: none;" placeholder="总页数">
    <input type="text" name="suffix" style="outline: none;" placeholder="后缀（可选">
    <input type="text" name="imgSize" style="outline: none;" placeholder="图片大小（可选）">
    <br>
    <input type="submit" value="批量获取图片">
</form>

<div id="progress" style="width: 200px;height: 20px;text-align: center;border:2px solid #666;margin: 10px auto;">
    <div id="schedule" style="width: 0px;height: 20px;background-color: red"></div>
</div>
<div id="percent" style="position: relative;text-align: center; font-weight: bold; font-size: 8pt">0%</div>
<div id="pername" style="position: relative;text-align: center; font-weight: bold; font-size: 9pt"></div>
<?php
flush();
if (strlen($_GET['urls']) >= 1)
    for ($i = $startPage; $i < $total; $i++) {
        $tempUrl = $urls . (string)$i . $suffix;
        $current = ($i - $startPage) / $rate;
        $ch->getSinglePage($tempUrl, $imgSize);
        ?>
        <script language="JavaScript">
            updateProgress(<?php echo $current ?>);
            currentProgress(<?php echo "'" . $tempUrl . "'" ?>);
        </script>
        <?php
        flush();
    } else {
    $ch->getSinglePage($url, $imgSize);
}
?>
<script language="JavaScript">
    //程序结束
    updateProgress(200);
</script>
<?php
flush();
?>
</html>