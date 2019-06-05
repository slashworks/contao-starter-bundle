# Verwendung

PHP-Klassen in diesem Ordner werden im Kombination mit dca-Dateien verwendet, z. B. für options_callback.  
Beispiel: `Content.php` (für tl_content.php) oder `Page.php` für tl_page.php  
Inkl. Namespace: `AppBundle\DataContainer\Content` oder `AppBundle\DataContainer\Page`

## Elternklasse

Die Klassen müssen von keiner Elternklasse erben. Es kann unter bestimmten Bedingungen sinnvoll sein, von `\Contao\Backend` zu erben.
