# Verwendung

PHP-Klassen in diesem Ordner werden für eigene Frontend-Module verwendet.  

## Namenskonvention

Die PHP-Klassen werden ohne vorangestelltest "Module" angelegt (abweichend vom core-bundle).  
Beispiel: `ImageList.php` anstelle von `ModuleImageList.php`.  
Inkl. Namespace: `AppBundle\Module\ImageList`.

## Elternklasse

Die Klassen sollten von der Elternklasse `\Contao\Module` erben.  
