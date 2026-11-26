/**
 * Nucomu Cafe — Offline-First Code 128B Barcode Renderer, Hardware HID Scanner Listener,
 * and Smart Camera/Photo Scanner Helper.
 *
 * Designed to work 100% reliably on local LAN/hotspot hosting (e.g. 192.168.x.x:8000)
 * without depending on external CDNs for 1D barcode generation.
 */
(function (global) {
    'use strict';

    // Complete ISO/IEC 15417 Code 128 symbol pattern table (values 0..106)
    // Values 0..105 each have 6 elements (bar, space, bar, space, bar, space) summing to 11 modules.
    // Value 106 (Stop) has 7 elements summing to 13 modules.
    const CODE128_PATTERNS = [
        '212222', '222122', '222221', '121223', '121322', '131222', '122213', '122312', '132212', '221213', // 0-9
        '221312', '231212', '112232', '122132', '122231', '113222', '123122', '123221', '223211', '221132', // 10-19
        '221231', '213212', '223112', '312131', '311222', '321122', '321221', '312212', '322112', '322211', // 20-29
        '212123', '212321', '232121', '111323', '131123', '131321', '112313', '132113', '132311', '211313', // 30-39
        '231113', '231311', '112133', '112331', '132131', '113123', '113321', '133121', '313121', '211331', // 40-49
        '231131', '213113', '213311', '213131', '311123', '311321', '331121', '312113', '312311', '332111', // 50-59
        '314111', '221411', '431111', '111224', '111422', '121124', '121421', '141122', '141221', '112214', // 60-69
        '112412', '122114', '122411', '142112', '142211', '241211', '221114', '413111', '241112', '134111', // 70-79
        '111242', '121142', '121241', '114212', '124112', '124211', '411212', '421112', '421211', '212141', // 80-89
        '214121', '412121', '111143', '111341', '131141', '114113', '114311', '411113', '411311', '113141', // 90-99
        '114131', '311141', '411131', '211412', '211214', '211232', '2331112', // 100-106 (104=Start B, 106=Stop)
    ];

    function sanitizeBarcodeText(rawValue) {
        const str = String(rawValue || '').trim();
        let clean = '';
        for (let i = 0; i < str.length; i++) {
            const code = str.charCodeAt(i);
            if (code >= 32 && code <= 126) {
                clean += str.charAt(i);
            }
        }
        return clean || 'NCM-ORDER';
    }

    function encodeCode128B(text) {
        const clean = sanitizeBarcodeText(text);
        const startCodeB = 104;
        const values = [startCodeB];
        let checksum = startCodeB;

        for (let i = 0; i < clean.length; i++) {
            const val = clean.charCodeAt(i) - 32;
            values.push(val);
            checksum += val * (i + 1);
        }

        const checkDigit = checksum % 103;
        values.push(checkDigit);
        values.push(106); // Stop

        return {
            text: clean,
            values,
            patterns: values.map((v) => CODE128_PATTERNS[v]),
        };
    }

    function escapeXml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    /**
     * Render a crisp, scanner-ready ISO Code 128B SVG barcode into a container element.
     *
     * @param {HTMLElement|string} target
     * @param {string} value
     * @param {object} [options]
     * @returns {string}
     */
    function renderCode128Svg(target, value, options) {
        const el = typeof target === 'string' ? document.querySelector(target) : target;
        const opts = Object.assign(
            {
                barHeight: 68,
                moduleWidth: 2,
                quietZoneModules: 10,
                displayValue: true,
                fontSize: 14,
                textMargin: 8,
                lineColor: '#000000',
                background: '#FFFFFF',
                maxWidth: '100%',
            },
            options || {}
        );

        const encoded = encodeCode128B(value);
        let totalModules = opts.quietZoneModules * 2;
        for (let i = 0; i < encoded.patterns.length; i++) {
            const p = encoded.patterns[i];
            for (let j = 0; j < p.length; j++) {
                totalModules += Number(p.charAt(j));
            }
        }

        const paddingTop = 8;
        const paddingBottom = opts.displayValue ? opts.textMargin + opts.fontSize + 8 : 8;
        const svgWidth = totalModules * opts.moduleWidth;
        const svgHeight = paddingTop + opts.barHeight + paddingBottom;

        let rects = '';
        let xModules = opts.quietZoneModules;

        for (let i = 0; i < encoded.patterns.length; i++) {
            const pattern = encoded.patterns[i];
            for (let j = 0; j < pattern.length; j++) {
                const w = Number(pattern.charAt(j));
                const isBar = j % 2 === 0;
                if (isBar) {
                    const rx = xModules * opts.moduleWidth;
                    const rw = w * opts.moduleWidth;
                    rects += `<rect x="${rx}" y="${paddingTop}" width="${rw}" height="${opts.barHeight}" fill="${opts.lineColor}"/>`;
                }
                xModules += w;
            }
        }

        const labelMarkup = opts.displayValue
            ? `<text x="${svgWidth / 2}" y="${paddingTop + opts.barHeight + opts.textMargin + opts.fontSize - 2}" text-anchor="middle" font-family="ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace" font-size="${opts.fontSize}" font-weight="700" letter-spacing="1.5" fill="${opts.lineColor}">${escapeXml(encoded.text)}</text>`
            : '';

        const svg = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ${svgWidth} ${svgHeight}" width="100%" style="max-width:${opts.maxWidth};height:auto;display:block;margin:0 auto;" role="img" aria-label="Barcode ${escapeXml(encoded.text)}"><rect x="0" y="0" width="${svgWidth}" height="${svgHeight}" fill="${opts.background}"/>${rects}${labelMarkup}</svg>`;

        if (el) {
            el.innerHTML = svg;
        }

        return svg;
    }

    /**
     * Extract clean Nucomu order code from either plain barcode text or a QR URL.
     */
    function extractOrderCode(raw) {
        const trimmed = String(raw || '').trim();
        if (!trimmed) {
            return '';
        }

        const match = trimmed.match(/(NCM-\d{8}-\d+)/i);
        if (match && match[1]) {
            return match[1].toUpperCase();
        }

        if (trimmed.includes('/')) {
            try {
                const parsed = new URL(trimmed);
                const segments = parsed.pathname.split('/').filter(Boolean);
                if (segments.length > 0) {
                    return segments[segments.length - 1].toUpperCase();
                }
            } catch (e) {
                const parts = trimmed.split('/').filter(Boolean);
                if (parts.length > 0) {
                    return parts[parts.length - 1].toUpperCase();
                }
            }
        }

        return trimmed.toUpperCase();
    }

    /**
     * Inspect browser camera API availability and Secure Context (HTTPS vs LAN HTTP IP).
     */
    function checkCameraSupport() {
        const hostname = global.location ? global.location.hostname : '';
        const protocol = global.location ? global.location.protocol : '';
        const origin = global.location ? global.location.origin : '';
        const isLocalhost = hostname === 'localhost' || hostname === '127.0.0.1' || hostname === '::1';
        const isSecure = Boolean(global.isSecureContext) || protocol === 'https:' || isLocalhost;
        const hasMediaDevices = Boolean(navigator.mediaDevices && typeof navigator.mediaDevices.getUserMedia === 'function');

        if (!isSecure || !hasMediaDevices) {
            return {
                supported: false,
                isSecureContext: isSecure,
                isLanHttp: !isSecure && protocol === 'http:' && !isLocalhost,
                origin,
                reason: !isSecure
                    ? `Browser memblokir akses kamera live di alamat HTTP (${origin}) karena bukan HTTPS/localhost.`
                    : 'Peramban ini tidak mendukung streaming kamera langsung (getUserMedia).',
            };
        }

        return {
            supported: true,
            isSecureContext: true,
            isLanHttp: false,
            origin,
            reason: '',
        };
    }

    /**
     * Return Html5Qrcode supported formats list including both QR Code and 1D Linear Barcodes.
     */
    function getSupportedHtml5QrFormats() {
        if (typeof global.Html5QrcodeSupportedFormats === 'undefined') {
            return undefined;
        }
        const F = global.Html5QrcodeSupportedFormats;
        return [
            F.QR_CODE,
            F.CODE_128,
            F.CODE_39,
            F.CODE_93,
            F.EAN_13,
            F.EAN_8,
            F.UPC_A,
            F.UPC_E,
            F.ITF,
            F.CODABAR,
        ].filter((v) => typeof v !== 'undefined');
    }

    /**
     * Start Html5Qrcode camera with automatic rear -> laptop front/default webcam fallback.
     */
    async function startSmartCamera(elementId, onDecodeSuccess, options) {
        const support = checkCameraSupport();
        if (!support.supported) {
            const err = new Error(support.reason);
            err.code = support.isLanHttp ? 'INSECURE_CONTEXT' : 'CAMERA_UNSUPPORTED';
            err.support = support;
            throw err;
        }

        if (typeof global.Html5Qrcode === 'undefined') {
            const err = new Error('Library pemindai kamera belum termuat. Periksa koneksi atau gunakan alat barcode scanner.');
            err.code = 'LIB_MISSING';
            throw err;
        }

        const formats = getSupportedHtml5QrFormats();
        const scannerConfig = formats ? { formatsToSupport: formats, verbose: false } : { verbose: false };
        const scanner = new global.Html5Qrcode(elementId, scannerConfig);
        const startOpts = Object.assign(
            {
                fps: 12,
                qrbox: (viewfinderWidth, viewfinderHeight) => {
                    const w = Math.min(280, Math.floor(viewfinderWidth * 0.82));
                    const h = Math.min(200, Math.floor(viewfinderHeight * 0.68));
                    return { width: Math.max(160, w), height: Math.max(120, h) };
                },
                aspectRatio: 1.333,
            },
            options || {}
        );

        // 1. Try rear camera first (phones/tablets)
        try {
            await scanner.start({ facingMode: 'environment' }, startOpts, onDecodeSuccess, () => {});
            return scanner;
        } catch (rearErr) {
            // 2. Fallback to enumerated camera list (laptop webcams often reject facingMode: environment)
            try {
                const cameras = await global.Html5Qrcode.getCameras();
                if (Array.isArray(cameras) && cameras.length > 0) {
                    const preferred = cameras.find((c) => /back|rear|environment/i.test(c.label || '')) || cameras[0];
                    await scanner.start(preferred.id, startOpts, onDecodeSuccess, () => {});
                    return scanner;
                }
            } catch (listErr) {
                // Fall through to facingMode: user
            }

            // 3. Final fallback: front/user camera
            try {
                await scanner.start({ facingMode: 'user' }, startOpts, onDecodeSuccess, () => {});
                return scanner;
            } catch (userErr) {
                throw rearErr || userErr;
            }
        }
    }

    /**
     * Scan QR code or 1D barcode from an uploaded or camera-captured photo file (works over plain HTTP LAN IP!).
     */
    async function scanImageFile(elementId, file) {
        if (!file) {
            throw new Error('Tidak ada gambar yang dipilih.');
        }
        if (typeof global.Html5Qrcode === 'undefined') {
            throw new Error('Library scanner belum siap.');
        }

        const formats = getSupportedHtml5QrFormats();
        const scannerConfig = formats ? { formatsToSupport: formats, verbose: false } : { verbose: false };
        const fileScanner = new global.Html5Qrcode(elementId, scannerConfig);

        try {
            const decodedText = await fileScanner.scanFile(file, false);
            try {
                fileScanner.clear();
            } catch (e) {}
            return decodedText;
        } catch (err) {
            try {
                fileScanner.clear();
            } catch (e) {}
            throw err;
        }
    }

    /**
     * Attach a global USB/Bluetooth HID Barcode Scanner (keyboard wedge) listener.
     * Automatically writes scanned barcode into the target order search input and triggers lookup.
     *
     * @param {object} config
     * @param {function(): HTMLElement|null} config.getInput
     * @param {function(string, string): void} config.onScan
     * @param {function(): boolean} [config.isActive]
     */
    function attachHardwareScanner(config) {
        let buffer = '';
        let lastKeyTime = 0;
        let resetTimer = null;

        function clearBuffer() {
            buffer = '';
            lastKeyTime = 0;
            if (resetTimer) {
                clearTimeout(resetTimer);
                resetTimer = null;
            }
        }

        function isEditableElement(el) {
            if (!el) return false;
            const tag = (el.tagName || '').toUpperCase();
            return tag === 'INPUT' || tag === 'TEXTAREA' || tag === 'SELECT' || el.isContentEditable;
        }

        document.addEventListener('keydown', (event) => {
            if (event.ctrlKey || event.altKey || event.metaKey) {
                return;
            }

            if (typeof config.isActive === 'function' && !config.isActive()) {
                return;
            }

            const targetInput = typeof config.getInput === 'function' ? config.getInput() : null;
            const activeEl = document.activeElement;
            const isTargetInputFocused = Boolean(targetInput && activeEl === targetInput);

            // Do not hijack keystrokes while user is typing in OTHER form fields (e.g. customer name / menu search)
            if (!isTargetInputFocused && isEditableElement(activeEl)) {
                return;
            }

            const now = Date.now();
            const gap = lastKeyTime > 0 ? now - lastKeyTime : 0;

            if (event.key === 'Enter' || event.key === 'Tab') {
                if (isTargetInputFocused) {
                    // Let the search input's own form submit handle Enter cleanly
                    clearBuffer();
                    return;
                }

                const candidate = buffer.trim();
                const looksLikeOrderCode = /^NCM-\d{6,}/i.test(candidate) || candidate.includes('/qr/pesanan/');
                if (candidate.length >= 6 && (looksLikeOrderCode || candidate.length >= 10)) {
                    event.preventDefault();
                    const cleanCode = extractOrderCode(candidate);
                    if (targetInput) {
                        targetInput.value = cleanCode;
                        targetInput.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                    if (typeof config.onScan === 'function') {
                        config.onScan(cleanCode, candidate);
                    }
                }
                clearBuffer();
                return;
            }

            if (event.key && event.key.length === 1) {
                // Hardware barcode scanners emit characters rapidly (< 90ms apart)
                if (lastKeyTime > 0 && gap > 120) {
                    buffer = '';
                }
                buffer += event.key;
                lastKeyTime = now;

                if (resetTimer) {
                    clearTimeout(resetTimer);
                }
                resetTimer = setTimeout(() => {
                    buffer = '';
                    lastKeyTime = 0;
                }, 260);
            }
        });
    }

    global.NucomuBarcode = {
        CODE128_PATTERNS,
        encodeCode128B,
        renderCode128Svg,
        extractOrderCode,
        checkCameraSupport,
        getSupportedHtml5QrFormats,
        startSmartCamera,
        scanImageFile,
        attachHardwareScanner,
    };
})(window);
