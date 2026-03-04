/**
 * ═══════════════════════════════════════════════════════
 * CHATBOT WIDGET - Customer Service Complaint System
 * State-machine driven chatbot for complaint submission
 * ═══════════════════════════════════════════════════════
 */
(function () {
    'use strict';

    // ─── Configuration ────────────────────────────────────
    var CHATBOT_API_URL = '/chatbot/complaint';
    var CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]');
    CSRF_TOKEN = CSRF_TOKEN ? CSRF_TOKEN.getAttribute('content') : '';

    var CATEGORIES = [
        { key: 'general', label: 'Umum' },
        { key: 'billing', label: 'Tagihan' },
        { key: 'technical', label: 'Teknis' },
        { key: 'other', label: 'Lainnya' }
    ];

    // ─── State Machine ────────────────────────────────────
    var STATE = {
        INIT: 'INIT',
        SELECT_CATEGORY: 'SELECT_CATEGORY',
        AWAITING_COMPLAINT: 'AWAITING_COMPLAINT',
        VALIDATE_CONTACT: 'VALIDATE_CONTACT',
        ASK_CONTACT: 'ASK_CONTACT',
        ASK_NAME: 'ASK_NAME',
        SUBMIT: 'SUBMIT',
        DONE: 'DONE'
    };

    var chatState = STATE.INIT;
    var complaintData = {
        message: '',
        category: 'general',
        contact_info: '',
        user_name: ''
    };

    // ─── Contact Detection Helpers ────────────────────────
    function detectEmail(text) {
        var m = text.match(/[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}/);
        return m ? m[0] : null;
    }
    function detectPhone(text) {
        var m = text.match(/(\+?\d[\d\s\-()]{7,}\d)/);
        if (m) {
            var cleaned = m[1].replace(/\D/g, '');
            if (cleaned.length >= 8 && cleaned.length <= 15) return m[1];
        }
        return null;
    }
    function detectUsername(text) {
        var m = text.match(/@([\w]{3,30})/);
        return m ? '@' + m[1] : null;
    }
    function hasContactInfo(text) {
        return detectEmail(text) || detectPhone(text) || detectUsername(text);
    }

    // ─── DOM Building ─────────────────────────────────────
    function buildChatbotHTML() {
        // Toggle button
        var toggle = document.createElement('button');
        toggle.className = 'chatbot-toggle';
        toggle.id = 'chatbot-toggle';
        toggle.setAttribute('aria-label', 'Buka obrolan dukungan pelanggan');
        toggle.innerHTML =
            '<span class="chatbot-icon-open"><i class="fa fa-comments"></i></span>' +
            '<span class="chatbot-icon-close"><i class="fa fa-times"></i></span>' +
            '<span class="chatbot-badge" id="chatbot-badge" style="display:none;">1</span>';

        // Chat window
        var win = document.createElement('div');
        win.className = 'chatbot-window';
        win.id = 'chatbot-window';
        win.innerHTML =
            '<div class="chatbot-header">' +
            '<div class="chatbot-header-avatar"><i class="fa fa-headphones"></i></div>' +
            '<div class="chatbot-header-info">' +
            '<h4>Dukungan Pelanggan</h4>' +
            '<p>Kami biasanya membalas dalam beberapa menit</p>' +
            '</div>' +
            '</div>' +
            '<div class="chatbot-messages" id="chatbot-messages"></div>' +
            '<div class="chatbot-input-area">' +
            '<input type="text" class="chatbot-input" id="chatbot-input" placeholder="Ketik pesan Anda..." autocomplete="off">' +
            '<button class="chatbot-send-btn" id="chatbot-send" aria-label="Kirim pesan">' +
            '<i class="fa fa-paper-plane"></i>' +
            '</button>' +
            '</div>';

        document.body.appendChild(toggle);
        document.body.appendChild(win);

        // Show badge after 3s
        setTimeout(function () {
            var badge = document.getElementById('chatbot-badge');
            if (badge) badge.style.display = 'flex';
        }, 3000);
    }

    // ─── Message Rendering ────────────────────────────────
    function addMessage(text, type, extraClass) {
        var container = document.getElementById('chatbot-messages');
        if (!container) return;

        var msg = document.createElement('div');
        msg.className = 'chatbot-msg ' + type;
        if (extraClass) msg.className += ' ' + extraClass;
        msg.textContent = text;

        container.appendChild(msg);
        container.scrollTop = container.scrollHeight;
    }

    function addBotMessage(text, extraClass) {
        addMessage(text, 'bot', extraClass);
    }

    function addUserMessage(text) {
        addMessage(text, 'user');
    }

    function showTyping() {
        var container = document.getElementById('chatbot-messages');
        var typing = document.createElement('div');
        typing.className = 'chatbot-typing';
        typing.id = 'chatbot-typing';
        typing.innerHTML = '<span></span><span></span><span></span>';
        container.appendChild(typing);
        container.scrollTop = container.scrollHeight;
    }

    function hideTyping() {
        var el = document.getElementById('chatbot-typing');
        if (el) el.remove();
    }

    function showCategoryButtons() {
        var container = document.getElementById('chatbot-messages');
        var wrapper = document.createElement('div');
        wrapper.className = 'chatbot-quick-replies';
        wrapper.id = 'chatbot-categories';

        CATEGORIES.forEach(function (cat) {
            var btn = document.createElement('button');
            btn.className = 'chatbot-quick-btn';
            btn.textContent = cat.label;
            btn.setAttribute('data-category', cat.key);
            btn.addEventListener('click', function () {
                selectCategory(cat.key, cat.label);
            });
            wrapper.appendChild(btn);
        });

        container.appendChild(wrapper);
        container.scrollTop = container.scrollHeight;
    }

    function removeCategoryButtons() {
        var el = document.getElementById('chatbot-categories');
        if (el) el.remove();
    }

    // ─── State Transitions ────────────────────────────────

    function initChat() {
        chatState = STATE.SELECT_CATEGORY;
        complaintData = { message: '', category: 'general', contact_info: '', user_name: '' };

        setTimeout(function () {
            addBotMessage('Halo! 👋 Ada yang bisa kami bantu? Silakan pilih kategori keluhan Anda:');
            showCategoryButtons();
        }, 500);
    }

    function selectCategory(key, label) {
        removeCategoryButtons();
        addUserMessage(label);
        complaintData.category = key;

        chatState = STATE.AWAITING_COMPLAINT;
        setTimeout(function () {
            addBotMessage('Baik — "' + label + '". Sekarang silakan jelaskan keluhan Anda secara detail. Pastikan untuk menyertakan informasi kontak Anda (email, nomor telepon, atau @username) agar admin kami dapat menghubungi Anda.');
        }, 400);
    }

    function handleInput() {
        var input = document.getElementById('chatbot-input');
        if (!input) return;

        var text = input.value.trim();
        if (!text) return;

        input.value = '';
        addUserMessage(text);

        switch (chatState) {
            case STATE.AWAITING_COMPLAINT:
                complaintData.message = text;
                // Check if contact info is in the message
                if (hasContactInfo(text)) {
                    chatState = STATE.ASK_NAME;
                    setTimeout(function () {
                        addBotMessage('Terima kasih! Kami menemukan informasi kontak Anda. Apakah Anda ingin memberikan nama Anda? (Ketik nama Anda atau ketik "lewati" untuk melanjutkan tanpa nama)');
                    }, 400);
                } else {
                    chatState = STATE.ASK_CONTACT;
                    setTimeout(function () {
                        addBotMessage('Kami tidak dapat menemukan informasi kontak di pesan Anda. Silakan berikan alamat email, nomor telepon, atau @username Anda agar admin dapat menghubungi Anda:');
                    }, 400);
                }
                break;

            case STATE.ASK_CONTACT:
                var contact = hasContactInfo(text);
                if (contact) {
                    complaintData.contact_info = contact;
                    chatState = STATE.ASK_NAME;
                    setTimeout(function () {
                        addBotMessage('Bagus, info kontak Anda sudah kami terima! Apakah Anda ingin memberikan nama Anda? (Ketik nama Anda atau "lewati"):');
                    }, 400);
                } else {
                    addBotMessage('Formatnya tidak terlihat seperti email, nomor telepon, atau @username yang valid. Silakan coba lagi:');
                }
                break;

            case STATE.ASK_NAME:
                if (text.toLowerCase() !== 'lewati' && text.toLowerCase() !== 'skip') {
                    complaintData.user_name = text;
                }
                submitComplaint();
                break;

            case STATE.DONE:
                addBotMessage('Apakah Anda ingin mengirim keluhan lain? Biarkan saya memulainya kembali untuk Anda...');
                setTimeout(function () {
                    initChat();
                }, 1000);
                break;

            default:
                break;
        }
    }

    function submitComplaint() {
        chatState = STATE.SUBMIT;
        showTyping();

        var sendBtn = document.getElementById('chatbot-send');
        var inputEl = document.getElementById('chatbot-input');
        if (sendBtn) sendBtn.disabled = true;
        if (inputEl) inputEl.disabled = true;

        var xhr = new XMLHttpRequest();
        xhr.open('POST', CHATBOT_API_URL, true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.setRequestHeader('X-CSRF-TOKEN', CSRF_TOKEN);
        xhr.setRequestHeader('Accept', 'application/json');

        xhr.onreadystatechange = function () {
            if (xhr.readyState !== 4) return;

            hideTyping();
            if (sendBtn) sendBtn.disabled = false;
            if (inputEl) inputEl.disabled = false;

            try {
                var resp = JSON.parse(xhr.responseText);
                if (xhr.status === 201 && resp.success) {
                    addBotMessage('✅ ' + resp.bot_message, 'success');
                    chatState = STATE.DONE;
                } else if (resp.needs_contact) {
                    chatState = STATE.ASK_CONTACT;
                    addBotMessage(resp.bot_message, 'error');
                } else {
                    addBotMessage(resp.bot_message || 'Terjadi kesalahan. Silakan coba lagi.', 'error');
                    chatState = STATE.AWAITING_COMPLAINT;
                }
            } catch (e) {
                addBotMessage('Maaf, ada kesalahan koneksi. Silakan coba lagi nanti.', 'error');
                chatState = STATE.AWAITING_COMPLAINT;
            }
        };

        xhr.send(JSON.stringify(complaintData));
    }

    // ─── Event Bindings ───────────────────────────────────
    function bindEvents() {
        var toggle = document.getElementById('chatbot-toggle');
        var win = document.getElementById('chatbot-window');
        var sendBtn = document.getElementById('chatbot-send');
        var input = document.getElementById('chatbot-input');

        if (!toggle || !win) return;

        var isOpen = false;
        var hasInited = false;

        toggle.addEventListener('click', function () {
            isOpen = !isOpen;
            toggle.classList.toggle('active', isOpen);
            win.classList.toggle('open', isOpen);

            // Hide badge
            var badge = document.getElementById('chatbot-badge');
            if (badge) badge.style.display = 'none';

            if (isOpen && !hasInited) {
                hasInited = true;
                initChat();
            }

            if (isOpen && input) {
                setTimeout(function () { input.focus(); }, 350);
            }
        });

        if (sendBtn) {
            sendBtn.addEventListener('click', function () {
                handleInput();
            });
        }

        if (input) {
            input.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    handleInput();
                }
            });
        }
    }

    // ─── Initialize ───────────────────────────────────────
    function init() {
        buildChatbotHTML();
        bindEvents();
    }

    // Wait for DOM
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
