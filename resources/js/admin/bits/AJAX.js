const request = async function(method, route, data = {}) {
    const url = `${window.PluginClassName.rest.url}/${route}`;
    
    let headers = {
        'X-WP-Nonce': window.PluginClassName.rest.nonce,
        'Content-Type': 'application/json'
    };

    if (['PUT', 'PATCH', 'DELETE'].includes(method.toUpperCase())) {
        headers['X-HTTP-Method-Override'] = method;
        method = 'POST';
    }

    const options = {
        method: method,
        headers: headers
    };

    if (method !== 'GET') {
        options.body = JSON.stringify(data);
    }

    try {
        const response = await fetch(url, options);
        
        // Aggiorna il nonce se presente nella risposta
        const newNonce = response.headers.get('X-WP-Nonce');
        if (newNonce) {
            window.PluginClassName.rest.nonce = newNonce;
        }

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        return response.json();
    } catch (error) {
        console.error('Request failed:', error);
        throw error;
    }
};

export default {
    get(route, data = {}) {
        return request('GET', route, data);
    },
    post(route, data = {}) {
        return request('POST', route, data);
    },
    delete(route, data = {}) {
        return request('DELETE', route, data);
    },
    put(route, data = {}) {
        return request('PUT', route, data);
    },
    patch(route, data = {}) {
        return request('PATCH', route, data);
    }
};