{include file="../include/header.tpl"}

<div class="container">
    <div class="row">
        <div class="col s12">
            <h1>Scegli l'interazione dell'evento che vuoi visualizzare</h1>
        </div>
    </div>
    <div class="row">
        <div class="collection">            
            {foreach item=i from=$interazioni}
            <a href="{$config['base_url']}control/show/{$i.id}/" class="collection-item"><span class="new badge">{$i.tot}</span>{$i.relatore}</a>
            {/foreach}
        </div>
    </div>
</div>

{include file="../include/footer.tpl"}