# Verwendung

PHP-Klassen in diesem Ordner definieren neue Content-Elemente.  

## Namenskonventionen

Die PHP-Klassen werden ohne vorangestelltest "Content" angelegt (abweichend vom core-bundle).  
Beispiel: `ImageZoon.php` anstelle von `ContentImageZoom.php`.  
Inkl. Namespace: `AppBundle\ContentElement\Card.php`

## Elternklasse

Die Klassen sollten von der Elternklasse `\Contao\ContentElement` erben.  
Bei sehr ähnlicher Funktionalität zu einem bestehenden Content-Element, kann dieses als Elternelement verwendet werden.
