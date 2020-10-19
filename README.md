# Web Site Administration Tool

Web Site Administration Tool (WSAT) is a development tool which allows you to perform several tedious tasks of a PRADO project in a GUI fashion. Its inspired in both Asp .Net - Web Site Administration Tool and Yii's Gii. WSAT will continue gaining new features along the time, at the moment it bring you the followings:

 * Generate one or all Active Record Classes.
 * Optionally generate all relationships in Active Record Classes.
 * Generate the magic __toString() method in all AR Classes.

## Requirements

To use WSAT, you need to add in your project configuration file: `application.xml`, in the services section the wsat service like follows:

```xml
<services>
    ...
    <service id="wsat" class="System.Wsat.TWsatService" Password="my_secret_password" />
</services>
```

## Usage

Then you are ready to go to: `http://localhost/yoursite/index.php?wsat=TWsatLogin` and follow the instructions on the page.