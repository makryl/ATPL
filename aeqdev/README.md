# ATPL

Micro include based template engine.



## Features

- Extending templates in both directions: wrapping content with layout or filling layout with content.
- Wrapping with another ATPL objects.
- Pushing child ATPL objects to render list of them as contents.
- Extremely fast: only 2 integers calculated while rendering.
- No extra buffering of results, only pure includes used.



## Usage

### Methods

```php
$mytpl = new ATPL();
$mytpl->addViewFile('MyTemplate.phtml');
$mytpl->render();
```

Example above will just include file "MyTemplate.phtml" when method "render" called.

For simplicity, when using ATPL in classes (or extending ATPL), there is method "addView", that receives constant \_\_CLASS\_\_ or \_\_METHOD\_\_ and convert it to view file path in [PSR-0](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md) style with one addition: method separator "::" converted to directory separator too, and differs in extension: ".phtml" used.

```php
$mytpl->addView(__CLASS__);
```

You can add several view files to one object: each next view file will be wrapped into a previous. To specify, where to render next view file of queue, you should call method "contents" in your view file.

```php
$mytpl = new ATPL();
$mytpl->addViewFile('Around.phtml');
$mytpl->addViewFile('Inside.phtml');
$mytpl->render();
```

```php
<!-- Around.phtml -->
Around begin
<?php $this->contents() ?>
Around end
```

```php
    <!-- Inside.phtml -->
    Inside contents
```

Output of example:

```php
<!-- Around.phtml -->
Around begin
    <!-- Inside.phtml -->
    Inside contents
Around end
```

You can wrap one template object around another one using method "wrap". When wrapped object renders, it will use all of it's wrappers. Last added wrapper will renders first.

```php
$around = new ATPL();
$around->addViewFile('Around.phtml');

$aroundMore = new ATPL();
$aroundMore->addViewFile('AroundMore.phtml');

$inside = new ATPL();
$inside->addViewFile('Inside.phtml');

$inside->wrap($around);
$inside->wrap($aroundMore);

$inside->render();
```

Output of example:

```php
<!-- AroundMore.phtml -->
AroundMore begin
    <!-- Around.phtml -->
    Around begin
        <!-- Inside.phtml -->
        Inside contents
    Around end
AroundMore end
```

You are free to combine both methods: adding views and wrapping.

You can add child objects to any ATPL object using method "push". Child objects will renders in order of adding, when method "contents" of parent object called.

```php
$child1 = new ATPL();
$child1->addViewFile('Child1.phtml');

$child2 = new ATPL();
$child2->addViewFile('Child2.phtml');

$parent = new ATPL();
$parent->addViewFile('Parent.phtml');

$parent->push($child1);
$parent->push($child2);

$parent->render();
```

```php
<!-- Parent.phtml -->
Parent begin
<?php $this->contents() ?>
Parent end
```

```php
    <!-- Child1.phtml -->
    Child1 contents
```

Output of example:

```php
<!-- Parent.phtml -->
Parent begin
    <!-- Child1.phtml -->
    Child1 contents
    <!-- Child2.phtml -->
    Child2 contents
Parent end
```



### Extending

When you extend ATPL class, you can specify view file in constructor.

```php
class LayoutBase extends ATPL
{
    public function __construct()
    {
        $this->addView(__CLASS__);
    }
}
```

```php
    <!-- LayoutBase.phtml -->
    LayoutBase begin
    <?php $this->contents() ?>
    LayoutBase end
```

You can extend your class with another one, and add one more view file in constructor. If you add view file before calling parent constructor, then this view file will be added to queue before parent's one. It means, this view file will be wrapped around parent's one.

```php
class LayoutAround extends LayoutBase
{
    public function __construct()
    {
        $this->addView(__CLASS__);
        parent::__construct();
    }
}
```

```php
<!-- LayoutAround.phtml -->
LayoutAround begin
<?php $this->contents() ?>
LayoutAround end
```

```php
$tpl = new LayoutAround();
$tpl->render();
```

Output of example:

```php
<!-- LayoutAround.phtml -->
LayoutAround begin
    <!-- LayoutBase.phtml -->
    LayoutBase begin
    LayoutBase end
LayoutAround end
```

If you add view file after calling parent constructor, then this view file will be added to queue after the parent's one. It means, this view file will be rendered inside parent's one.

```php
class LayoutInside extends LayoutBase
{
    public function __construct()
    {
        parent::__construct();
        $this->addView(__CLASS__);
    }
}
```

```php
        <!-- LayoutInside.phtml -->
        LayoutInside begin
        <?php $this->contents() ?>
        LayoutInside end
```

```php
$tpl = new LayoutInside();
$tpl->render();
```

Output of example:

```php
    <!-- LayoutBase.phtml -->
    LayoutBase begin
        <!-- LayoutInside.phtml -->
        LayoutInside begin
        LayoutInside end
    LayoutBase end
```



## License

Copyright © 2013 Krylosov Maksim <Aequiternus@gmail.com>

This Source Code Form is subject to the terms of the Mozilla Public
License, v. 2.0. If a copy of the MPL was not distributed with this
file, You can obtain one at http://mozilla.org/MPL/2.0/.
