/**
 * RoarJS
 *
 * A zero-dependency, vanilla JavaScript alert/confirm replacement.
 *
 * @url https://getbutterfly.com/roarjs-vanilla-javascript-alert-confirm-replacement/
 * @version 1.1.1
 *
 * @author Ciprian Popescu
 * @copyright 2018-2021
 * @license GPLv3 or later
 * @licenseUri https://www.gnu.org/licenses/gpl-3.0.html
 */
function roar(title, message, options) {
    if (typeof options !== 'object') {
        options = {};
    }

    if (!window.roarAlert) {
        var RoarObject = {
            element: null,
            cancelElement: null,
            confirmElement: null
        };

        RoarObject.element = document.querySelector('.roar-alert');
    } else {
        // Clear style
        if (window.roarAlert.cancel) {
            window.roarAlert.cancelElement.style = '';
        }
        if (window.roarAlert.confirm) {
            window.roarAlert.confirmElement.style = '';
        }
        // Show alert
        document.body.classList.add('roar-open');
        window.roarAlert.element.style.display = 'flex';

        RoarObject = window.roarAlert;
    }

    // Define default options
    RoarObject.cancel = options.cancel !== undefined ? options.cancel : false;
    RoarObject.cancelText = options.cancelText !== undefined ? options.cancelText : 'Cancel';
    RoarObject.cancelCallBack = (event) => {
        document.body.classList.remove('roar-open');
        window.roarAlert.element.style.display = 'none';

        // Cancel callback
        if (typeof options.cancelCallBack === 'function') {
            options.cancelCallBack(event);
        }

        // Cancelled
        return true;
    };

    RoarObject.message = message;
    RoarObject.title = title;
    RoarObject.confirm = options.confirm !== undefined ? options.confirm : true;
    RoarObject.confirmText = options.confirmText !== undefined ? options.confirmText : 'Confirm';
    RoarObject.confirmCallBack = (event) => {
        document.body.classList.remove('roar-open');
        window.roarAlert.element.style.display = 'none';

        // Confirm callback
        if (typeof options.confirmCallBack === 'function') {
            options.confirmCallBack(event);
        }

        // Confirmed
        return true;
    };

    if (!RoarObject.element) {
        RoarObject.html = `<div class="roar-alert" id="roar-alert" role="alertdialog">
            <div class="roar-alert-mask"></div>
            <div class="roar-alert-message-body" role="alert" aria-relevant="all">
                <div class="roar-alert-message-title">` + RoarObject.title + `</div>
                <div class="roar-alert-message-content">` + RoarObject.message + `</div>
                <div class="roar-alert-message-button">`;

                    if (RoarObject.confirm || true) {
                        RoarObject.html += '<a href="javascript:;" class="roar-alert-message-button-confirm">' + RoarObject.confirmText + '</a>';
                    }

                    if (RoarObject.cancel || true) {
                        RoarObject.html += '<a href="javascript:;" class="roar-alert-message-button-cancel">' + RoarObject.cancelText + '</a>';
                    }

                RoarObject.html += `</div>
            </div>
        </div>`;

        let element = document.createElement('div');

        element.id = 'roar-alert-wrap';
        element.innerHTML = RoarObject.html;
        document.body.appendChild(element);

        RoarObject.element = document.querySelector('.roar-alert');
        RoarObject.cancelElement = document.querySelector('.roar-alert-message-button-cancel');

        // Enable cancel button callback
        if (RoarObject.cancel) {
            document.querySelector('.roar-alert-message-button-cancel').style.display = 'block';
        } else {
            document.querySelector('.roar-alert-message-button-cancel').style.display = 'none';
        }

        // Enable confirm button callback
        RoarObject.confirmElement = document.querySelector('.roar-alert-message-button-confirm');

        if (RoarObject.confirm) {
            document.querySelector('.roar-alert-message-button-confirm').style.display = 'block';
        } else {
            document.querySelector('.roar-alert-message-button-confirm').style.display = 'none';
        }

        RoarObject.cancelElement.onclick = RoarObject.cancelCallBack;
        RoarObject.confirmElement.onclick = RoarObject.confirmCallBack;

        window.roarAlert = RoarObject;
    }

    document.querySelector('.roar-alert-message-title').innerHTML = '';
    document.querySelector('.roar-alert-message-content').innerHTML = '';
    document.querySelector('.roar-alert-message-button-cancel').innerHTML = RoarObject.cancelText;
    document.querySelector('.roar-alert-message-button-confirm').innerHTML = RoarObject.confirmText;

    RoarObject.cancelElement = document.querySelector('.roar-alert-message-button-cancel');

    // Enable cancel button callback
    if (RoarObject.cancel) {
        document.querySelector('.roar-alert-message-button-cancel').style.display = 'block';
    } else {
        document.querySelector('.roar-alert-message-button-cancel').style.display = 'none';
    }

    // Enable cancel button callback
    RoarObject.confirmElement = document.querySelector('.roar-alert-message-button-confirm');
    if (RoarObject.confirm) {
        document.querySelector('.roar-alert-message-button-confirm').style.display = 'block';
    } else {
        document.querySelector('.roar-alert-message-button-confirm').style.display = 'none';
    }
    RoarObject.cancelElement.onclick = RoarObject.cancelCallBack;
    RoarObject.confirmElement.onclick = RoarObject.confirmCallBack;

    // Set title and message
    RoarObject.title = RoarObject.title || '';
    RoarObject.message = RoarObject.message || '';

    document.querySelector('.roar-alert-message-title').innerHTML = RoarObject.title;
    document.querySelector('.roar-alert-message-content').innerHTML = RoarObject.message;

    window.roarAlert = RoarObject;
}