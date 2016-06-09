<?php

use Moonlight\Main\Site;
use Moonlight\Main\Item;
use Moonlight\Properties\BaseProperty;
use Moonlight\Properties\CheckboxProperty;
use Moonlight\Properties\DatetimeProperty;
use Moonlight\Properties\DateProperty;
use Moonlight\Properties\FloatProperty;
use Moonlight\Properties\ImageProperty;
use Moonlight\Properties\IntegerProperty;
use Moonlight\Properties\OneToOneProperty;
use Moonlight\Properties\RichtextProperty;
use Moonlight\Properties\TextareaProperty;
use Moonlight\Properties\TextfieldProperty;

$site = \App::make('site');

$site->
    
    /*
	 * Раздел сайта
	 */

	addItem(
		Item::create('App\Section')->
		setTitle('Раздел сайта')->
		setMainProperty('name')->
		setRoot(true)->
		setElementPermissions(true)->
		addOrder()->
		addProperty(
			TextfieldProperty::create('name')->
			setTitle('Название')->
			setRequired(true)->
			setShow(true)
		)->
		addProperty(
			TextfieldProperty::create('url')->
			setTitle('Адрес страницы')->
            setRequired(true)->
			addRule('regex:/^[a-z0-9\-]+$/i', 'Допускаются латинские буквы, цифры и дефис.')
		)->
		addProperty(
			TextfieldProperty::create('title')->
			setTitle('Title')
		)->
		addProperty(
			TextfieldProperty::create('h1')->
			setTitle('H1')
		)->
		addProperty(
			TextfieldProperty::create('meta_keywords')->
			setTitle('META Keywords')
		)->
		addProperty(
			TextareaProperty::create('meta_description')->
			setTitle('META Description')
		)->
		addProperty(
			TextareaProperty::create('shortcontent')->
			setTitle('Краткий текст')
		)->
		addProperty(
			RichtextProperty::create('fullcontent')->
			setTitle('Текст раздела')
		)->
		addProperty(
			OneToOneProperty::create('section_id')->
			setTitle('Раздел сайта')->
			setRelatedClass('App\Section')->
			setParent(true)
		)->
		addTimestamps()->
		addSoftDeletes()
	)->

	/*
	 * Служебный раздел
	 */

	addItem(
		Item::create('App\ServiceSection')->
		setTitle('Служебный раздел')->
		setMainProperty('name')->
		setRoot(true)->
		setElementPermissions(true)->
		addOrder()->
		addProperty(
			TextfieldProperty::create('name')->
			setTitle('Название')->
			setRequired(true)->
			setShow(true)
		)->
		addProperty(
			OneToOneProperty::create('service_section_id')->
			setTitle('Служебный раздел')->
			setRelatedClass('App\ServiceSection')->
			setParent(true)
		)->
		addTimestamps()->
		addSoftDeletes()
	)->

	/*
	 * Настройки сайта
	 */

	addItem(
		Item::create('App\SiteSettings')->
		setTitle('Настройки сайта')->
		setMainProperty('name')->
		setRoot(true)->
		addProperty(
			TextfieldProperty::create('name')->
			setTitle('Название')->
			setRequired(true)->
			setShow(true)
		)->
		addProperty(
			TextfieldProperty::create('title')->
			setTitle('Title')->
			setRequired(true)
		)->
		addProperty(
			TextfieldProperty::create('h1')->
			setTitle('H1')
		)->
		addProperty(
			TextareaProperty::create('description')->
			setTitle('META Description')
		)->
		addProperty(
			TextfieldProperty::create('keywords')->
			setTitle('META Keywords')
		)->
		addProperty(
			RichtextProperty::create('text')->
			setTitle('Текст')
		)->
		addProperty(
			TextfieldProperty::create('copyright')->
			setTitle('Copyright')
		)->
		addTimestamps()->
		addSoftDeletes()
	)->

	/*
	 * Категория товаров
	 */

	addItem(
		Item::create('App\Category')->
		setTitle('Категория товаров')->
		setMainProperty('name')->
		setRoot(true)->
		addOrder()->
		addProperty(
			TextfieldProperty::create('name')->
			setTitle('Название')->
			setRequired(true)->
			setShow(true)
		)->
		addProperty(
			ImageProperty::create('image')->
			setTitle('Изображение')->
			setResize(250, 200, 80)->
			setShow(true)
		)->
		addProperty(
			TextfieldProperty::create('url')->
			setTitle('URL')->
			setRequired(true)->
			addRule('regex:/^[a-z0-9\-]+$/i', 'Допускаются латинские буквы, цифры и дефис.')
		)->
		addProperty(
			TextfieldProperty::create('title')->
			setTitle('Title')
		)->
		addProperty(
			TextareaProperty::create('shortcontent')->
			setTitle('Краткое описание')->
			setShow(true)->
			setEditable(true)
		)->
		addProperty(
			RichtextProperty::create('fullcontent')->
			setTitle('Полное описание')
		)->
		addProperty(
			CheckboxProperty::create('hide')->
			setTitle('Скрыть')->
			setShow(true)->
			setEditable(true)
		)->
		addTimestamps()->
		addSoftDeletes()
	)->

	/*
	 * Подкатегория товаров
	 */

	addItem(
		Item::create('App\Subcategory')->
		setTitle('Подкатегория товаров')->
		setMainProperty('name')->
		addOrder()->
		addProperty(
			TextfieldProperty::create('name')->
			setTitle('Название')->
			setRequired(true)->
			setShow(true)
		)->
		addProperty(
			TextfieldProperty::create('url')->
			setTitle('URL')->
			setRequired(true)->
			addRule('regex:/^[a-z0-9\-]+$/i', 'Допускаются латинские буквы, цифры и дефис.')
		)->
		addProperty(
			TextfieldProperty::create('title')->
			setTitle('Title')
		)->
		addProperty(
			RichtextProperty::create('fullcontent')->
			setTitle('Полное описание')
		)->
		addProperty(
			CheckboxProperty::create('hide')->
			setTitle('Скрыть')->
			setShow(true)->
			setEditable(true)
		)->
		addProperty(
			OneToOneProperty::create('category_id')->
			setTitle('Категория товаров')->
			setRelatedClass('App\Category')->
			setParent(true)->
			setRequired(true)
		)->
		addTimestamps()->
		addSoftDeletes()
	)->

	/*
	 * Товар
	 */

	addItem(
		Item::create('App\Good')->
		setTitle('Товар')->
		setMainProperty('name')->
		addOrder()->
		addProperty(
			TextfieldProperty::create('name')->
			setTitle('Название')->
			setRequired(true)->
			setShow(true)
		)->
		addProperty(
			TextfieldProperty::create('url')->
			setTitle('URL')->
			setRequired(true)->
			addRule('regex:/^[a-z0-9\-]+$/i', 'Допускаются латинские буквы, цифры и дефис.')
		)->
		addProperty(
			ImageProperty::create('image')->
			setTitle('Изображение')->
			setResize(300, 350, 80)->
			addResize('spec', 150, 200, 80)->
			addResize('other', 100, 100, 80)
		)->
		addProperty(
			FloatProperty::create('supplier_price')->
			setTitle('Цена поставщика')->
			setRequired(true)->
			setShow(true)->
			setEditable(true)
		)->
		addProperty(
			FloatProperty::create('price')->
			setTitle('Цена')->
			setRequired(true)->
			setShow(true)->
			setEditable(true)
		)->
		addProperty(
			FloatProperty::create('old_price')->
			setTitle('Старая цена')
		)->
		addProperty(
			TextfieldProperty::create('title')->
			setTitle('Title')
		)->
		addProperty(
			TextfieldProperty::create('meta_keywords')->
			setTitle('META Keywords')
		)->
		addProperty(
			TextareaProperty::create('meta_description')->
			setTitle('META Description')
		)->
		addProperty(
			TextareaProperty::create('shortcontent')->
			setTitle('Краткое описание')
		)->
		addProperty(
			RichtextProperty::create('fullcontent')->
			setTitle('Полное описание')
		)->
		addProperty(
			CheckboxProperty::create('special')->
			setTitle('Спецпредложение')
		)->
		addProperty(
			CheckboxProperty::create('novelty')->
			setTitle('Новинка')
		)->
		addProperty(
			CheckboxProperty::create('hide')->
			setTitle('Скрыть')->
			setShow(true)->
			setEditable(true)
		)->
		addProperty(
			CheckboxProperty::create('absent')->
			setTitle('Нет в наличии')->
			setShow(true)->
			setEditable(true)
		)->
		addProperty(
			OneToOneProperty::create('category_id')->
			setTitle('Категория товара')->
			setRelatedClass('App\Category')->
			setRequired(true)->
			setParent(true)
		)->
		addProperty(
			OneToOneProperty::create('subcategory_id')->
			setTitle('Подкатегория товара')->
			setRelatedClass('App\Subcategory')
		)->
		addTimestamps()->
		addSoftDeletes()
	)->
    
    /*
	 * Категория расхода
	 */

	addItem(
		Item::create('App\ExpenseCategory')->
		setTitle('Категория расхода')->
		setMainProperty('name')->
		addOrder()->
		addProperty(
			TextfieldProperty::create('name')->
			setTitle('Название')->
			setRequired(true)->
			setShow(true)
		)->
		addProperty(
			OneToOneProperty::create('service_section_id')->
			setTitle('Служебный раздел')->
			setRelatedClass('App\ServiceSection')->
			setParent(true)
		)->
		addTimestamps()->
		addSoftDeletes()
	)->
    
    /*
	 * Источник расхода
	 */

	addItem(
		Item::create('App\ExpenseSource')->
		setTitle('Источник расхода')->
		setMainProperty('name')->
		addOrder()->
		addProperty(
			TextfieldProperty::create('name')->
			setTitle('Название')->
			setRequired(true)->
			setShow(true)
		)->
		addProperty(
			OneToOneProperty::create('service_section_id')->
			setTitle('Служебный раздел')->
			setRelatedClass('App\ServiceSection')->
			setParent(true)
		)->
		addTimestamps()->
		addSoftDeletes()
	)->
    
    /*
	 * Расход
	 */

	addItem(
		Item::create('App\Expense')->
		setTitle('Расход')->
		setMainProperty('name')->
        addOrderBy('created_at', 'desc')->
		addProperty(
			TextfieldProperty::create('name')->
			setTitle('Название')->
			setRequired(true)->
			setShow(true)
		)->
        addProperty(
			OneToOneProperty::create('category_id')->
			setTitle('Категория расхода')->
			setRelatedClass('App\ExpenseCategory')
		)->
        addProperty(
			OneToOneProperty::create('source_id')->
			setTitle('Источник расхода')->
			setRelatedClass('App\ExpenseSource')
		)->
        addProperty(
			FloatProperty::create('sum')->
			setTitle('Сумма')->
			setRequired(true)->
			setShow(true)
		)->
        addProperty(
			TextareaProperty::create('comments')->
			setTitle('Комментарий')
		)->
		addProperty(
			OneToOneProperty::create('service_section_id')->
			setTitle('Служебный раздел')->
			setRelatedClass('App\ServiceSection')->
			setParent(true)
		)->
		addProperty(
			DatetimeProperty::create('created_at')->
            setFillNow()->
			setTitle('Создан')->
			setShow(true)
		)->
		addProperty(
			DatetimeProperty::create('updated_at')->
			setTitle('Изменен')->
			setReadonly(true)->
			setShow(true)
		)->
		addSoftDeletes()
	)->

	bind(Site::ROOT, 'App\Category', 'App\Section', 'App\ServiceSection', 'App\SiteSettings')->
	bind('App\Category', 'App\Subcategory', 'App\Good')->
	bind('App\Subcategory', 'App\Good')->
    bind(env('site.dicts', 'App.ServiceSection.1'), 'App\ServiceSection')->
    bind(env('site.expenses', 'App.ServiceSection.4'), 'App\Expense')->
	bind(env('site.statistic', 'App.ServiceSection.7'), 'App\ServiceSection')->
	bind(env('site.expense_categories', 'App.ServiceSection.11'), 'App\ExpenseCategory')->
	bind(env('site.expense_sources', 'App.ServiceSection.12'), 'App\ExpenseSource')->

	bindBrowsePlugin('App.ServiceSection.8', 'moneyStat')->
	bindSearchPlugin('App\Good', 'goodSearch')->
	bindEditPlugin('App\ServiceSection', 'moneyStat2')->
	bindBrowseFilter('App\Good', 'goodFilter')->

	end();