// We need the service worker registration to check for a subscription
    navigator.serviceWorker.ready.then(function(serviceWorkerRegistration) {
        // Do we already have a push message subscription?
        serviceWorkerRegistration.pushManager.getSubscription()
        .then(function(subscription) {
            // Enable any UI which subscribes / unsubscribes from
            // push messages.
            //changePushButtonState('disabled');

            if (!subscription) {
                // We aren't subscribed to push, so set UI
                // to allow the user to enable push
                return;
            }

            // Keep your server in sync with the latest endpoint
            console.log(subscription);
            return;
          push_sendSubscriptionToServer(subscription, 'update');

            // Set your UI to show they have subscribed for push messages
            //changePushButtonState('enabled');
        })
        ['catch'](function(err) {
            console.warn('[SW] Eroare la getSubscription()', err);
        });
    });
}



function push_sendSubscriptionToServer(subscription, action) {
    var req = new XMLHttpRequest();
    var url = 'push_'+action;
    req.open('POST', url, true);
    req.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    req.setRequestHeader("Content-type", "application/json");
    req.onreadystatechange = function (e) {
        if (req.readyState == 4) {
            if(req.status != 200) {
                console.error("[SW] Eroare :" + e.target.status);
            }
        }
    };
    req.onerror = function (e) {
        console.error("[SW] Eroare :" + e.target.status);
    };

    var key = subscription.getKey('p256dh');
    var token = subscription.getKey('auth');

    req.send(JSON.stringify({
        'endpoint': getEndpoint(subscription),
        'key': key ? btoa(String.fromCharCode.apply(null, new Uint8Array(key))) : null,
        'token': token ? btoa(String.fromCharCode.apply(null, new Uint8Array(token))) : null
    }));

    return true;
}

function getEndpoint(pushSubscription) {
    var endpoint = pushSubscription.endpoint;
    var subscriptionId = pushSubscription.subscriptionId;

    // fix Chrome < 45
    if (subscriptionId && endpoint.indexOf(subscriptionId) === -1) {
        endpoint += '/' + subscriptionId;
    }

    return endpoint;
}

function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding)
        .replace(/\-/g, '+')
        .replace(/_/g, '/');

    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);

    for (var i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}
