<?php 
/**
 * 
 * @param string $title
 * @param array $javascripts
 * @param array $styles
 */
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
	<meta charset="utf-8">
	
    <script type="text/javascript" src="/js/jquery.js"></script>
    <link rel="stylesheet" type="text/css" href="/css/bootstrap.css" />
    <link rel="shortcut icon" href="images/favicon.ico" />
    
    <?php if($javascripts):?>
    <?php foreach($javascripts as $index=>$javascript_file):?>
    <script type="text/javascript" src="<?php echo $javascript_file;?>"></script>
    <?php endforeach;?>
    <?php endif;?>
    
    <?php if($styles):?>
    <?php foreach($styles as $index=>$style_file):?>
    <link rel="stylesheet" type="text/css" href="<?php echo $style_file;?>" />
    <?php endforeach;?>
    <?php endif;?>
    
	<title><?php echo $title;?></title>
</head>
<body>
<div id="container">