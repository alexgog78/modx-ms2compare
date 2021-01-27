<?php

$prefix = 'ms2compare_property_';

$_lang[$prefix . 'id'] = 'Идентификатор ресурса. Если не указан, используется id текущего документа.';
$_lang[$prefix . 'list'] = 'Идентификатор списка сравнения. Если у вас на сайте разные группы товаров, то нужно указывать для них разные списки.';
$_lang[$prefix . 'lists'] = 'Список идентификаторов списков сравнения, через запятую, для вывода в результатах.';
$_lang[$prefix . 'page'] = 'Идентификатор страницы для ссылки.';
$_lang[$prefix . 'tpl'] = 'Чанк оформления результатов.';
$_lang[$prefix . 'tplempty'] = 'Чанк, который выводится при отсутствии результатов.';
$_lang[$prefix . 'fields'] = 'Список опций товара для вывода в сравнении, через запятую. Если не указан, используются все поля msProductData кроме указанных в параметре "fieldsToExclude".';
$_lang[$prefix . 'fieldstoexclude'] = 'Список опций товара которые не надо выводить в сравнении, через запятую. Используется в случае пустого параметра "fields".';
$_lang[$prefix . 'fieldstoshowifempty'] = 'Список опций товара которые выводить даже при пустом значении у всех товаров, через запятую.';