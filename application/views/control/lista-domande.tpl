{include file="../include/header.tpl"}

<div class="container">
    <div class="row">
        <div class="col s12">
            <h1>Domande per {$relatore.nome}</h1>
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
                        <th>Domanda</th>
                        <th>Data Invio</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach item=i from=$domande}
                    <tr>
                        <td>{$i.nome} {$i.cognome}</td>
                        <td>{$i.mail}</td>
                        <td>{$i.domanda}</td>
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