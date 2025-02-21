<div class="panel">
    <p style="background-color: #f9f9f9; padding: 10px; border-left: 5px solid #d9534f;">
        <strong>{l s='Variabile {X}:' mod='popupview'}</strong><br>
        {l s='Rappresenta un numero casuale che indica la quantità residua di un prodotto.' mod='popupview'}<br>
        {l s='Generato casualmente tra 1 e 10, per far sembrare che ci siano solo pochi pezzi rimasti.' mod='popupview'}<br>
        {l s='Utilizzato per creare frasi come:' mod='popupview'}<br>
        <em>"{l s='Solo 5 pezzi rimasti! Ordina ora per non perderli.' mod='popupview'}"</em>
    </p>
    <p style="background-color: #f9f9f9; padding: 10px; border-left: 5px solid #0275d8;">
        <strong>{l s='Variabile {Y}:' mod='popupview'}</strong><br>
        {l s='Rappresenta un numero casuale che indica quante persone stanno visualizzando il prodotto in quel momento.' mod='popupview'}<br>
        {l s='Generato casualmente tra 1 e 50, per simulare un alto interesse verso il prodotto.' mod='popupview'}<br>
        {l s='Utilizzato per creare frasi come:' mod='popupview'}<br>
        <em>"{l s='30 persone stanno guardando questo prodotto ora!' mod='popupview'}"</em>
    </p>
    <br>
    <h3>{l s='Configurazione Popup Dinamico' mod='popupview'}</h3>
    <form action="{$form_action}" method="post">
        {for $i=1 to 5}
            <div class="form-group">
                <label>{l s='Messaggio' mod='popupview'} {$i}</label>
                <input type="text" name="POPUPVIEW_MESSAGE_{$i}" value="{$messages[$i-1]|escape:'htmlall':'UTF-8'}" class="form-control">
            </div>
        {/for}
        <div class="form-group">
            <label>{l s='Colore di sfondo' mod='popupview'}</label>
            <input type="color" name="POPUPVIEW_BG_COLOR" value="{$bg_color|escape:'htmlall':'UTF-8'}" class="form-control" style="max-width: 150px;">
        </div>
        <div class="form-group">
            <label>{l s='Colore del testo' mod='popupview'}</label>
            <input type="color" name="POPUPVIEW_TEXT_COLOR" value="{$text_color|escape:'htmlall':'UTF-8'}" class="form-control" style="max-width: 150px;">
        </div>
        <button type="submit" class="btn btn-primary" name="submit_popupview">{l s='Salva' mod='popupview'}</button>
    </form>
</div>
