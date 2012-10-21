<?php
/**
 *
 * @param string $title
 * @param array $javascripts
 * @param array $styles
 * @param JavascriptCssManager $javascript_css_manager
 */
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
	<meta charset="utf-8">

    <script src="/js/jquery-min.js"></script>
    <script src="/js/bootstrap/bootstrap.min.js"></script>
    <link rel="stylesheet" href="/css/bootstrap.css" />
    <link rel="stylesheet" href="/css/oa.css" />
    <link rel="shortcut icon" href="images/favicon.ico" />

    <?php $javascript_css_manager->outputAll();?>
    
	<title><?php echo $title;?></title>
</head>
<body>
<?php
$this->navbar->tryToDisplay();
?>
<div class="container">