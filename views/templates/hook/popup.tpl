<div id="popupview" class="popup-view" style="background-color: {$popup_bg_color|escape:'htmlall':'UTF-8'}; color: {$popup_text_color|escape:'htmlall':'UTF-8'};">
    <button id="close-popup" class="popup-close">&times;</button>
    <p id="popup-message" class="popup-message"></p>
</div>

<script>
    var popupMessages = {$popup_messages_json nofilter};
    var popupConfig = {$popup_config_json nofilter};
</script>

<script>
    var popupMessages = {$popup_messages nofilter};
</script>
