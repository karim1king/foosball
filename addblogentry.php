<?php
$filename = "news/blog.xml";
if (file_exists($filename))
{
  $rawBlog = file_get_contents($filename);
}
else
{
  $rawBlog = "<?xml version=\"1.0\" encoding=\"utf-8\" ?>";
  $rawBlog .= "<blog><title>Новасти ностольного чемпионата</title>";
  $rawBlog .= "<author>Admin Karim</author><entries></entries></blog>";
}
$xml = new SimpleXmlElement($rawBlog);

$entry = $xml->entries->addChild("entry");
$entry->addChild("date", $_POST["date"]);
$entry->addChild("body", stripslashes($_POST["body"]));

$finalPath;
if(is_uploaded_file($_FILES["filename"]["tmp_name"]))
{
    $finalPath = "news/".uniqid().$_FILES["filename"]["name"];
    move_uploaded_file($_FILES["filename"]["tmp_name"], $finalPath);
}

if (isset($finalPath))
  $entry->addChild("image", $finalPath);

$file = fopen($filename, 'w');
fwrite($file, $xml->asXML());
fclose($file);
header("location:turlistedit.php");

