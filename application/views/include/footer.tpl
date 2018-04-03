<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script src="{$config['base_url']}{$base_js}/material.js"></script>

{foreach item=i from=$default_js}
<script src="{$config['base_url']}{$base_js}/{$i}"></script>
{/foreach}
</body>
</html>