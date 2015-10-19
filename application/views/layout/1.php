<html>
<head>
<style>
img {
   padding:1px;
   border:1px solid #021a40;
}

img.500 {
   max-width:500px;
   padding:1px;
   border:1px solid #021a40;
}

div.container {
      display:inline-block;
    }
</style>
</head>
<body>
<div id="heading">
<h1><?php echo $title;?></h1>
</div>
<div class="container">
<h2><?php echo $maps[0]->description;?></h2>
<img class="500" src="/staticmap.php?mapid=<?php echo $maps[0]->id;?>">
</div>
<div class="container">
<h2><?php echo $maps[1]->description;?></h2>
<img class="500" src="/staticmap.php?mapid=<?php echo $maps[1]->id;?>">
</div>



<?php foreach ($maps as $map) { ?>
<h2><?php echo $map->description;?></h2>
<img src="/staticmap.php?mapid=<?php echo $map->id;?>" style="width:<?php echo $map->size_width;?>px;height:<?php echo $map->size_height;?>px;">
<?php } ?>
<div id="addresses">
<h2>Addresses</h2>
<?php echo $address_table;?>
</div>
</body>
</html>
