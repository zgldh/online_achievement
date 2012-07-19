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

    <script src="/js/jquery-min.js"></script>
    <link rel="stylesheet" href="/css/bootstrap.css" />
    <link rel="shortcut icon" href="images/favicon.ico" />

    <?php // load javascript files?>
    <?php if($javascripts):?>
    <?php foreach($javascripts as $index=>$javascript_file):?>
    <script src="<?php echo $javascript_file;?>"></script>
    <?php endforeach;?>
    <?php endif;?>

    <?php // set auto run javascript codes ?>
    <?php if($auto_javascript_codes):?>
    <script type="text/javascript">
    $(function(){
    <?php foreach($auto_javascript_codes as $index=>$code):?>
    <?php echo $code;?>
    <?php endforeach;?>
    });
    </script>
    <?php endif;?>

    <?php // load style files?>
    <?php if($styles):?>
    <?php foreach($styles as $index=>$style_file):?>
    <link rel="stylesheet" href="<?php echo $style_file;?>" />
    <?php endforeach;?>
    <?php endif;?>

	<title><?php echo $title;?></title>
</head>
<body>
<?php
if($this->navbar->isDisplay())
{
    $this->load->view('common/navbar');
}
?>
<div class="container">