#Translate

##Install by adding

#Set up service provider in config/app.php
```php
Nodes\Translate\ServiceProvider::class
```

#Set up alias in config/app.php
```php
'NodesTranslate' => Nodes\Translate\Support\Facades\Translate::class
```

#Copy the config file

```php
from translate/config to htdocs/config/nodes
```

#Pick providers
There is both a upload and url provider
This can be found in general config

```php
config/nodes/translate
```


#Global functions

```php
translate($key, $replacements = [], $locale = null, $platform = null)
```

#Facade

```php
Translate::get($key, $replacements = [], $locale = null, $platform = null)
\NodesTranslate::get($key, $replacements = [], $locale = null, $platform = null) (alias)
```

