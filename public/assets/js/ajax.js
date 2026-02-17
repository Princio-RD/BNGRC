/**
 * Unified AJAX Module for BNGRC
 * Provides a consistent structure for all AJAX requests
 */
const Ajax = {
    /**
     * Standardized XMLHttpRequest with error handling and loading state
     * @param {string} url - The endpoint URL
     * @param {Object} options - Configuration options
     * @param {string} options.method - HTTP method (GET, POST, PUT, DELETE)
     * @param {Object|FormData|string|null} options.data - Data to send
     * @param {string|null} options.contentType - Content-Type header (default: application/json)
     * @param {Function} options.onSuccess - Success callback
     * @param {Function} options.onError - Error callback
     * @param {Function} options.onStart - Called before request
     * @param {Function} options.onComplete - Called after request (success or error)
     * @param {HTMLElement} options.statusElement - Element to update with status messages
     * @returns {Promise}
     */
    request(url, options = {}) {
        const {
            method = 'GET',
            data = null,
            contentType = 'application/json',
            onSuccess = null,
            onError = null,
            onStart = null,
            onComplete = null,
            statusElement = null
        } = options;

        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            const upperMethod = method.toUpperCase();
            let finalUrl = url;
            let payload = null;

            if (onStart) onStart();
            if (statusElement) statusElement.textContent = 'Chargement...';

            if (data && upperMethod === 'GET') {
                const params = data instanceof URLSearchParams
                    ? data.toString()
                    : new URLSearchParams(data).toString();
                finalUrl += (finalUrl.includes('?') ? '&' : '?') + params;
            } else if (data && upperMethod !== 'GET') {
                if (data instanceof FormData) {
                    payload = data;
                } else if (contentType === 'application/json') {
                    payload = JSON.stringify(data);
                } else if (contentType === 'application/x-www-form-urlencoded') {
                    payload = new URLSearchParams(data).toString();
                } else {
                    payload = data;
                }
            }

            xhr.addEventListener('error', () => {
                const error = new Error('Erreur réseau');
                if (onError) onError(error);
                if (statusElement) statusElement.textContent = 'Erreur lors de la requête.';
                if (onComplete) onComplete(error, null);
                reject(error);
            });

            xhr.onreadystatechange = () => {
                if (xhr.readyState !== 4) return;

                const isOk = xhr.status >= 200 && xhr.status < 300;
                const responseType = xhr.getResponseHeader('content-type') || '';
                let responseData = xhr.responseText;

                if (responseType.includes('application/json')) {
                    try {
                        responseData = JSON.parse(xhr.responseText);
                    } catch (e) {
                        // Keep raw text if JSON parsing fails
                    }
                }

                if (isOk) {
                    if (onSuccess) onSuccess(responseData);
                    if (statusElement) statusElement.textContent = 'Succès.';
                    if (onComplete) onComplete(null, responseData);
                    resolve(responseData);
                    return;
                }

                const error = new Error(`HTTP Error: ${xhr.status} ${xhr.statusText}`);
                if (onError) onError(error);
                if (statusElement) statusElement.textContent = 'Erreur lors de la requête.';
                if (onComplete) onComplete(error, null);
                reject(error);
            };

            xhr.open(upperMethod, finalUrl, true);

            if (!(payload instanceof FormData) && contentType) {
                xhr.setRequestHeader('Content-Type', contentType);
            }

            xhr.send(payload);
        });
    },

    /**
     * GET request
     */
    get(url, options = {}) {
        return this.request(url, { ...options, method: 'GET' });
    },

    /**
     * POST request
     */
    post(url, data = null, options = {}) {
        return this.request(url, { ...options, method: 'POST', data });
    },

    /**
     * PUT request
     */
    put(url, data = null, options = {}) {
        return this.request(url, { ...options, method: 'PUT', data });
    },

    /**
     * DELETE request
     */
    delete(url, options = {}) {
        return this.request(url, { ...options, method: 'DELETE' });
    }
};
