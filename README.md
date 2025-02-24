## Usage

Add repository to your `composer.json`:

```
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/urbasekd/nette-doctrine3-queries-logger"
    }
]
```

Install package:

```
composer require urbasekd/nette-doctrine3-queries-logger
```

Doctrine setup:

```
#Create logger
sqlLogger: OM\Doctrine\QueriesLogger\ConnectionPanel

#Create new Middleware (with our logger)
loggingMiddleware: OM\Doctrine\QueriesLogger\DoctrineMiddlewares\Middleware(@sqlLogger)

#Set Middleware
doctrineConfig:
    factory: Doctrine\ORM\ORMSetup::createAttributeMetadataConfiguration(...)
    setup:
        - setMiddlewares([@loggingMiddleware])
```

Add logger to debug-bar:

```
tracy:
  bar:
    - @sqlLogger
```

## Thanks
For debug-bar, I used code from [Nella Connection Panel](https://patrik.votocek.cz/)