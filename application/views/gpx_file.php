<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<gpx version="1.0" creator="GPS MobiMap https://mobimap.io/" xmlns="http://www.topografix.com/GPX/1/0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.topografix.com/GPX/1/0 http://www.topografix.com/GPX/1/0/gpx.xsd">
  <?php foreach ($markers as $key => $marker ) { ?>
    <wpt lat="<?php echo $marker->lat; ?>" lon="<?php echo $marker->lon; ?>">
        <name><?php echo $marker->name; ?></name>
        <cmt>COMMENT</cmt>
        <desc> </desc>
        <sym> </sym>
        <extensions>
            <gpxx:WaypointExtension>
                <gpxx:DisplayMode>SymbolAndName</gpxx:DisplayMode>
                <gpxx:Categories>
                    <gpxx:Category><?php echo $projects[0]->description; ?></gpxx:Category>
                </gpxx:Categories>
                <gpxx:Address>
                    <gpxx:StreetAddress><?php echo $marker->address1; ?></gpxx:StreetAddress>
                    <gpxx:City><?php echo $marker->city; ?></gpxx:City>
                    <gpxx:State><?php echo $marker->state; ?></gpxx:State>
                    <gpxx:PostalCode><?php echo $marker->zip; ?></gpxx:PostalCode>
                </gpxx:Address>
                <gpxx:PhoneNumber><?php echo $marker->phone1; ?></gpxx:PhoneNumber>
            </gpxx:WaypointExtension>
        </extensions>
    </wpt>
    <?php } ?>
</gpx>
