{include file="../include/header.tpl"}

<div class="container">
    <div class="row">
        <div class="col s12">
            <h1>Lista Partecipanti {$relatore.nome}</h1>
        </div>
    </div>
    <div class="row">
        <div class="col s12">
            {if isset($partecipanti)}
            <table class="bordered striped highlight responsive-table">
                <thead>
                    <tr>
                        <th>Partecipante</th>
                        <th>Mail</th>
                        <th>Professione</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    {foreach item=i from=$partecipanti}
                    <tr>
                        <td>{$i.nome} {$i.cognome}</td>
                        <td>{$i.mail}</td>
                        <td>{$i.professione}</td>
                        <td>
                            {if $i.flag_networking}
                            <a href="{$config['base_url']}control/removePartecipante/{$i.id}/{$id_evento}/1/" class="waves-effect waves-light btn amber darken-3">Rimuovi</a>
                            {else}
                            <a href="{$config['base_url']}control/addPartecipante/{$i.id}/{$id_evento}/1/" class="waves-effect waves-light btn green darken-3">Aggiungi</a>
                            {/if}
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
            {else}
            <p>No partecipanti</p>
            {/if}
        </div>
    </div>
</div>

{include file="../include/footer.tpl"}