<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{$config['base_url']}{$base_css}/material.css">
    {foreach item=i from=$default_css}
    <link rel="stylesheet" href="{$config['base_url']}{$base_css}/{$i}">
    {/foreach}
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>{$title}</title>
</head>
<body>
    {include file="./navbar-top.tpl"}