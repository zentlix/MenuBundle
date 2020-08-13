Zentlix Menu Bundle
=================

This bundle is part of Zentlix CMS. Currently in development, please do not use in production!

## Установка
- Установить Zentlix CMS https://github.com/zentlix/MainBundle 
- Установить MenuBundle:
```bash
    composer require zentlix/menu-bundle
```
- Создать миграцию:
```bash 
    php bin/console doctrine:migrations:diff
```
- Выполнить миграцию: 
```bash 
    php bin/console doctrine:migrations:migrate
```
- Выполнить установку бандла:
```bash 
    php bin/console zentlix_main:install zentlix/menu-bundle
```

## Использование

- Создать меню в административной панели, добавить пункты меню, скопировать "Символьный код".
- В шаблоне сайта разместить виджет:
```twig
    {{ menuWidget('symbol_code') }}
```

- Вторым параметром можно передать имя шаблона:
```twig
    {{ menuWidget('symbol_code', 'main_menu') }}
```
- Шаблон должен быть создан в шаблоне сайта и прописан в конфигурационном файле шаблона templates/шаблон/src/config.yaml:
```yaml
    menu:
      main_menu: "menu/main.html.twig"
```
- Для вывода заголовка меню используется виджет:
```twig
    {{ menuTitleWidget('symbol_code') }}
```