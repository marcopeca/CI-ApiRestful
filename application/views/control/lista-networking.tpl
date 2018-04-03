{include file="../include/header.tpl"}

<div class="container">
    <div class="row">
        <div class="col s12">
            <h1>Iscritti {$relatore.nome}</h1>
        </div>
    </div>
    <div class="row">
        <div class="col s12">
            <p>Numero Iscritti: <span class="red-text text-darken-4"><strong>{$domande|@count}</strong></span></p>
            <a class="waves-effect waves-light btn green darken-3" href="{$config['base_url']}control/show_networking/1/{$id_evento}/1/" target="_blank"><i class="material-icons left">file_download</i>Scarica Lista</a>
        </div>
    </div>
    <div class="row">
        <div class="col s12">
            {if isset($domande)}
            <table class="bordered striped highlight responsive-table">
                <thead>
                    <tr>
                        <th>Partecipante</th>
                        <th>Mail</th>
                        <th>Professione</th>
                        <th>Data Invio</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach item=i from=$domande}
                    <tr>
                        <td>{$i.nome} {$i.cognome}</td>
                        <td>{$i.mail}</td>
                        <td>{$i.professione}</td>
                        <td>{$i.invio}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
            {else}
            <p>No domande</p>
            {/if}
        </div>
    </div>
</div>

{include file="../include/footer.tpl"}