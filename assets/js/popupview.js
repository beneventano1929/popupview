$(document).ready(function () {
    const $popup = $('#popupview');
    const $closeButton = $('#close-popup');
    const $messageElement = $('#popup-message');
    if ($popup.length === 0 || $closeButton.length === 0 || $messageElement.length === 0) {
        console.error('Popup elements not found on the page.');
        return;
    }

    if (!Array.isArray(popupMessages) || popupMessages.length === 0) {
        console.warn('Popup messages are not available; popup will not be shown.');
        return;
    }

    function generateMessage() {
        const randomIndex = Math.floor(Math.random() * popupMessages.length);
        return popupMessages[randomIndex]
            .replace("{X}", Math.floor(Math.random() * 10) + 1)
            .replace("{Y}", Math.floor(Math.random() * 50) + 1);
    }

    function showPopup() {
        $messageElement.text(generateMessage());
        $popup.addClass('show');
    }

    const initialDelay = Math.max(0, (popupConfig?.delay || 0) * 1000);
    const repeatInterval = Math.max(0, (popupConfig?.repeatInterval || 0) * 1000);

    setTimeout(() => {
        showPopup();

        if (repeatInterval > 0) {
            setInterval(showPopup, repeatInterval);
        }
    }, initialDelay);
    $closeButton.on('click', function () {
        $popup.removeClass('show');
    });
});
        return popupMessages[randomIndex]
            .replace("{X}", Math.floor(Math.random() * 10) + 1)
            .replace("{Y}", Math.floor(Math.random() * 50) + 1);
    }

    setTimeout(() => {
        $popup.addClass('show');
        $messageElement.text(generateMessage());
    }, 2000);

    $closeButton.on('click', function () {
        $popup.removeClass('show');
    });
});
