<?php echo '<?xml version="1.0" encoding="utf-8"?>' . "\n"; ?>
<GPI xmlns="http://www.garmin.com"
  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
  xsi:schemaLocation="http:/www.garmin.com GPI_XML.xsd">
  <?php foreach ($layers as $key => $layer ) {
    if (count($layer['markers']) > 0) { ?>
    <Group>
        <!-- ID of this group -->
        <ID>MobiMap<?php echo $layer['layer_id']; ?></ID>
        <!-- Display name of the group. -->
        <Name>
            <LString lang="EN"><?php echo htmlspecialchars($layer['name'], ENT_QUOTES); ?></LString>
        </Name>
<?php foreach ($layer['markers'] as $key => $marker) {
  if ($marker->lat != '' && $marker->lon != '' && $marker->name != '') {
  ?>
        <POI>
              <!-- Display name of the POI. -->
              <Name>
                  <LString lang="EN"><?php echo htmlspecialchars($marker->name, ENT_QUOTES); ?></LString>
              </Name>

              <!-- The geographic coordinates of the POI. -->
              <Geo>
                  <Lat><?php echo $marker->lat; ?></Lat>
                  <Lon><?php echo $marker->lon; ?></Lon>
              </Geo>

              <!-- Link the POI to a category from the category list. -->
              <!--<CategoryID>Restaurants</CategoryID> -->
              <?php if ($marker->city != '' || $marker->state != '' || $marker->zip != '' || $marker->address1 != '') { ?>
              <!-- Address information for the POI. -->
              <Address>

                  <!-- Street name. -->
                  <!--<Street>
                      <LString lang="EN">South Harrison Street</LString>
                  </Street> -->

                  <!-- House number. -->
                  <!-- <HouseNo>14920</HouseNo> -->

                  <!-- City name. -->
                  <?php if ($marker->city != '') { ?>
                  <City>
                      <LString lang="EN"><?php echo htmlspecialchars($marker->city, ENT_QUOTES); ?></LString>
                  </City>
                  <?php } ?>

                  <!-- State name. -->
                  <?php if ($marker->state != '') { ?>
                  <State>
                      <LString lang="EN"><?php echo htmlspecialchars($marker->state, ENT_QUOTES); ?></LString>
                  </State>
                  <?php } ?>

                  <!-- Country name. -->
                  <!--<Country>
                      <LString lang="EN">COUNTRY</LString>
                  </Country>-->

                  <!-- Zip code. -->
                  <?php if ($marker->zip != '') { ?>
                  <Zip><?php echo htmlspecialchars($marker->zip, ENT_QUOTES); ?></Zip>
                  <?php } ?>
                  <!--<Ext><?php echo htmlspecialchars($marker->address1, ENT_QUOTES); ?></Ext>-->

              </Address>
              <?php } ?>
              <?php if ($marker->phone1 != '') { ?>
              <!-- Contact information for the POI. -->
              <Contact>
                  <!-- A single phone number for this POI. A POI may have multiple phone numbers. -->
                  <?php if ($marker->phone1 != '') { ?>
                  <Phone><?php echo htmlspecialchars($marker->phone1, ENT_QUOTES); ?></Phone>
                  <?php } ?>
                  <!-- No fax numbers for this POI. -->

                  <!-- No e-mail address for this POI. -->

                  <!-- An internet address for the POI. -->
                  <!--<URL>http://www.chilis.com</URL> -->

                  <!-- This POI does not have any extended contact information. -->
              </Contact>
              <?php } ?>
        </POI>
<?php }
} ?>
  </Group>
  <?php }
}?>
</GPI>
