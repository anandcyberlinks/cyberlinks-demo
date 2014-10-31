<?php $randid = uniqid(); ?>
<div id="jwplayerrender">
    <div id="<?=$randid?>"></div>
</div>
<script type="text/javascript">jwplayer.key = "BC9ahgShNRQbE4HRU9gujKmpZItJYh5j/+ltVg==";</script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jwplayer.js" ></script>
<script>jwplayer("<?=$randid?>").setup({ file: "<?=$path?>",width:'100%',height:'100%'  }); </script>